<?php
$serverName = "KORU";
$connectionOptions = array(
    "Database" => "gala",
    "Uid" => "",
    "PWD" => ""
);
$conn = sqlsrv_connect($serverName, $connectionOptions);
$email = $_POST["email"];
$password = $_POST["password"];
$sql = "SELECT id, username, password FROM USERS WHERE email='$email'";
$result = sqlsrv_query($conn, $sql);
$row = sqlsrv_fetch_array($result);
if ($row["password"] == "") {
    header("Location: ../Pages/login.php?error=Invalid email or password");
    exit();
}
if ($row["password"] == $password) {
    header("Location: ../Pages/start.php");
    exit();
}
header("Location: ../Pages/login.php?error=Invalid email or password");
exit();
?>
