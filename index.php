<?

if (!$_SESSION['iserv'] AND !$_COOKIE['iserv']) {
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
        <button type="submit" name="submit">Log In</button>
    </form>
  <a href="register.php">Register</a>
  </body>
</html>';
}
?>
