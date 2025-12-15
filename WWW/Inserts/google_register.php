<?php
session_start();

// token sent by Google after user signs up
$token = @$_POST["token"];

// if Google token is missing, registration cannot continue
if ($token == "") {
    header("Location: ../Pages/register.php?error=Google token missing");
    exit();
}

// split the JWT token into parts (header.payload.signature)
$parts = explode(".", $token);
$payload = "";

// payload is the second part of the token
if (count($parts) >= 2) {
    $payload = $parts[1];
}

// JWT payload needs proper padding before decoding
$remainder = strlen($payload) % 4;

if ($remainder > 0) {
    $payload .= str_repeat("=", 4 - $remainder);
}

// convert URL-safe base64 characters back to normal base64
$payload = str_replace(array('-', '_'), array('+', '/'), $payload);

// decode the payload to get user information
$data = base64_decode($payload);
$user = json_decode($data, true);

// variables that will store Google account info
$email = "";
$name = "";

// extract email and name from Google response
if ($user != null) {

    if (@$user["email"] != "") {
        $email = $user["email"];
    }

    if (@$user["name"] != "") {
        $name = $user["name"];
    }
}

// email is required for account creation
if ($email == "") {
    header("Location: ../Pages/register.php?error=Unable to get email from Google");
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

// check if the email already exists in the database
$sql = "SELECT id, username FROM USERS WHERE email='".$email."'";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($result);

// if user already exists, log them in instead of creating a new account
if ($row["id"] != "") {
    $_SESSION["userid"] = $row["id"];
    $_SESSION["username"] = $row["username"];
    header("Location: ../Pages/start.php?success=Logged in with Google");
    exit();
}

// username defaults to Google name
$uname = $name;

// if Google name is missing, use email prefix as username
if ($uname == "") {
    $parts = explode("@", $email);
    $uname = $parts[0];
}

// generate a temporary password for Google users
$password = "GoogleUser".time();

// insert new Google user into database
$sql2 = "INSERT INTO USERS (email, username, password)
         VALUES ('".$email."', '".$uname."', '".$password."')";

sqlsrv_query($conn, $sql2);

// retrieve newly created user account
$sql3 = "SELECT id, username FROM USERS WHERE email='".$email."'";
$result3 = sqlsrv_query($conn, $sql3);
$row3 = sqlsrv_fetch_array($result3);

// store user session after successful registration
$_SESSION["userid"] = $row3["id"];
$_SESSION["username"] = $row3["username"];

// redirect to homepage after Google registration
header("Location: ../Pages/start.php?success=Account created via Google");
exit();

?>
