<?php
require_once "config.php";
$msg="";
$exists=(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn()>0;
if(!$exists&&$_SERVER["REQUEST_METHOD"]==="POST"){
    $u=trim($_POST["username"]??"");
    $p=$_POST["password"]??"";
    if(strlen($u)<3||strlen($p)<8)$msg="Username must be 3+ characters and password 8+ characters.";
    else{
        $s=$pdo->prepare("INSERT INTO users(username,password,role) VALUES(?,?,'admin')");
        $s->execute([$u, password_hash($p, PASSWORD_DEFAULT)]);
        $msg="Admin created. Delete setup_admin.php now.";
        $exists=true;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Setup</title><link rel="stylesheet" href="style.css"></head><body><main class="auth-card"><h1>CampusConnect</h1><h2>Administrator Setup</h2>
<?php if($msg):
?>
<p class="success">
<?=htmlspecialchars($msg)
?>
</p>
<?php endif;
?>
<?php if($exists):
?>
<p>Setup complete. Delete this file for security.</p><a href="index.php">Sign in</a>
<?php else:
?>
<form method="post"><label>Admin username</label><input name="username" value="admin" required><label>Admin password (8+ characters)</label><input type="password" name="password" minlength="8" required><button class="full">Create Admin</button></form>
<?php endif;
?>
</main></body></html>
