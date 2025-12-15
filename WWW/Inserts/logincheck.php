<?php
session_start();

// database server name for SQL Server connection
$serverName = "KORU";

// connection settings for the gala database
$connectionOptions = array(
    "Database" => "gala",
    "Uid" => "",
    "PWD" => ""
);

// connects to the database
$conn = sqlsrv_connect($serverName, $connectionOptions);

// values submitted from the login form
$email = $_POST["email"];
$password = $_POST["password"];

// retrieves user data based on the entered email
$sql = "SELECT id, username, password FROM USERS WHERE email='$email'";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($result);

// if no password is returned, the email does not exist
if ($row["password"] == "") {
    header("Location: /WWW/Pages/login.php?error=Invalid email or password");
    exit();
}

// checks if the entered password matches the stored password
if ($row["password"] == $password) {
    // login is successful, set session variables
    $_SESSION["userid"] = $row["id"];
    $_SESSION["username"] = $row["username"];
    header("Location: /WWW/Pages/start.php");
    exit();
}

// fallback redirect if password does not match
header("Location: /WWW/Pages/login.php?error=Invalid email or password");
exit();

?>
