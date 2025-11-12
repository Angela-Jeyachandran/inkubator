<?php
// Keeps session consistent across the pages 
ini_set('session.cookie_path', '/');
session_name('inkubator_session');
session_start();

if (isset($_SESSION['username'])) {
    $_SESSION = [];

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();
}

header('Location: index.html');
exit;
?>
