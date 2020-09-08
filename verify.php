<?php
require 'vendor/autoload.php';

$uid = $_GET["uid"];

$server = getenv("DB_HOST");
$username = getenv("DB_U");
$password = getenv("DB_P");
$db = getenv("DB");

$conn = new mysqli($server, $username, $password, $db);
if ($conn->connect_error) {
    header("Location: error.html?e=db");
    die();
}

$query = $conn->query("SELECT * FROM data WHERE id = '$uid'");
if(!$query->num_rows) {
    header("Location: error.html?e=vid");
    die();
}

$query =  $conn->query("UPDATE data SET verified = true WHERE id = '$uid'");
if (!$query) {
    header("Location: error.html?e=db");
    die();
}
header("Location: verified.html");
die();