<?php require_once '../auth.php';
require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $action=$_POST['action']??'';
    if($action==='add'){
        $v=[trim($_POST['title']??''), trim($_POST['event_date']??''), trim($_POST['location']??''), trim($_POST['description']??'')];
        $s=$pdo->prepare('INSERT INTO events(title,event_date,location,description) VALUES(?,?,?,?)');
        $s->execute($v);
        $_SESSION['notice']='Events item added.';
    }
    elseif($action==='delete'){
        $s=$pdo->prepare('DELETE FROM events WHERE id=?');
        $s->execute([(int)$_POST['id']]);
        $_SESSION['notice']='Item deleted.';
    }
    header('Location: events.php');
    exit;
}
require_once '../portal_helpers.php';
$rows=$pdo->query('SELECT * FROM events ORDER BY id DESC')->fetchAll();
portal_start('Manage Events');
?>
<section class="panel"><h2>Add Event</h2><form method="post">
<?=portal_csrf()
?>
<input type="hidden" name="action" value="add"><label>Title</label><input name="title" required><label>Event Date</label><input name="event_date" type="date" required><label>Location</label><input name="location" required><label>Description</label><textarea name="description" rows="3"></textarea><button>Publish</button></form></section>
<section class="panel"><h2>Existing Events</h2>
<?php foreach($rows as $r):
?>
<div class="feed"><b>
<?=h($r['title'])
?>
 ·
<?=h($r['event_date'])
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
