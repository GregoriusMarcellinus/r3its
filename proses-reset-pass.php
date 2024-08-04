<?php

$token = $_POST["token"];

$token_hash = hash("sha256", $token);

$mysqli = require __DIR__ . "/database.php";

$sql = "SELECT * FROM tb_pengelolah
        WHERE reset_token_hash = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("s", $token_hash);

$stmt->execute();

$result = $stmt->get_result();

$pengelolah = $result->fetch_assoc();

if ($pengelolah === null) {
    die("token not found");
}

if (strtotime($pengelolah["reset_token_expires_at"]) <= time()) {
    die("token has expired");
}

if (strlen($_POST["password"]) < 8) {
    die("kata sandi harus kurang lebih 8 karakter");
}

if ( ! preg_match("/[a-z]/i", $_POST["password"])) {
    die("Kata sandi harus memuat huruf besar");
}

if ( ! preg_match("/[0-9]/", $_POST["password"])) {
    die("kata sandi harus memuat setidaknya 1 angka");
}

if ($_POST["password"] !== $_POST["password-konfirmasi"]) {
    die("Passwords harus sama");
}

$password_hash = password_hash($_POST["password"], PASSWORD_DEFAULT);


$sql = "UPDATE tb_pengelolah
        SET password_hash = ?,
            reset_token_hash = NULL,
            reset_token_expires_at = NULL
        WHERE id_pengelolah = ?";

$stmt = $mysqli->prepare($sql);

$stmt->bind_param("ss", $password_hash, $pengelolah["id_pengelolah"]);

$stmt->execute();

header("Location: terubah.php");
