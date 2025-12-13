<?php
$serverName = "KORU";
$connectionOptions = array(
    "Database" => "gala",
    "Uid" => "",
    "PWD" => ""
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
$email = $_POST["email"];
$username = $_POST["username"];
$password = $_POST["password"];
$confirm = $_POST["confirm"];
if ($password != $confirm) {
    header("Location: register.php?error=Passwords do not match");
    exit();
}
$sql = "SELECT email FROM USERS WHERE email='$email'";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($result);
if ($row["email"] != "") {
    header("Location: register.php?error=Email already exists");
    exit();
}
$sql2 = "SELECT username FROM USERS WHERE username='$username'";
$result2 = sqlsrv_query($conn, $sql2);
$row2 = sqlsrv_fetch_array($result2);
if ($row2["username"] != "") {
    header("Location: register.php?error=Username already exists");
    exit();
}
$sql3 = "INSERT INTO USERS (email, username, password) VALUES ('$email', '$username', '$password')";
sqlsrv_query($conn, $sql3);
header("Location: login.php?success=Account created successfully");
exit();
?>
