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
    // if (iserv.signOn($iserv, $passw)) {
    if (true) {
        $success = True;
        session_start();
        $_SESSION['auth'] = adminLogin($iserv);
        $_SESSION['iserv'] = $iserv;
        $_SESSION['passw'] = $passw;
        setcookie('iserv', $iserv, time() + (86400 * 30), "/");
        setcookie('passw', $passw, time() + (86400 * 30), "/");
    } else {
        $success = False;
    }
    return $success;
}

function adminLogin($iserv) {
    $stmt = $GLOBALS['conn']->prepare('SELECT * FROM admins WHERE iserv=:iserv');
    $stmt->bindParam(':iserv', $iserv);
    $stmt->execute();
    $acc = $stmt->fetchAll();
    if ($acc) {
        return 'admin';
    } else {
        return 'user';
    }
}

?>
