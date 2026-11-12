<?php
require __DIR__.'/common.php';
if($_SERVER['REQUEST_METHOD']!=='POST') reply(405,['success'=>false,'message'=>'POST only']);
$in=json_decode(file_get_contents('php://input'),true) ?: [];
$name=trim((string)($in['name']??''));$code=strtoupper(trim((string)($in['visitor_code']??'')));
if(mb_strlen($name)<2||mb_strlen($name)>100) reply(422,['success'=>false,'message'=>'Enter a name between 2 and 100 characters.']);
$old=visitor_from_cookie($pdo);
if(!$old && $code!==''){
 if(!preg_match('/^VIS[0-9]{5,}$/',$code)) reply(422,['success'=>false,'message'=>'Enter a valid Visitor ID, for example VIS00001.']);
 $st=$pdo->prepare('SELECT id,visitor_code,visitor_token,name FROM visitors WHERE visitor_code=?');$st->execute([$code]);$found=$st->fetch();
 if(!$found || mb_strtolower($found['name'])!==mb_strtolower($name)) reply(404,['success'=>false,'message'=>'Visitor ID and name did not match.']);
 $old=$found;
}
if($old){$pdo->prepare('UPDATE visitors SET name=?,last_seen_at=NOW() WHERE id=?')->execute([$name,$old['id']]);$v=$old;$v['name']=$name;$returning=true;if(isset($v['visitor_token']))setcookie('snapmoment_visitor',$v['visitor_token'],['expires'=>time()+31536000,'path'=>'/','httponly'=>true,'samesite'=>'Lax']);}
else{$token=bin2hex(random_bytes(32));$pdo->beginTransaction();try{$pdo->prepare("INSERT INTO visitors(visitor_code,visitor_token,name) VALUES(?,?,?)")->execute(['TEMP'.bin2hex(random_bytes(5)),$token,$name]);$id=(int)$pdo->lastInsertId();$code='VIS'.str_pad((string)$id,5,'0',STR_PAD_LEFT);$pdo->prepare('UPDATE visitors SET visitor_code=? WHERE id=?')->execute([$code,$id]);$pdo->commit();}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();reply(500,['success'=>false,'message'=>'Could not create visitor profile.']);}$v=['id'=>$id,'visitor_code'=>$code,'name'=>$name];$returning=false;setcookie('snapmoment_visitor',$token,['expires'=>time()+31536000,'path'=>'/','httponly'=>true,'samesite'=>'Lax']);}
reply(200,['success'=>true,'returning'=>$returning,'visitor'=>['id'=>(int)$v['id'],'code'=>$v['visitor_code'],'name'=>$v['name']],'message'=>$returning?'Welcome back! Previous photos are linked to your profile.':'Profile saved. Keep your Visitor ID to return from another device.']);
