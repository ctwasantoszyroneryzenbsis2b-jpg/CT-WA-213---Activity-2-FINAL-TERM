<?php
require_once 'portal_helpers.php';
require_login();
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $courseId=(int)($_POST['course_id']??0);
    $action=$_POST['action']??'';
    if($courseId>0 && $action==='enroll'){
        $check=$pdo->prepare('SELECT id FROM courses WHERE id=?');
        $check->execute([$courseId]);
        if($check->fetch()){
            $s=$pdo->prepare('INSERT IGNORE INTO enrollments(user_id,course_id) VALUES(?,?)');
            $s->execute([$_SESSION['user_id'], $courseId]);
            $_SESSION['notice']='Course added to My Classes.';
        }
    }
    elseif($courseId>0 && $action==='withdraw'){
        $s=$pdo->prepare('DELETE FROM enrollments WHERE user_id=? AND course_id=?');
        $s->execute([$_SESSION['user_id'], $courseId]);
        $_SESSION['notice']='You have been withdrawn from the course.';
    }
    header('Location: courses.php');
    exit;
}
portal_start('Browse Courses');
$sql='SELECT c.*, EXISTS(SELECT 1 FROM enrollments e WHERE e.course_id=c.id AND e.user_id=?) AS enrolled FROM courses c ORDER BY c.course_code';
$s=$pdo->prepare($sql);
$s->execute([$_SESSION['user_id']]);
$rows=$s->fetchAll();
?>
<section class="panel"><h2>Choose your courses</h2><p class="muted">Browse available courses and select the ones you want to attend. Your selections will appear in My Classes with their schedules.</p></section>
<div class="course-grid">
<?php foreach($rows as $r):
?>
<article class="course-card"><span class="pill">
<?=h($r['course_code'])
?>
</span><h3>
<?=h($r['title'])
?>
</h3><p>
<?=h($r['description']??'Course description will be announced.')
?>
</p><div class="course-meta"><span><b>Instructor:</b>
<?=h($r['instructor']?:'To be announced')
?>
</span><span><b>Schedule:</b>
<?=h($r['schedule_day']??'To be announced')
?>
<?=!empty($r['start_time'])?' · '.h(date('g:i A', strtotime($r['start_time']))):''
?>
<?=!empty($r['end_time'])?'–'.h(date('g:i A', strtotime($r['end_time']))):''
?>
</span><span><b>Room:</b>
<?=h($r['room']??'To be announced')
?>
</span></div>
<?php if($r['enrolled']):
?>
<span class="enrolled-tag">✓ Enrolled</span><form method="post">
<?=portal_csrf()
?>
<input type="hidden" name="action" value="withdraw"><input type="hidden" name="course_id" value="
<?=$r['id']
?>
"><button class="danger" type="submit">Withdraw</button></form>
<?php else:
?>
<form method="post">
<?=portal_csrf()
?>
<input type="hidden" name="action" value="enroll"><input type="hidden" name="course_id" value="
<?=$r['id']
?>
"><button type="submit">Enroll in Course</button></form>
<?php endif;
?>
</article>
<?php endforeach;
?>
</div>
<?php if(!$rows):
?>
<section class="panel"><p class="muted">No courses are available yet. Please check again later.</p></section>
<?php endif;
?>
<?php portal_end();
?>
