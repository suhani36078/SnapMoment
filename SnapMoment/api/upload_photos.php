<?php
require __DIR__.'/common.php';
if($_SERVER['REQUEST_METHOD']!=='POST') reply(405,['success'=>false,'message'=>'POST only']);
$v=visitor_from_cookie($pdo);if(!$v) reply(401,['success'=>false,'message'=>'Visitor session missing. Please enter your name again.']);
if(empty($_POST['consent'])) reply(422,['success'=>false,'message'=>'Please confirm that these photos are okay to share.']);
if(!isset($_FILES['photos'])) reply(422,['success'=>false,'message'=>'Choose at least one photo.']);
$f=$_FILES['photos'];$n=count($f['name']);
if($n<1||$n>20) reply(422,['success'=>false,'message'=>'Upload between 1 and 20 photos at a time.']);
$dir=realpath(__DIR__.'/../uploads/photos');if(!$dir) reply(500,['success'=>false,'message'=>'Upload folder is missing.']);
$mimeMap=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];$finfo=new finfo(FILEINFO_MIME_TYPE);$ready=[];$total=0;
for($i=0;$i<$n;$i++){
 if(($f['error'][$i]??1)!==UPLOAD_ERR_OK) reply(422,['success'=>false,'message'=>'A file could not be uploaded. Each photo must be under 8 MB.']);
 $tmp=$f['tmp_name'][$i];$size=(int)$f['size'][$i];$mime=$finfo->file($tmp);
 if($size<1||$size>8*1024*1024||!isset($mimeMap[$mime])||!getimagesize($tmp)) reply(422,['success'=>false,'message'=>'Only valid JPG, PNG or WEBP images under 8 MB are allowed.']);
 $total+=$size;if($total>40*1024*1024) reply(422,['success'=>false,'message'=>'Total upload size cannot exceed 40 MB.']);
 $ready[]=['tmp'=>$tmp,'size'=>$size,'mime'=>$mime,'ext'=>$mimeMap[$mime],'name'=>substr(basename($f['name'][$i]),0,255)];
}
$paths=[];try{$pdo->beginTransaction();$st=$pdo->prepare("INSERT INTO photos(visitor_id,photo_path,original_name,mime_type,file_size,status) VALUES(?,?,?,?,?,'pending')");
 foreach($ready as $p){$fn=bin2hex(random_bytes(16)).'.'.$p['ext'];if(!move_uploaded_file($p['tmp'],$dir.DIRECTORY_SEPARATOR.$fn))throw new Exception();$paths[]=$dir.DIRECTORY_SEPARATOR.$fn;$st->execute([$v['id'],'uploads/photos/'.$fn,$p['name'],$p['mime'],$p['size']]);}
 $pdo->commit();reply(200,['success'=>true,'message'=>count($ready).' photo(s) uploaded! They will appear after admin approval.','uploaded'=>count($ready)]);
}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();foreach($paths as $p)if(is_file($p))unlink($p);reply(500,['success'=>false,'message'=>'Upload failed. Please try again.']);}
