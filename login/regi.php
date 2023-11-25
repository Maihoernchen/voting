<?php
require ("aha.php");
if (isset($_POST["submit"])) {
    $iserv = $_POST["iserv"];
    $role = $_POST["role"];
    if (!str_ends_with($iserv, '@berlin.gcm.schule')) {
        echo('Keine valide Iserv-Mailadresse. Bitte geben Sie Ihre Iserv-Adresse an.<br>');
        echo('<a href="./register.php">Go Back</a>');
        exit(401);
    }
    $stmt = $conn->prepare("SELECT * FROM suskuks WHERE iserv=:iserv");
    $stmt->bindParam(":iserv", $iserv);
    $stmt->execute();
    $userexists = $stmt->fetchColumn();
    if (!$userexists) {
        registerUser($iserv, $role);
    }
    else {
        echo('User wurde bereits registriert. Um einen neue Registrierung anzufordern, bestätige bitte die Email, die wir an '.$iserv.' gesendet haben.');
    }
}
function randHash($len=32)
{
	return substr(md5(openssl_random_pseudo_bytes(20)),-$len);
}
function registerUser($iserv, $role){
     mail
    (
        'nachtara07@gmail.com',
        'Bestätigung Ihrer Email.',
        'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    );  
    global $conn;
    $stmt = $conn->prepare("INSERT INTO suskuks(iserv,verification,role) VALUES(:iserv, :verification,:role)");
    $stmt->bindParam(":iserv", $iserv);
    $stmt->bindParam(":verification", randHash());
    $stmt->bindParam(":role", $role);
    $stmt->execute();
    header("Location: ./login.php");
}
?>
