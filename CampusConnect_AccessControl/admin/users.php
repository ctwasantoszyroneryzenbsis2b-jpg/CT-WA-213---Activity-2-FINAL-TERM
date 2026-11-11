<?php
require_once dirname(__DIR__)."/auth.php";
require_admin();
$error="";
$message="";
if($_SERVER["REQUEST_METHOD"]==="POST"){
    verify_csrf();
    $action=$_POST["action"]??"";
    $id=(int)($_POST["id"]??0);
    if($action==="create"){
        $u=trim($_POST["username"]??"");
        $p=$_POST["password"]??"";
        $r=$_POST["role"]??"student";
        if(strlen($u)<3||strlen($p)<8||!in_array($r, ["admin", "student"], true))$error="Check username, password, and role.";
        else try{
            $s=$pdo->prepare("INSERT INTO users(username,password,role) VALUES(?,?,?)");
            $s->execute([$u, password_hash($p, PASSWORD_DEFAULT), $r]);
            $message="User created.";
        }
        catch(PDOException $e){
            $error="Username already exists.";
        }
    }
    elseif($action==="update"){
        $u=trim($_POST["username"]??"");
        $r=$_POST["role"]??"student";
        $p=$_POST["password"]??"";
        if($id<1||strlen($u)<3||!in_array($r, ["admin", "student"], true))$error="Invalid user details.";
        elseif($id===(int)$_SESSION["user_id"]&&$r!=="admin")$error="You cannot remove your own admin role.";
        else try{
            if($p!==""){
                if(strlen($p)<8)throw new RuntimeException("Password must be at least 8 characters.");
                $s=$pdo->prepare("UPDATE users SET username=?,role=?,password=? WHERE id=?");
                $s->execute([$u, $r, password_hash($p, PASSWORD_DEFAULT), $id]);
            }
            else{
                $s=$pdo->prepare("UPDATE users SET username=?,role=? WHERE id=?");
                $s->execute([$u, $r, $id]);
            }
            if($id===(int)$_SESSION["user_id"])$_SESSION["username"]=$u;
            $message="User updated.";
        }
        catch(RuntimeException $e){
            $error=$e->getMessage();
        }
        catch(PDOException $e){
            $error="Username already exists or update failed.";
        }
    }
    elseif($action==="delete"){
        if($id===(int)$_SESSION["user_id"])$error="You cannot delete your own account.";
        else{
            $s=$pdo->prepare("SELECT role FROM users WHERE id=?");
            $s->execute([$id]);
            $target=$s->fetch();
            if($target&&$target["role"]==="admin"&&(int)$pdo->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn()<=1)$error="Cannot delete the last administrator.";
            if(!$error){
                $s=$pdo->prepare("DELETE FROM users WHERE id=?");
                $s->execute([$id]);
                $message="User deleted.";
            }
        }
    }
}
$q=trim($_GET["q"]??"");
if($q!==""){
    $s=$pdo->prepare("SELECT id,username,role,created_at FROM users WHERE username LIKE ? ORDER BY id DESC");
    $s->execute(["%$q%"]);
    $users=$s->fetchAll();
}
else{
    $users=$pdo->query("SELECT id,username,role,created_at FROM users ORDER BY id DESC")->fetchAll();
}
?>
<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>User Management</title><link rel="stylesheet" href="../style.css"></head><body class="portal">
<?php include "../nav.php";
?>
<main class="container"><h1>User Management</h1><p class="muted">Create, view, update, search, and delete accounts.</p>
<?php if($error):
?>
<p class="alert">
<?=h($error)
?>
</p>
<?php endif;
?>
<?php if($message):
?>
<p class="success">
<?=h($message)
?>
</p>
<?php endif;
?>
<section class="panel"><h2>Add User</h2><form method="post" class="inline-form"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="create"><input name="username" placeholder="Username" minlength="3" required><input type="password" name="password" placeholder="Password (8+)" minlength="8" required><select name="role"><option value="student">Student</option><option value="admin">Admin</option></select><button>Create User</button></form></section>
<section class="panel"><h2>Registered Users</h2><form method="get" class="search-form"><input name="q" value="
<?=h($q)
?>
" placeholder="Search username"><button>Search</button><a href="users.php">Clear</a></form><div class="table-wrap"><table><thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Created</th><th>Update</th><th>Delete</th></tr></thead><tbody>
<?php foreach($users as $u):
?>
<tr><td>
<?=h($u["id"])
?>
</td><td>
<?=h($u["username"])
?>
</td><td>
<?=h($u["role"])
?>
</td><td>
<?=h($u["created_at"])
?>
</td><td><form method="post" class="edit-form"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="update"><input type="hidden" name="id" value="
<?=h($u["id"])
?>
"><input name="username" value="
<?=h($u["username"])
?>
" required><select name="role"><option value="student"
<?=$u["role"]==="student"?"selected":""
?>
>Student</option><option value="admin"
<?=$u["role"]==="admin"?"selected":""
?>
>Admin</option></select><input type="password" name="password" placeholder="New password (optional)"><button class="small">Save</button></form></td><td><form method="post" onsubmit="return confirm('Delete this user?');"><input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="
<?=h($u["id"])
?>
"><button class="danger small">Delete</button></form></td></tr>
<?php endforeach;
?>
<?php if(!$users):
?>
<tr><td colspan="6">No users found.</td></tr>
<?php endif;
?>
</tbody></table></div></section></main></body></html>
