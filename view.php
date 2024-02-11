<?php

require ('survey.php');
require('r.php');

session_start();

$survey = $_GET['survey'];

if (isset($_POST['addVote'])) {
    addVote($survey, $_COOKIE['options']);
}else if (!isset($_SESSION['auth'])) {
    header("HTTP/1.1 403 Forbidden");
    echo '403 Access Denied<br><a href="./">Go back</a>';
} elseif ($_SESSION['auth'] == 'admin' OR $_SESSION['auth'] == 'user') {
    $stmt = $GLOBALS['conn']->prepare('SELECT descr,multiplePossible,expirationDate from '.$survey.' WHERE iserv=0;');
    $stmt->execute();
    $properties = $stmt->fetchAll()[0];
    setcookie('multiplePossible', $properties['multiplePossible']);
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/view.css">
    <link rel="stylesheet" href="css/user.css">
        <script src="js/user.js"></script>
    <title>Umfragen</title>
    </head>';
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
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menü</span>
    <header>';
    echo '<h1 id="name">'.$survey.'</h1>';
    echo '<h3 id="descr">'.$properties['descr'].'</h3>';
    echo '</header><main>';
    if (time() < strtotime($properties['expirationDate'])){
        echo' <form action="survey.php?survey='.$survey.'" method="POST">';
        $stmt = $GLOBALS['conn']->prepare('SELECT id,meaning from '.$survey.'_options;');
        $stmt->execute();
        $options = $stmt->fetchAll();
        if ($properties['multiplePossible']) {
            foreach ($options as $key=>$option) {
                echo '<label>'.$option['meaning'].'</label><input class="el" name='.$option['id'].' type=checkbox id='.$option['id'].' value='.$option['id'].'>';
            }
        } else {
            foreach ($options as $key=>$option) {
                echo '<label>'.$option['meaning'].'</label><input class="el" name="yes" type=radio id='.$option['id'].' value='.$option['id'].'>';
            }
        }
        
        echo '<input type="submit" name="vote" value="Add Vote"></form></main></html>';
    } else {
        echo 'passed umfrage';
    }
}