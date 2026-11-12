<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
require_once __DIR__ . '/../config/db.php';
function reply($status,$data){ http_response_code($status); echo json_encode($data); exit; }
function visitor_from_cookie($pdo){
 $token=$_COOKIE['snapmoment_visitor']??'';
 if(!is_string($token)||!preg_match('/^[a-f0-9]{64}$/',$token)) return null;
 $s=$pdo->prepare('SELECT id,visitor_code,name FROM visitors WHERE visitor_token=?'); $s->execute([$token]); return $s->fetch() ?: null;
}
