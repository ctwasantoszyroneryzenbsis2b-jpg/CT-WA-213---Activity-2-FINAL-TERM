<?php
require_once "auth.php";
$error="";
$success="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    verify_csrf();
    $u=trim($_POST["username"]??"");
    $p=$_POST["password"]??"";
    if(strlen($u)<3)$error="Username must be at least 3 characters.";
    elseif(strlen($p)<8)$error="Password must be at least 8 characters.";
    else{
        try{
            $s=$pdo->prepare("INSERT INTO users(username,password,role) VALUES(?,?,'student')");
            $s->execute([$u, password_hash($p, PASSWORD_DEFAULT)]);
            $success="Registration successful. You can now sign in.";
        }
        catch(PDOException $e){
            $error="Username already taken.";
        }
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Register | CampusConnect</title><link rel="stylesheet" href="style.css"></head><body><main class="auth-card"><h1>CampusConnect</h1><h2>Create Account</h2>
<?php if($error):
?>
<p class="alert">
<?=h($error)
?>
</p>
<?php endif;
?>
<?php if($success):
?>
<p class="success">
<?=h($success)
?>
</p>
<?php endif;
?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><label>Username</label><input name="username" minlength="3" required><label>Password</label><input type="password" name="password" minlength="8" required><button class="full">Register</button></form><p><a href="index.php">Back to Sign In</a></p></main></body></html>
