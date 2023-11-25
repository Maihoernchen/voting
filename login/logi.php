<?php
require ('aha.php');
if(isset($_POST['submit'])) {
    $iserv = $_POST['iserv'];
    $passw = $_POST['passw'];
    if (!iservLogin($iserv, $passw)) {
        echo('Anmeldung fehlgeschlagen. Iserv-Login falsch.<br>');
        echo('<a href="../">Back</a>');
    } else {
        header('Location: ../');
    }
}

function iservLogin($iserv, $passw) {
    $success = true;
    /*
    if (iserv.signOn($iserv, $passw)) {
        $success = True;
        session_start();
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
