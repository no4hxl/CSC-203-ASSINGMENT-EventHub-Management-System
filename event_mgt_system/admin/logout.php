<?php
/**
 * Logout Script
 * Clears all session data and cookies for a clean exit.
 */
session_start();

// Clear the session array
$_SESSION = array();

// Destroy the session cookie in the browser
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy the server-side session
session_destroy();

// Redirect to login
header("Location: login.php");
exit();
?>