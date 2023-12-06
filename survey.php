<?php

require ('login/aha.php');

if(isset($_POST['create'])) {
    $survey = json_decode($_COOKIE['survey'], true);
    if (isset($survey['1']) AND isset($survey['name']) AND isset($survey['descr'])) {
        $survey = json_decode($_COOKIE['survey'], true);
        setcookie("survey", "", time()-3600);
        createSurvey ($survey);
        header('Location: admin.php');
    } else {
        echo 'keine optionen angegeben <br> <a href="admin.php">zurück</a>';
    }
} elseif (isset($_POST['vote'])) {
    addVote('Moin','Maista');
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
    $multiplePossible = $survey['multiplePossible'];
    $optionsNumber  = count($options);
    createVoted($name, $optionsNumber, $descr, $multiplePossible);
    createOptions($name, $options);
}



function createVoted ($name, $optionsNumber, $descr, $multiplePossible) {
    $stmt = $GLOBALS['conn']->prepare('CREATE TABLE '.$name.' (iserv VARCHAR(255) PRIMARY KEY, optionsNumber INT(8), multiplePossible BOOLEAN, descr VARCHAR(255));');
    $stmt->execute();
    $stmt = $GLOBALS['conn']->prepare('INSERT INTO '.$name.' (iserv, optionsNumber, multiplePossible, descr) VALUES (0, :optionsNumber, :multiplePossible, :descr);');
    $stmt->bindParam(':optionsNumber', $optionsNumber);
    $stmt->bindParam(':multiplePossible', $multiplePossible);
    $stmt->bindParam(':descr', $descr);
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
    $stmt = $GLOBALS['conn']->prepare('SELECT * FROM '.$name.' WHERE id=:option');
    $stmt->bindParam(':option', $option);
    $stmt->execute();
    $opt = $stmt->fetchAll();
    $av = $stmt->fetchAll();
    if ($av) {
        echo 'You already voted. <br> <a href="index.php">Go back.</a>';
    } else {
        $stmt = $GLOBALS['conn']->prepare('INSERT INTO '.$name.'_options WHERE iserv=:iserv (iserv, votes) VALUES (:iserv, :option)');
        $stmt->bindParam(':iserv', $_SESSION['iserv']);
        $stmt->bindParam(':option', $opt);
        $stmt->execute();
        $av = $stmt->fetchAll();
    }
}