<?php require_once 'portal_helpers.php';
portal_start('Learning Resources');
$rows=$pdo->query('SELECT * FROM resources ORDER BY created_at DESC')->fetchAll();
?>
<section class="panel cards">
<?php foreach($rows as $r):
?>
<article class="card"><h3>
<?=h($r['title'])
?>
</h3><span class="pill">
<?=h($r['category'])
?>
</span><p>
<?=h($r['description'])
?>
</p>
<?php if($r['url']):
?>
<a target="_blank" rel="noopener" href="
<?=h($r['url'])
?>
">Open resource ↗</a>
<?php endif;
?>
</article>
<?php endforeach;
?>
<?php if(!$rows):
?>
<p class="muted">No resources published yet.</p>
<?php endif;
?>
</section>
<?php portal_end();
?>
