<?php require_once 'portal_helpers.php';
portal_start('Announcements');
$rows=$pdo->query('SELECT * FROM announcements ORDER BY created_at DESC')->fetchAll();
?>
<section class="panel">
<?php foreach($rows as $a):
?>
<div class="feed"><h3>
<?=h($a['title'])
?>
</h3><p>
<?=nl2br(h($a['body']))
?>
</p><small>
<?=h($a['created_at'])
?>
</small></div>
<?php endforeach;
?>
<?php if(!$rows):
?>
<p class="muted">No announcements yet.</p>
<?php endif;
?>
</section>
<?php portal_end();
?>
