<?
require ('login/logi.php');
require('r.php');

session_start();

function renderSignIn () {
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
        </html>
    ';
}

if (isset($_SESSION['auth'])) {
  if ($_SESSION['auth'] == 'admin') {
    echo '<p>Wie soll auf die Seite zugegriffen werden?</p><a href="admin.php">Admin-Modus</a><br><a href="user.php">User-Modus</a>';
  } else {
    header('Location: user.php');
  }
} elseif (isset($_SESSION['iserv']) AND isset($_SESSION['passw'])) {
  $success = iservLogin($_SESSION['iserv'],$_SESSION['passw']);
  if ($success) {   
    if ($_SESSION['auth'] == 'admin') {
      echo '<p>Wie soll auf die Seite zugegriffen werden?</p><a href="admin.php">Admin-Modus</a><br><a href="user.php">User-Modus</a>';
    } else {
      header('Location: user.php');
    }
  }
} elseif (isset($_COOKIE['iserv']) AND isset($_COOKIE['passw'])) {
    $success = iservLogin($_COOKIE['iserv'],$_COOKIE['passw']);
    if ($success) {  
      if ($_SESSION['auth'] == 'admin') {
        echo '<p>Wie soll auf die Seite zugegriffen werden?</p><a href="admin.php">Admin-Modus</a><br><a href="user.php">User-Modus</a>';
      } else {
        header('Location: user.php');
      }
    }
} else {
    renderSignIn();
}
?>
