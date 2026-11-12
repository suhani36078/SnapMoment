<?php
require __DIR__.'/common.php';
$s=$pdo->query("SELECT p.id,p.photo_path,p.uploaded_at,v.name FROM photos p JOIN visitors v ON v.id=p.visitor_id WHERE p.status='approved' ORDER BY p.uploaded_at DESC");
reply(200,['success'=>true,'photos'=>$s->fetchAll()]);
