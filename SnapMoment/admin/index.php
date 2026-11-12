<?php
require __DIR__.'/auth.php';
admin_required();

// A session-bound token protects the permanent-delete action from cross-site requests.
if (empty($_SESSION['snap_admin_csrf'])) {
    $_SESSION['snap_admin_csrf'] = bin2hex(random_bytes(32));
}
$notice = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_rejected') {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['snap_admin_csrf'], $token)) {
        http_response_code(403);
        exit('Invalid security token. Please refresh the page and try again.');
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    if (!$id || $id < 1) {
        header('Location: index.php?filter=rejected&delete_error=1');
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, photo_path FROM photos WHERE id = ? AND status = 'rejected'");
    $stmt->execute([$id]);
    $photo = $stmt->fetch();

    if (!$photo) {
        header('Location: index.php?filter=rejected&delete_error=1');
        exit;
    }

    // Delete only files whose resolved path is inside this project's uploads/photos folder.
    $uploadsDir = realpath(__DIR__ . '/../uploads/photos');
    $relativePath = str_replace('\\', '/', (string)$photo['photo_path']);
    $candidate = realpath(__DIR__ . '/../' . ltrim($relativePath, '/'));
    $fileDeleted = true;
    if ($candidate !== false && $uploadsDir !== false) {
        $insideUploads = str_starts_with($candidate, $uploadsDir . DIRECTORY_SEPARATOR);
        if ($insideUploads && is_file($candidate)) {
            $fileDeleted = unlink($candidate);
        }
    }

    if (!$fileDeleted) {
        header('Location: index.php?filter=rejected&delete_error=1');
        exit;
    }

    // Remove the database row only after the file has been safely handled.
    $delete = $pdo->prepare("DELETE FROM photos WHERE id = ? AND status = 'rejected'");
    $delete->execute([$id]);
    header('Location: index.php?filter=rejected&deleted=1');
    exit;
}

if (isset($_GET['action'], $_GET['id']) && in_array($_GET['action'], ['approve', 'reject'], true)) {
    $id = (int)$_GET['id'];
    $status = $_GET['action'] === 'approve' ? 'approved' : 'rejected';
    $st = $pdo->prepare('UPDATE photos SET status=?, reviewed_at=NOW() WHERE id=?');
    $st->execute([$status, $id]);
    header('Location: index.php?updated=1');
    exit;
}

$filter = $_GET['filter'] ?? 'pending';
if (!in_array($filter, ['pending', 'approved', 'rejected', 'all'], true)) $filter = 'pending';
$sql = "SELECT p.*,v.name,v.visitor_code FROM photos p JOIN visitors v ON p.visitor_id=v.id";
if ($filter !== 'all') $sql .= " WHERE p.status=" . $pdo->quote($filter);
$sql .= " ORDER BY p.uploaded_at DESC";
$photos = $pdo->query($sql)->fetchAll();
$counts = $pdo->query("SELECT status,COUNT(*) total FROM photos GROUP BY status")->fetchAll();
$stats = ['pending'=>0,'approved'=>0,'rejected'=>0];
foreach ($counts as $c) $stats[$c['status']] = $c['total'];
?><!doctype html>
<html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>SnapMoment Admin</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body class="admin-body">
<header class="admin-top"><a class="brand" href="../index.php">✳ snap<span>moment</span></a><span>Signed in as <?=htmlspecialchars($_SESSION['snap_admin'])?> · <a href="logout.php">Logout</a></span></header>
<main class="admin-wrap">
<div class="admin-heading"><div><small class="eyebrow">CONTROL ROOM</small><h1>Photo moderation</h1><p>Review uploads before they appear on the event display.</p></div><a class="secondary link-btn" href="../display.php" target="_blank">Open slideshow ↗</a></div>
<div class="stat-grid"><div><b><?=$stats['pending']?></b><span>Pending</span></div><div><b><?=$stats['approved']?></b><span>Approved</span></div><div><b><?=$stats['rejected']?></b><span>Rejected</span></div></div>
<nav class="filters"><?php foreach(['pending','approved','rejected','all'] as $f):?><a class="<?=$filter===$f?'active':''?>" href="?filter=<?=$f?>"><?=ucfirst($f)?></a><?php endforeach;?></nav>
<?php if(isset($_GET['updated'])):?><p class="success">Photo status updated.</p><?php endif;?>
<?php if(isset($_GET['deleted'])):?><p class="success">Rejected photo permanently deleted.</p><?php endif;?>
<?php if(isset($_GET['delete_error'])):?><p class="error">Could not delete this photo. Please refresh and try again.</p><?php endif;?>
<div class="admin-photos">
<?php foreach($photos as $p):?>
<article class="admin-photo"><img src="../<?=htmlspecialchars($p['photo_path'])?>" alt="Uploaded photo"><div><b><?=htmlspecialchars($p['name'])?></b><small><?=htmlspecialchars($p['visitor_code'])?> · <?=htmlspecialchars($p['uploaded_at'])?></small><span class="status <?=$p['status']?>"><?=ucfirst($p['status'])?></span><div class="moderate">
<?php if($p['status']!=='approved'):?><a class="approve" href="?action=approve&id=<?=$p['id']?>">Approve</a><?php endif;?>
<?php if($p['status']!=='rejected'):?><a class="reject" href="?action=reject&id=<?=$p['id']?>" onclick="return confirm('Reject this photo?')">Reject</a><?php endif;?>
<?php if($p['status']==='rejected'):?>
<form method="post" class="delete-photo-form" onsubmit="return confirm('Permanently delete this rejected photo? This cannot be undone.');">
<input type="hidden" name="action" value="delete_rejected"><input type="hidden" name="id" value="<?=$p['id']?>"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars($_SESSION['snap_admin_csrf'])?>">
<button type="submit" class="reject delete-permanent-btn">Delete Permanently</button>
</form>
<?php endif;?>
</div></div></article>
<?php endforeach;?>
<?php if(!$photos):?><p class="empty">No photos in this section yet.</p><?php endif;?>
</div></main></body></html>
