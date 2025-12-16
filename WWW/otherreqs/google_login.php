<?php
session_start();

$token = @$_POST["token"];

if ($token == "") {
    header("Location: /WWW/Pages/login.php?error=Google token missing");
    exit();
}

// decode
$parts = explode(".", $token);
$payload = "";
if (count($parts) >= 2) {
    $payload = $parts[1];
}

$remainder = strlen($payload) % 4;
if ($remainder > 0) {
    $payload .= str_repeat("=", 4 - $remainder);
}

$payload = str_replace(array('-', '_'), array('+', '/'), $payload);

$data = base64_decode($payload);
$user = json_decode($data, true);

$email = "";
if ($user != null) {
    if (@$user["email"] != "") { $email = $user["email"]; }
}

if ($email == "") {
    header("Location: /WWW/Pages/login.php?error=Unable to get email from Google");
    exit();
}

// dc connect
$serverName = "KORU";
$connectionOptions = array(
    "Database" => "gala",
    "Uid" => "",
    "PWD" => ""
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

// check if account exists
$sql = "SELECT id, username FROM USERS WHERE email='" . $email . "'";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($result);

if (@$row["id"] == "" || $row["id"] == null) {
    header("Location: /WWW/Pages/register.php?error=Account not found. Please Sign Up using Google.&email=" . $email);
    exit();
}

// log in
$_SESSION["userid"] = $row["id"];
$_SESSION["username"] = $row["username"];

header("Location: /WWW/Pages/start.php?success=Logged in with Google");
exit();
?>
