<?php require_once '../auth.php';
require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $action=$_POST['action']??'';
    if($action==='add'){
        $v=[trim($_POST['title']??''), trim($_POST['body']??'')];
        $s=$pdo->prepare('INSERT INTO announcements(title,body) VALUES(?,?)');
        $s->execute($v);
        $_SESSION['notice']='Announcements item added.';
    }
    elseif($action==='delete'){
        $s=$pdo->prepare('DELETE FROM announcements WHERE id=?');
        $s->execute([(int)$_POST['id']]);
        $_SESSION['notice']='Item deleted.';
    }
    header('Location: announcements.php');
    exit;
}
require_once '../portal_helpers.php';
$rows=$pdo->query('SELECT * FROM announcements ORDER BY id DESC')->fetchAll();
portal_start('Manage Announcements');
?>
<section class="panel"><h2>Add Announcement</h2><form method="post">
<?=portal_csrf()
?>
<input type="hidden" name="action" value="add"><label>Title</label><input name="title" required><label>Body</label><textarea name="body" rows="3"></textarea><button>Publish</button></form></section>
<section class="panel"><h2>Existing Announcements</h2>
<?php foreach($rows as $r):
?>
<div class="feed"><b>
<?=h($r['title'])
?>
 ·
<?=h($r['body'])
?>
</b><p>
<?=h($r['description']??$r['body']??$r['event_date']??$r['category']??'')
?>
</p><form method="post" onsubmit="return confirm('Delete this item?')">
<?=portal_csrf()
?>
<input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="
<?=$r['id']
?>
"><button class="danger">Delete</button></form></div>
<?php endforeach;
?>
<?php if(!$rows):
?>
<p class="muted">No records yet.</p>
<?php endif;
?>
</section>
<?php portal_end();
?>
