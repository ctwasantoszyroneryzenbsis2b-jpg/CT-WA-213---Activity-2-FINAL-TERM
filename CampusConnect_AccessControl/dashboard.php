<?php require_once 'portal_helpers.php';
portal_start('Dashboard');
$course=(int)$pdo->query('SELECT COUNT(*) FROM courses')->fetchColumn();
$users=(int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$en=$pdo->prepare('SELECT COUNT(*) FROM enrollments WHERE user_id=?');
$en->execute([$_SESSION['user_id']]);
$isAdmin=($_SESSION['role']==='admin');
?>
<section class="panel"><p class="eyebrow purple">
<?=$isAdmin?'CAMPUS ADMINISTRATION':'STUDENT PORTAL'
?>
</p><h2>Welcome,
<?=h($_SESSION['username'])
?>
!</h2><p class="muted">Your campus information and services in one place.</p></section>
<div class="gridstats"><section class="panel"><span>Courses</span><br><strong>
<?=$course
?>
</strong></section><section class="panel"><span>
<?=$isAdmin?'Users':'My classes'
?>
</span><br><strong>
<?=$isAdmin?$users:(int)$en->fetchColumn()
?>
</strong></section><section class="panel"><span>Role</span><br><strong>
<?=h(ucfirst($_SESSION['role']))
?>
</strong></section></div>
<section class="panel"><h2>Quick access</h2><p class="muted">Jump directly to the services you use most.</p><div class="actions">
<?php if($isAdmin):
?>
<a href="admin/courses.php">Manage Courses</a><a href="admin/announcements.php">Announcements</a><a href="admin/events.php">Events</a><a href="admin/resources.php">Resources</a><a href="admin/grades.php">Manage Grades</a><a href="admin/users.php">User Management</a><a href="admin/support.php">Support Inbox</a>
<?php else:
?>
<a href="courses.php">Browse Courses</a><a href="my_classes.php">My Classes & Schedule</a><a href="grades.php">My Grades</a><a href="announcements.php">Announcements</a><a href="events.php">Events</a><a href="resources.php">Learning Resources</a><a href="messages.php">Messages</a><a href="support.php">Help & Support</a>
<?php endif;
?>
</div></section>
<?php portal_end();
?>
