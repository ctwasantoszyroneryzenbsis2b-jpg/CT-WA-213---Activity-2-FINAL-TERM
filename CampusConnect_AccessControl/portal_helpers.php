<?php
require_once __DIR__.'/auth.php';
function portal_start($title) {
    require_login();

    $cssPath = (strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false)
        ? '../style.css'
        : 'style.css';

    echo '<!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>' . h($title) . ' | CampusConnect</title>
        <link rel="stylesheet" href="' . $cssPath . '">
    </head>
    <body class="portal">';

    include __DIR__ . '/nav.php';

    echo '<main class="container">
        <h1>' . h($title) . '</h1>';

    if (!empty($_SESSION['notice'])) {
        echo '<p class="success">' . h($_SESSION['notice']) . '</p>';
        unset($_SESSION['notice']);
    }
}
function portal_end(){
    echo '</main><footer class="footer">© '.date('Y').' CampusConnect </footer></body></html>';
}
function portal_csrf(){
    return '<input type="hidden" name="csrf_token" value="'.h(csrf_token()).'">';
}
