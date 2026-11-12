<?php
require __DIR__.'/common.php';$v=visitor_from_cookie($pdo);
reply(200,['success'=>true,'visitor'=>$v?['id'=>(int)$v['id'],'code'=>$v['visitor_code'],'name'=>$v['name']]:null]);
