<?php

require ('survey.php');
require('r.php');

session_start();

if (!isset($_SESSION['auth'])) {
    header("HTTP/1.1 403 Forbidden");
    echo '403 Access Denied<br><a href="./">Go back</a>';
} else {
    if ($_SESSION['auth'] == 'admin') {
        echo '<!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="utf-8">
            <title>Login</title>
            <script type="text/javascript" src="js/admin.js"></script>
            <link rel="stylesheet" href="css/user.css">
            <script src="js/user.js"></script>
          </head>
          <body>';
        echo '<div id=main>
          <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="user.php">Startseite</a>
        <a href="documentation.php">Über dieses Programm</a>
        <a href="archive.php">Archiv</a>
        ';
    if (isset($_SESSION['auth']) AND $_SESSION['auth']=='admin') {
      echo '<a href="index.php">Mode Select</a>';
    }
    echo '<a href="logout.php">Logout</a>
    </div>
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menü</span></body></html>';
    } else {
        header("HTTP/1.1 403 Forbidden");
        echo '403 Access Denied<br><a href="./">Go back</a>';
    }
}