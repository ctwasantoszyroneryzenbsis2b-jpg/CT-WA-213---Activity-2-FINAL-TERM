<?php require_once 'portal_helpers.php';
if(($_SESSION['role']??'')==='admin'){
    header('Location: admin/support.php');
    exit;
}
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $s=$pdo->prepare('INSERT INTO support_tickets(user_id,subject,body) VALUES(?,?,?)');
    $s->execute([$_SESSION['user_id'], trim($_POST['subject']), trim($_POST['body'])]);
    $_SESSION['notice']='Support request submitted.';
    header('Location: support.php');
    exit;
}
portal_start('Help & Support');
$s=$pdo->prepare('SELECT * FROM support_tickets WHERE user_id=? ORDER BY created_at DESC');
$s->execute([$_SESSION['user_id']]);
$rows=$s->fetchAll();
?>
<section class="panel"><h2>Submit request</h2><form method="post">
<?=portal_csrf()
?>
<label>Subject</label><input name="subject" required><label>Details</label><textarea name="body" rows="4" required></textarea><button>Submit</button></form></section><section class="panel"><h2>My requests</h2>
<?php foreach($rows as $r):
?>
<div class="feed"><b>
<?=h($r['subject'])
?>
 ·
<?=h($r['status'])
?>
</b><p>
<?=h($r['body'])
?>
</p>
<?php if($r['response']):
?>
<p><b>Response:</b>
<?=h($r['response'])
?>
</p>
<?php endif;
?>
</div>
<?php endforeach;
?>
</section>
<?php portal_end();
?>
