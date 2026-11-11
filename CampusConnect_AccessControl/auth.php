<?php
if(session_status()===PHP_SESSION_NONE) session_start();
require_once __DIR__."/config.php";
function require_login(){
    if(empty($_SESSION["user_id"])){
        header("Location: index.php");
        exit;
    }
}
function require_admin(){
    require_login();
    if(($_SESSION["role"]??"")!=="admin"){
        http_response_code(403);
        exit("Access denied. Admin permission required.");
    }
}
function csrf_token(){
    if(empty($_SESSION["csrf_token"]))$_SESSION["csrf_token"]=bin2hex(random_bytes(32));
    return $_SESSION["csrf_token"];
}
function verify_csrf(){
    if($_SERVER["REQUEST_METHOD"]==="POST"&&!hash_equals($_SESSION["csrf_token"]??"", $_POST["csrf_token"]??"")){
        http_response_code(400);
        exit("Invalid request token.");
    }
}
function h($v){
    return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8");
}
