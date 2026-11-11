<?php require_once '../auth.php';
require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $action=$_POST['action']??'';
    if($action==='add'){
        $v=[trim($_POST['course_code']??''), trim($_POST['title']??''), trim($_POST['description']??''), trim($_POST['instructor']??''), trim($_POST['schedule_day']??''), ($_POST['start_time']??'')?:null, ($_POST['end_time']??'')?:null, trim($_POST['room']??'')];
        $s=$pdo->prepare('INSERT INTO courses(course_code,title,description,instructor,schedule_day,start_time,end_time,room) VALUES(?,?,?,?,?,?,?,?)');
        $s->execute($v);
        $_SESSION['notice']='Courses item added.';
    }
    elseif($action==='delete'){
        $s=$pdo->prepare('DELETE FROM courses WHERE id=?');
        $s->execute([(int)$_POST['id']]);
        $_SESSION['notice']='Item deleted.';
    }
    header('Location: courses.php');
    exit;
}
require_once '../portal_helpers.php';
$rows=$pdo->query('SELECT * FROM courses ORDER BY id DESC')->fetchAll();
portal_start('Manage Courses');
?>
<section class="panel"><h2>Add Course</h2><form method="post">
<?=portal_csrf()
?>
<input type="hidden" name="action" value="add"><label>Course Code</label><input name="course_code" required><label>Title</label><input name="title" required><label>Description</label><textarea name="description" rows="3"></textarea><label>Instructor</label><input name="instructor" required><label>Schedule day</label><select name="schedule_day"><option value="">To be announced</option>
<?php foreach(["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"] as $day):
?>
<option>
<?=h($day)
?>
</option>
<?php endforeach;
?>
</select><label>Start time</label><input type="time" name="start_time"><label>End time</label><input type="time" name="end_time"><label>Room</label><input name="room" placeholder="e.g. Room 204"><button>Publish</button></form></section>
<section class="panel"><h2>Existing Courses</h2>
<?php foreach($rows as $r):
?>
<div class="feed"><b>
<?=h($r['course_code'])
?>
 ·
<?=h($r['title'])
?>
</b><p>
<?=h($r['description']??'')
?>
 ·
<?=h($r['schedule_day']??'TBA')
?>
<?=h($r['start_time']??'')
?>
–
<?=h($r['end_time']??'')
?>
 ·
<?=h($r['room']??'TBA')
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
