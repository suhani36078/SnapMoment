<?php
session_start();require_once __DIR__.'/../config/db.php';
function admin_required(){if(empty($_SESSION['snap_admin'])){header('Location: login.php');exit;}}
