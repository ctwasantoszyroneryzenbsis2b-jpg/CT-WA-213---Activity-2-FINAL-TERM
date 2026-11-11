<?php require_once 'portal_helpers.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $to=(int)($_POST['to']??0);
    $body=trim($_POST['body']??'');
    if($to&&$body){
        $s=$pdo->prepare('INSERT INTO messages(sender_id,recipient_id,body) VALUES(?,?,?)');
        $s->execute([$_SESSION['user_id'], $to, $body]);
        $_SESSION['notice']='Message sent.';
    }
    header('Location: messages.php');
    exit;
}
portal_start('Messages');
$u=$pdo->prepare('SELECT id,username,role FROM users WHERE id<>?');
$u->execute([$_SESSION['user_id']]);
$users=$u->fetchAll();
$s=$pdo->prepare('SELECT m.*,a.username sender,b.username recipient FROM messages m JOIN users a ON a.id=m.sender_id JOIN users b ON b.id=m.recipient_id WHERE sender_id=? OR recipient_id=? ORDER BY created_at DESC');
$s->execute([$_SESSION['user_id'], $_SESSION['user_id']]);
$msgs=$s->fetchAll();
?>
<section class="panel"><h2>Send message</h2><form method="post">
<?=portal_csrf()
?>
<label>Recipient</label><select name="to" required>
<?php foreach($users as $u):
?>
<option value="
<?=$u['id']
?>
">
<?=h($u['username'].' ('.$u['role'].')')
?>
</option>
<?php endforeach;
?>
</select><label>Message</label><textarea name="body" rows="4" required></textarea><button>Send</button></form></section><section class="panel"><h2>Recent messages</h2>
<?php foreach($msgs as $m):
?>
<div class="feed"><b>
<?=h($m['sender'])
?>
 →
<?=h($m['recipient'])
?>
</b><p>
<?=nl2br(h($m['body']))
?>
</p><small>
<?=h($m['created_at'])
?>
</small></div>
<?php endforeach;
?>
</section>
<?php portal_end();
?>
