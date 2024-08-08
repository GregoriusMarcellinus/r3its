<?php

$host = "localhost";
$dbname = "u571243125_gosite";
$username = "u571243125_gosite";
$password = "Po4Q+W/|!4";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}
return $mysqli;
