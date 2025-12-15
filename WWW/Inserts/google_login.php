<?php
session_start();

// token sent by Google after user signs in
$token = @$_POST["token"];

// login cannot continue without Google token
if ($token == "") {
    header("Location: ../Pages/login.php?error=Google token missing");
    exit();
}

// split JWT token into parts
$parts = explode(".", $token);
$payload = "";

// payload is the second part of the token
if (count($parts) >= 2) {
    $payload = $parts[1];
}

// JWT payload needs correct padding before decoding
$remainder = strlen($payload) % 4;

if ($remainder > 0) {
    $payload .= str_repeat("=", 4 - $remainder);
}

// convert URL-safe base64 to standard base64
$payload = str_replace(array('-', '_'), array('+', '/'), $payload);

// decode payload to extract Google account data
$data = base64_decode($payload);
$user = json_decode($data, true);

// email will be used to identify the user
$email = "";

// extract email from decoded Google data
if ($user != null) {
    if (@$user["email"] != "") {
        $email = $user["email"];
    }
}

// email is required to match user in database
if ($email == "") {
    header("Location: ../Pages/login.php?error=Unable to get email from Google");
    exit();
}

// database connection setup
$serverName = "KORU";

$connectionOptions = array(
    "Database" => "gala",
    "Uid" => "",
    "PWD" => ""
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

// check if Google email exists in USERS table
$sql = "SELECT id, username FROM USERS WHERE email='".$email."'";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($result);

// if no account is found, redirect user to Google registration instead
if ($row["id"] == "") {
    header(
        "Location: ../Pages/register.php?error=Account not found. Please Sign Up using Google.&email=".$email
    );
    exit();
}

// store user data into session after successful login
$_SESSION["userid"] = $row["id"];
$_SESSION["username"] = $row["username"];

// redirect to homepage after Google login
header("Location: ../Pages/start.php?success=Logged in with Google");
exit();

?>
