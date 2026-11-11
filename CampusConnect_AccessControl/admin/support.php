<?php require_once '../auth.php';
require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $s=$pdo->prepare('UPDATE support_tickets SET response=?,status=? WHERE id=?');
    $s->execute([trim($_POST['response']), $_POST['status']==='Closed'?'Closed':'Open', (int)$_POST['id']]);
    header('Location: support.php');
    exit;
}
require_once '../portal_helpers.php';
$rows=$pdo->query('SELECT t.*,u.username FROM support_tickets t JOIN users u ON u.id=t.user_id ORDER BY t.created_at DESC')->fetchAll();
portal_start('Support Inbox');
?>
<section class="panel">
<?php foreach($rows as $r):
?>
<div class="feed"><b>
<?=h($r['subject'])
?>
 ·
<?=h($r['username'])
?>
</b><p>
<?=h($r['body'])
?>
</p><form method="post">
<?=portal_csrf()
?>
<input type="hidden" name="id" value="
<?=$r['id']
?>
"><label>Response</label><textarea name="response">
<?=h($r['response'])
?>
</textarea><label>Status</label><select name="status"><option>Open</option><option>Closed</option></select><button>Update</button></form></div>
<?php endforeach;
?>
</section>
<?php portal_end();
?>
