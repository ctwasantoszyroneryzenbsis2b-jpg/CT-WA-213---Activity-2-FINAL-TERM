<?php
require_once "auth.php";
if(!empty($_SESSION["user_id"])){
    header("Location: dashboard.php");
    exit;
}
$error="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    verify_csrf();
    $s=$pdo->prepare("SELECT id,username,password,role FROM users WHERE username=?");
    $s->execute([trim($_POST["username"]??"")]);
    $u=$s->fetch();
    if($u&&password_verify($_POST["password"]??"", $u["password"])){
        session_regenerate_id(true);
        $_SESSION["user_id"]=$u["id"];
        $_SESSION["username"]=$u["username"];
        $_SESSION["role"]=$u["role"];
        header("Location: dashboard.php");
        exit;
    }
    $error="Incorrect username or password.";
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CampusConnect Login</title><link rel="stylesheet" href="style.css"></head><body><main class="auth-card"><h1>CampusConnect</h1><h2>Sign In</h2>
<?php if($error):
?>
<p class="alert">
<?=h($error)
?>
</p>
<?php endif;
?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><label>Username</label><input name="username" required><label>Password</label><input type="password" name="password" required><button class="full">Sign In</button></form><p>No account? <a href="register.php">Register here</a></p></main></body></html>
