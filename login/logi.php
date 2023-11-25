<?php
$succes = require ("aha.php");
echo ($succes);
if(isset($_POST["submit"])) {
    $iserv = $_POST["iserv"];
    $stmt = $conn->prepare("SELECT * FROM suskuks WHERE iserv=:iserv;");
    $stmt->bindParam(":iserv", $iserv);
    $stmt->execute();
    $userExists = $stmt->fetchAll();
    if (count($userExists)==0) {
        echo("Iserv-Mail doesn't exist.");
        header("Location: login.php");
    }
    if($passw==$passwfdb) {
        session_start();
        $_SESSION["username"] = $userExists[0]["user"];
        $_SESSION["permitlvl"] = $userExists[0]["permitlvl"];
        header("Location: /");
    } else {
        header("Location: login.php");
    }
}
?>
