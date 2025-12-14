<?php
session_start();
$token = @$_POST["token"];
if ($token == "") {
    header("Location: ../Pages/register.php?error=Google token missing");
    exit();
}
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
$name = "";
if ($user != null) {
    if (@$user["email"] != "") { $email = $user["email"]; }
    if (@$user["name"] != "") { $name = $user["name"]; }
}
if ($email == "") {
    header("Location: ../Pages/register.php?error=Unable to get email from Google");
    exit();
}
$serverName = "KORU";
$connectionOptions = array(
    "Database" => "gala",
    "Uid" => "",
    "PWD" => ""
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
$sql = "SELECT id, username FROM USERS WHERE email='" . $email . "'";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($result);
if ($row["id"] != "") {
    $_SESSION["userid"] = $row["id"];
    $_SESSION["username"] = $row["username"];
    header("Location: ../Pages/start.php?success=Logged in with Google");
    exit();
}
$uname = $name;
if ($uname == "") {
    $parts = explode("@", $email);
    $uname = $parts[0];
}
$password = "GoogleUser" . time();
$sql2 = "INSERT INTO USERS (email, username, password) VALUES ('" . $email . "', '" . $uname . "', '" . $password . "')";
sqlsrv_query($conn, $sql2);
$sql3 = "SELECT id, username FROM USERS WHERE email='" . $email . "'";
$result3 = sqlsrv_query($conn, $sql3);
$row3 = sqlsrv_fetch_array($result3);
$_SESSION["userid"] = $row3["id"];
$_SESSION["username"] = $row3["username"];
header("Location: ../Pages/start.php?success=Account created via Google");
exit();
?>
