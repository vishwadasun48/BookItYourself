<?php
// Session එක ආරම්භ කිරීම
session_start();

// Session data සියල්ල හිස් කිරීම (Unset all session variables)
$_SESSION = array();

// Session Cookie එකක් පවතී නම් එයද මකා දැමීම (Delete session cookie)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Session එක සම්පූර්ණයෙන්ම විනාශ කිරීම (Destroy the session)
session_destroy();

// Login පිටුවට Redirect කිරීම
header("Location: login.php");
exit();
?>