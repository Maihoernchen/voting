<?php

require ('survey.php');

session_start();

$survey = $_GET['survey'];

if (isset($_POST['addVote'])) {
    addVote($survey, $_COOKIE['options']);
}else if (!isset($_SESSION['auth'])) {
    echo '403 Access Denied<br><a href="./">Go back</a>';
} elseif ($_SESSION['auth'] == 'admin' OR $_SESSION['auth'] == 'user') {
    $stmt = $GLOBALS['conn']->prepare('SELECT descr,multiplePossible from '.$survey.' WHERE iserv=0;');
    $stmt->execute();
    $properties = $stmt->fetchAll()[0];
    setcookie('multiplePossible', $properties['multiplePossible']);
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/view.css">
    <title>Umfragen</title>
    </head>
    <header>';
    echo '<h1 id="name">'.$survey.'</h1>';
    echo '<h3 id="descr">'.$properties['descr'].'</h3>';
    echo '</header><main><form action="survey.php?survey='.$survey.'" method="POST">';
    $stmt = $GLOBALS['conn']->prepare('SELECT id,meaning from '.$survey.'_options;');
    $stmt->execute();
    $options = $stmt->fetchAll();
    // shorthand if to set checkbox type
    $type = $properties['multiplePossible'] ? 'check' : 'radio';
    // generate checkboxes with same name for radio buttons and brackets for checkboxes
    foreach ($options as $key=>$option) {
        echo '<label>'.$option['meaning'].'</label><input class="el" name="yes" type=' . $type . ' id='.$option['id'].' value='.$option['id'].'>';
    }
    
    echo '<input type="submit" name="vote" value="Add Vote"></form></main></html>';
}
