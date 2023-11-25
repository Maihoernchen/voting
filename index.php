<?

if (!isset($_SESSION['iserv']) AND !isset($_COOKIE['iserv'])) {
echo '
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
  </head>
  <body>
    <form action="login/logi.php" method="post">
        <input type="text" name="iserv" placeholder="ISERV-Mail-Adresse" required><br>
        <input type="password" name="passw" placeholder="ISERV-Passwort" required><br>
        <button type="submit" name="submit">Log In</button>
    </form>
  </body>
</html>';
} elseif (isset($_SESSION['iserv'])) {
  echo 'Welcome '.$_SESSION['iserv'];
} else {
  echo 'Welcome '.$_COOKIE['iserv'];
}
?>
