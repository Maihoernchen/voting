<?php

require ('login/aha.php');
require('r.php');

session_start();

if(isset($_POST['create'])) {
    $survey = json_decode($_COOKIE['survey'], true);
    if (isset($survey['1']) AND isset($survey['name']) AND isset($survey['descr'])) {
        $survey = json_decode($_COOKIE['survey'], true);
        setcookie("survey", "", time()-3600);
        createSurvey ($survey);
        header('Location: admin.php');
    } else {
        echo 'Attribut fehlt. <br> <a href="admin.php">zurück</a>';
    }
} elseif (isset($_POST['vote'])) {
    addVote($_GET['survey'],$_POST);
}


function createSurvey ($survey) {
    $options = array();
    foreach ($survey as $key=>$value) {
        if (is_numeric($key)) {
            $options[$key] = $value;
        }
    }
    $name = $survey['name'];
    $descr = $survey['descr'];
    $expirationDate = $survey['expirationDate'];
    $active = true;
    $multiplePossible = $survey['multiplePossible'];
    $optionsNumber  = count($options);
    createVoted($name, $optionsNumber, $descr, $multiplePossible, $expirationDate, $active);
    createOptions($name, $options);
}



function createVoted ($name, $optionsNumber, $descr, $multiplePossible, $expirationDate, $active) {
    $stmt = $GLOBALS['conn']->prepare('CREATE TABLE '.$name.' (iserv VARCHAR(255) PRIMARY KEY, optionsNumber INT(8), multiplePossible BOOLEAN, descr VARCHAR(255), expirationDate DATETIME, active BOOLEAN);');
    $stmt->execute();
    $stmt = $GLOBALS['conn']->prepare('INSERT INTO '.$name.' (iserv, optionsNumber, multiplePossible, descr, expirationDate, active) VALUES (0, :optionsNumber, :multiplePossible, :descr, :expirationDate, :active);');
    $stmt->bindParam(':optionsNumber', $optionsNumber);
    $stmt->bindParam(':multiplePossible', $multiplePossible);
    $stmt->bindParam(':descr', $descr);
    $stmt->bindParam(':expirationDate', $expirationDate);
    $stmt->bindParam(':active', $active);
    $stmt->execute();
}

function createOptions ($name, $options) {
    $stmt = $GLOBALS['conn']->prepare('CREATE TABLE '.$name.'_options (id INT(8) PRIMARY KEY, votes INT(20), meaning VARCHAR(255));');
    $stmt->execute();
    $query = ('INSERT INTO '.$name.'_options (id, votes, meaning) VALUES ');
    $lastElement = array_key_last($options);
    foreach ($options as $id=>$meaning) {
        if ($id == $lastElement) {
            $query .= '("'.$id.'", "0", "'.$meaning.'")';
        } else {
            $query .= '("'.$id.'", "0", "'.$meaning.'"),';
        }
    }
    $query .= ';';
    $stmt = $GLOBALS['conn']->prepare($query);
    $stmt->execute();

}

function addVote($name, $option) {
    $stmt = $GLOBALS['conn']->prepare('SELECT * FROM '.$name.' WHERE iserv=:iserv');
    $stmt->bindParam(':iserv', $_SESSION['iserv']);
    $stmt->execute();
    $av = $stmt->fetchAll();
    $stmt = $GLOBALS['conn']->prepare('SELECT expirationDate FROM '.$name.' WHERE iserv=0');
    $stmt->execute();
    $eD = $stmt->fetchAll()[0];
    $stmt = $GLOBALS['conn']->prepare('SELECT multiplePossible FROM '.$name.' WHERE iserv=0');
    $stmt->execute();
    $mP = $stmt->fetchAll();
    unset($option['vote']);
    if ($av) {
        header("HTTP/1.1 405 Method Not Allowed");
        echo 'You already voted. <br> <a href="user.php">Go back.</a>';
    } elseif (time() > $eD) {
        header("HTTP/1.1 410 Gone");
        echo 'Die Umfrage ist vergangen und nimmt keine Stimmen mehr an. <a href="user.php">Go Back</a>';
    } elseif ($mP AND count($option)>1) {
        header("HTTP/1.1 400 Bad Request");
        echo 'Sie haben mehrere Stimmen abgegeben, aber die Umfrage nimmt maximal eine an. <a href="user.php">Go Back</a>';
    } else {
        $stmt = $GLOBALS['conn']->prepare('INSERT INTO '.$name.' (iserv) VALUES (:iserv)');
        $stmt->bindParam(':iserv', $_SESSION['iserv']);
        $stmt->execute();
        foreach ($option as $key=>$value) {
            $stmt = $GLOBALS['conn']->prepare('UPDATE '.$name.'_options SET votes = votes + 1 WHERE id=:id');
            $stmt->bindParam(':id', $value);
            $stmt->execute();
        }
        echo 'Stimme hinzugefügt. Möchten Sie noch Feedback geben, oder einen Vorschlag machen? <br>
        <form action="survey.php">
        <input type="text" id="feedback"> <br> 
        <input type="submit" name="noFeedback" value="Feedback überspringen"><input type="submit" name="Feedback" value="Feedback abschicken">
        </form>';
    }
}