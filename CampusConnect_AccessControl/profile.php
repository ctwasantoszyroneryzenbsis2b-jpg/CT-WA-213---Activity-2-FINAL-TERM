<?php
require_once "auth.php";
require_login();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>My Profile | CampusConnect</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="portal">
<?php include "nav.php";
?>
<main class="container">
  <h1>My Profile</h1>
  <section class="panel">
    <p><strong>Username:</strong>
<?= h($_SESSION["username"] ?? "")
?>
</p>
    <p><strong>Role:</strong>
<?= h(ucfirst($_SESSION["role"] ?? "student"))
?>
</p>
  </section>
</main>
</body>
</html>
