<?php
require 'vendor/autoload.php';
session_start();

$url =filter_var($_GET["url"], FILTER_SANITIZE_URL);
$email = $_GET["email"];

if(filter_var($url, FILTER_VALIDATE_URL)) {

    // Cooldown for the register stuff
    if(isset($_SESSION['last_submit']) && ((time() - $_SESSION['last_submit']) < 60 * 5)) {
        header("Location: error.html?e=cooldown");
    }
    $_SESSION['last_submit'] = time();

    $server = getenv('DB_HOST');
    $username = getenv('DB_U');
    $password = getenv('DB_P');
    $db = getenv('DB');

    $conn = new mysqli($server, $username, $password, $db);
    if ($conn->connect_error) {
        header("Location: error.html?e=db");
    }

    $url = urlencode($url); 
    
    $query = $conn->query("SELECT * FROM data WHERE url = '$url' AND email = '$email'");
	if($query->num_rows) {
        header("Location: error.html?e=ver");
    }
    
	$query = "INSERT INTO data (url, email, verified) VALUES ('$url', '$email', false)";
	if (!$conn->query($query)) {
        header("Location: error.html?e=db");
    }
    
    $query = $conn->query("SELECT * FROM data WHERE url = '$url' AND email = '$email'");

    $message = "Hi there!\nYou need to verify your email to recive an e-mail when your website is down.\nYour verification link is: https://downtime.macedon.ga/verify.php?uid=" . $query->fetch_all(MYSQLI_BOTH)[0]["id"];
    echo $url . "\n" . $email . "\n" . $message;
    $from = new SendGrid\Email(null, "no-reply@macedon.ga");
    $subject = "Verify your e-mail";
    $to = new SendGrid\Email(null, $email);
    $content = new SendGrid\Content("text/plain", $message);
    $mail = new SendGrid\Mail($from, $subject, $to, $content);

    $apiKey = getenv('SENDGRID_API_KEY');
    $sg = new \SendGrid(getenv('SENDGRID_API_KEY'));

    $response = $sg->client->mail()->send()->post($mail);

    header("Location: sent.html");
} else {
    header("Location: error.html?e=url");
}
