<?php require_once '../auth.php';
require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $action=$_POST['action']??'';
    if($action==='add'){
        $v=[trim($_POST['title']??''), trim($_POST['category']??''), trim($_POST['description']??''), trim($_POST['url']??'')];
        $s=$pdo->prepare('INSERT INTO resources(title,category,description,url) VALUES(?,?,?,?)');
        $s->execute($v);
        $_SESSION['notice']='Resources item added.';
    }
    elseif($action==='delete'){
        $s=$pdo->prepare('DELETE FROM resources WHERE id=?');
        $s->execute([(int)$_POST['id']]);
        $_SESSION['notice']='Item deleted.';
    }
    header('Location: resources.php');
    exit;
}
require_once '../portal_helpers.php';
$rows=$pdo->query('SELECT * FROM resources ORDER BY id DESC')->fetchAll();
portal_start('Manage Resources');
?>
<section class="panel"><h2>Add Resource</h2><form method="post">
<?=portal_csrf()
?>
<input type="hidden" name="action" value="add"><label>Title</label><input name="title" required><label>Category</label><input name="category" required><label>Description</label><textarea name="description" rows="3"></textarea><label>Url</label><input name="url" type="url" required><button>Publish</button></form></section>
<section class="panel"><h2>Existing Resources</h2>
<?php foreach($rows as $r):
?>
<div class="feed"><b>
<?=h($r['title'])
?>
 ·
<?=h($r['category'])
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
