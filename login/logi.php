<?php
$succes = require ('aha.php');
echo ($succes);
if(isset($_POST['submit'])) {
    $iserv = $_POST['iserv'];
    $passw = $_POST['passw'];
    if (!iservLogin($iserv, $passw)) {
        echo('Anmeldung fehlgeschlagen. Iserv-Login falsch.<br>');
        echo('<a href="../"');
    } else {
        if($passw==$passwfdb) {
            session_start();
            $_SESSION["username"] = $userExists[0]["user"];
            $_SESSION["permitlvl"] = $userExists[0]["permitlvl"];
            header("Location: /");
        } else {
            header("Location: login.php");
        }
    }
}

function iservLogin($iserv, $passw) {
    $success = true;
    /*
    if (iserv.signOn($iserv, $passw)) {
        $success = True;
        $_SESSION['iserv'] = $iserv;
        $_SESSION['passw'] = $passw;
        setcookie('iserv', $iserv, time() + (86400 * 30), "/");
    } else {
        $success = False;
    }
    */
    return $success;
}

?>
