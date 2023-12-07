<?php

require ('survey.php');

session_start();

if (!isset($_SESSION['auth'])) {
    echo '403 Access Denied<br><a href="./">Go back</a>';
} else {
    if ($_SESSION['auth'] == 'admin') {
        echo '<!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="utf-8">
            <title>Login</title>
            <script type="text/javascript" src="js/admin.js"></script>
          </head>
          <body>
          </body>
        </html>';
    } else {
        echo '403 Access Denied<br><a href="./">Go back</a>';
    }
}