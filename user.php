<?php

require ('survey.php');

session_start();

if (!isset($_SESSION['auth'])) {
    echo '403 Access Denied<br><a href="./">Go back</a>';
} elseif ($_SESSION['auth'] == 'admin' OR $_SESSION['auth'] == 'user') {
    echo '<!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="utf-8">
        <title>Login</title>
      </head>
      <body>
        <form action="user.php" method="post">
            <input type="checkbox" name="" placeholder="ISERV-Mail-Adresse" required><br>
            <input type="checkbox" name="passw" placeholder="ISERV-Passwort" required><br>
            <button type="submit" name="vote">Log In</button>
        </form>
      </body>
    </html>';
}