<?php $isAdmin=(($_SESSION['role']??'student')==='admin');
$base=strpos($_SERVER['PHP_SELF'], '/admin/')!==false?'../':'';
?>
<nav class="main-nav"><a class="nav-brand" href="
<?= $base
?>
dashboard.php">✦ CampusConnect</a><div class="nav-links">
<a href="
<?= $base
?>
dashboard.php">Dashboard</a>
<?php if($isAdmin):
?>
<a href="
<?= $base
?>
admin/courses.php">Courses</a><a href="
<?= $base
?>
admin/announcements.php">Announcements</a><a href="
<?= $base
?>
admin/events.php">Events</a><a href="
<?= $base
?>
admin/resources.php">Resources</a><a href="
<?= $base
?>
admin/grades.php">Grades</a><a href="
<?= $base
?>
admin/users.php">User Management</a><a href="
<?= $base
?>
admin/support.php">Support Inbox</a>
<?php else:
?>
<a href="
<?= $base
?>
courses.php">Courses</a><a href="
<?= $base
?>
my_classes.php">My Classes</a><a href="
<?= $base
?>
grades.php">Grades</a><a href="
<?= $base
?>
announcements.php">Announcements</a><a href="
<?= $base
?>
events.php">Events</a><a href="
<?= $base
?>
resources.php">Learning Resources</a><a href="
<?= $base
?>
support.php">Help & Support</a>
<?php endif;
?>
<a href="
<?= $base
?>
messages.php">Messages</a><a href="
<?= $base
?>
profile.php">My Profile</a><a class="logout-link" href="
<?= $base
?>
logout.php">Logout</a></div></nav>
