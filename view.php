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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
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
        $type = $properties['multiplePossible'] ? 'check' : 'radio';
        // generate checkboxes with same name for radio buttons and brackets for checkboxes
        foreach ($options as $key=>$option) {
            echo '<label>'.$option['meaning'].'</label><input class="el" name="yes" type=' . $type . ' id='.$option['id'].' value='.$option['id'].'>';
        }
        
        echo '<input type="submit" name="vote" value="Add Vote"></form></main></html>';
    } else {
        $stmt = $GLOBALS['conn']->prepare('SELECT id,meaning,votes from '.$survey.'_options;');
        $stmt->execute();
        $options = $stmt->fetchAll();
        $last_key = end(array_keys($options));
        $available_colors = array('aqua', 'black', 'blue', 'fuchsia', 'gray', 'green', 'lime', 'maroon', 'navy', 'olive', 'purple', 'red', 'silver', 'teal', 'yellow');
        $colors = '[';
        $xValues = '[';
        $yValues = '[';
        foreach ($options as $key=>$option) {
            $color = $available_colors[array_rand($available_colors)];
            unset($available_colors[array_search($color, $available_colors)]);
            if ($key == $last_key) {
                $colors .= '"'.$color.'"';
                $xValues .= '"'.$option['meaning'].'"';
                $yValues .= '"'.$option['votes'].'"';
            } else {
                $colors .= '"'.$color.'",';
                $xValues .= '"'.$option['meaning'].'",';
                $yValues .= '"'.$option['votes'].'",';
            }
        }
        echo '<canvas id="results" style="width:100%;max-width:700px"></canvas>';
        echo '<script>
        const xValues = '.$xValues.'];
        const yValues = '.$yValues.'];
        const barColors = '.$colors.'];
        
        new Chart("results", {
          type: "bar",
          data: {
            labels: xValues,
            datasets: [{
              backgroundColor: barColors,
              data: yValues
            }]
          },
          options: {
            legend: {display: false},
            title: {
              display: true,
              text: "'.$survey.'"
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
          }
        });
        </script>';
    }
}
