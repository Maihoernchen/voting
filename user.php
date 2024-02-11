<?php

require ('survey.php');
require('r.php');

session_start();

if (!isset($_SESSION['auth'])) {
  header("HTTP/1.1 403 Forbidden");
    echo '403 Access Denied<br><a href="./">Go back</a>';
} elseif ($_SESSION['auth'] == 'admin' OR $_SESSION['auth'] == 'user') {
    echo '<!DOCTYPE html>
    <html lang="en">
      <head>
        <meta charset="utf-8">
        <title>Umfragen</title>
        <link rel="stylesheet" href="css/user.css">
        <script src="js/user.js"></script>
      </head>
      <body>';
    $stmt = $GLOBALS['conn']->prepare('SELECT table_name FROM information_schema.tables WHERE table_schema = "maihoernchen_users";');
    $stmt->execute();
    $tables = $stmt->fetchAll();
    $defaults = ['GSV','users','admins'];
    $active = array();
    $passed = array();
    foreach ($tables as $key=>$content) {
      $table = $content['table_name'];
      if (!in_array($table, $defaults) AND !str_contains($table, '_options')) {
        $stmt = $GLOBALS['conn']->prepare('SELECT descr,expirationDate,active FROM '.$table.' WHERE iserv=0;');
        $stmt->execute();
        $properties = $stmt->fetchAll()[0];
        if (time() < strtotime($properties['expirationDate'])) {
          if ($properties['active']) {
            array_push($active, array('name' => $table, 'descr' => $properties['descr']));
          }
        } else {
          $stmt = $GLOBALS['conn']->prepare('UPDATE '.$table.' SET active = false WHERE iserv=0;');
          $stmt->execute();
          if((time()-(60*60*24*30)) < strtotime($properties['expirationDate'])) {
            array_push($passed, array('name' => $table, 'descr' => $properties['descr']));
          }
        }
      }
    }
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
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menü</span>';
    echo '<div id=active><h1>Aktive Umfragen</h1>';
    foreach ($active as $key=>$value) {
      echo '<div class="survey" style="border-style:double"><a href="view.php?survey='.$value['name'].'"><h2>'.$value['name'].'</h2><h4>'.$value['descr'].'</h4></a></div>';
    }
    echo '</div>';
    echo '<div id=passed><h1>Vergangene Umfragen</h1>';
    foreach ($passed as $key=>$value) {
      echo '<div class="survey" style="border-style:double"><a href="view.php?survey='.$value['name'].'"><h2>'.$value['name'].'</h2><h4>'.$value['descr'].'</h4></a></div>';
    }
    echo '</div>';
    echo '
    </div>
    </body>
    </html>';
}