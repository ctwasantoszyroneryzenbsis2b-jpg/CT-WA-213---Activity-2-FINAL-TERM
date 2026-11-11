<?php require_once 'portal_helpers.php';
portal_start('My Classes');
$s=$pdo->prepare('SELECT c.* FROM courses c JOIN enrollments e ON e.course_id=c.id WHERE e.user_id=? ORDER BY c.schedule_day,c.start_time,c.course_code');
$s->execute([$_SESSION['user_id']]);
$rows=$s->fetchAll();
?>
<section class="panel"><h2>My weekly schedule</h2><p class="muted">Your enrolled classes, meeting times, and rooms are listed below.</p>
<?php if($rows):
?>
<div class="table-wrap"><table class="schedule-table"><thead><tr><th>Course</th><th>Day</th><th>Time</th><th>Room</th><th>Instructor</th></tr></thead><tbody>
<?php foreach($rows as $r):
?>
<tr><td><b>
<?=h($r['course_code'])
?>
</b><br>
<?=h($r['title'])
?>
</td><td>
<?=h($r['schedule_day']?:'To be announced')
?>
</td><td>
<?=!empty($r['start_time'])?h(date('g:i A', strtotime($r['start_time']))):'TBA'
?>
<?=!empty($r['end_time'])?' – '.h(date('g:i A', strtotime($r['end_time']))):''
?>
</td><td>
<?=h($r['room']?:'TBA')
?>
</td><td>
<?=h($r['instructor']?:'TBA')
?>
</td></tr>
<?php endforeach;
?>
</tbody></table></div>
<?php else:
?>
<p class="muted">You have not enrolled in any courses yet. Visit Browse Courses to choose your classes.</p><a class="action-link" href="courses.php">Browse available courses</a>
<?php endif;
?>
</section>
<section class="panel"><h2>Enrolled courses (
<?=count($rows)
?>
)</h2>
<?php foreach($rows as $r):
?>
<div class="feed"><b>
<?=h($r['course_code'])
?>
 ·
<?=h($r['title'])
?>
</b><p class="muted">
<?=h($r['description']??'')
?>
 </p></div>
<?php endforeach;
?>
</section>
<?php portal_end();
?>
