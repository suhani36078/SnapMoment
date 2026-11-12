<?php
require __DIR__.'/common.php';$v=visitor_from_cookie($pdo);if(!$v)reply(401,['success'=>false,'message'=>'Please identify yourself first.']);
$s=$pdo->prepare('SELECT id,photo_path,status,uploaded_at FROM photos WHERE visitor_id=? ORDER BY uploaded_at DESC');$s->execute([$v['id']]);
reply(200,['success'=>true,'photos'=>$s->fetchAll()]);
