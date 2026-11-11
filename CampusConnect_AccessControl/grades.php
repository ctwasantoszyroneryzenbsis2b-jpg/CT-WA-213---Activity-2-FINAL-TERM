<?php require_once 'portal_helpers.php';
if(($_SESSION['role']??'')==='admin'){
    header('Location: admin/grades.php');
    exit;
}
portal_start('My Grades');
$s=$pdo->prepare('SELECT g.*,c.course_code,c.title FROM grades g JOIN courses c ON c.id=g.course_id WHERE g.student_id=?');
$s->execute([$_SESSION['user_id']]);
$rows=$s->fetchAll();
?>
<section class="panel table-wrap"><table><tr><th>Course</th><th>Assessment</th><th>Grade</th><th>Remarks</th></tr>
<?php foreach($rows as $r):
?>
<tr><td>
<?=h($r['course_code'].' '.$r['title'])
?>
</td><td>
<?=h($r['assessment'])
?>
</td><td>
<?=h($r['grade'])
?>
%</td><td>
<?=h($r['remarks'])
?>
</td></tr>
<?php endforeach;
?>
</table>
<?php if(!$rows):
?>
<p class="muted">No grades have been posted yet.</p>
<?php endif;
?>
</section>
<?php portal_end();
?>
