<?php

// database server name used for SQL Server connection
$serverName = "KORU";

// connection settings for the database
$connectionOptions = array(
    "Database" => "gala",
    "Uid" => "",
    "PWD" => ""
);

// establishes the database connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// values coming from the registration form
$email = $_POST["email"];
$username = $_POST["username"];
$password = $_POST["password"];
$confirm = $_POST["confirm"];

// basic password confirmation check before inserting data
if ($password != $confirm) {
    header("Location: /WWW/Pages/register.php?error=Passwords do not match");
    exit();
}

// checks if the email already exists in the USERS table
$sql = "SELECT email FROM USERS WHERE email='$email'";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($result);

// if an email is found, registration is stopped
if ($row["email"] != "") {
    header("Location: /WWW/Pages/register.php?error=Email already exists");
    exit();
}

// checks if the username already exists
$sql2 = "SELECT username FROM USERS WHERE username='$username'";
$result2 = sqlsrv_query($conn, $sql2);
$row2 = sqlsrv_fetch_array($result2);

// if a username is found, registration is stopped
if ($row2["username"] != "") {
    header("Location: /WWW/Pages/register.php?error=Username already exists");
    exit();
}

// inserts the new user into the database once all checks pass
$sql3 = "INSERT INTO USERS (email, username, password)
         VALUES ('$email', '$username', '$password')";

sqlsrv_query($conn, $sql3);

// redirects the user to login page after successful registration
header("Location: /WWW/Pages/login.php?success=Account created successfully");
exit();

?>
