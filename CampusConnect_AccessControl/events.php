<?php require_once 'portal_helpers.php';
portal_start('Events & Calendar');
$rows=$pdo->query('SELECT * FROM events ORDER BY event_date')->fetchAll();
?>
<section class="panel">
<?php foreach($rows as $a):
?>
<div class="feed"><h3>
<?=h($a['title'])
?>
</h3><p>
<?=h($a['event_date'])
?>
 ·
<?=h($a['location'])
?>
</p>
<?=h($a['description'])
?>
</div>
<?php endforeach;
?>
<?php if(!$rows):
?>
<p class="muted">No events yet.</p>
<?php endif;
?>
</section>
<?php portal_end();
?>
