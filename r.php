<?php


$block = date('d m', strtotime("2013-01-12 00:00:00.0"));
$today = date('d m', time());

if ($block == $today) {
  echo 'What did really happen to Aaron Schwartz?';
  exit();
}