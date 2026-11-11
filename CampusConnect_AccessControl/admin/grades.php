<?php require_once '../auth.php';
require_admin();
if($_SERVER['REQUEST_METHOD']==='POST'){
    verify_csrf();
    $s=$pdo->prepare('INSERT INTO grades(student_id,course_id,assessment,grade,remarks) VALUES(?,?,?,?,?)');
    $s->execute([(int)$_POST['student_id'], (int)$_POST['course_id'], trim($_POST['assessment']), (float)$_POST['grade'], trim($_POST['remarks']??'')]);
    $_SESSION['notice']='Grade posted.';
    header('Location: grades.php');
    exit;
}
require_once '../portal_helpers.php';
$students=$pdo->query("SELECT id,username FROM users WHERE role='student'")->fetchAll();
$courses=$pdo->query('SELECT id,course_code FROM courses')->fetchAll();
portal_start('Manage Grades');
?>
<section class="panel"><form method="post">
<?=portal_csrf()
?>
<label>Student</label><select name="student_id">
<?php foreach($students as $s):
?>
<option value="
<?=$s['id']
?>
">
<?=h($s['username'])
?>
</option>
<?php endforeach;
?>
</select><label>Course</label><select name="course_id">
<?php foreach($courses as $c):
?>
<option value="
<?=$c['id']
?>
">
<?=h($c['course_code'])
?>
</option>
<?php endforeach;
?>
</select><label>Assessment</label><input name="assessment" required><label>Grade</label><input type="number" name="grade" min="0" max="100" step=".01" required><label>Remarks</label><input name="remarks"><button>Post grade</button></form></section>
<?php portal_end();
?>
