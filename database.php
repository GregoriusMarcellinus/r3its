<?php

$host = "localhost";
$dbname = "u571243125_R3ITS";
$username = "u571243125_R3ITS";
$password = "Po4Q+W/|!4";

$mysqli = new mysqli(hostname: $host,
                     username: $username,
                     password: $password,
                     database: $dbname);
                     
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}
return $mysqli;
