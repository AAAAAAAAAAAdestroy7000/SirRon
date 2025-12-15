<?php
session_start();

// database server name
$serverName = "KORU";

// connection settings for the gala database
$connectionOptions = [
    "Database" => "gala",
    "Uid" => "",
    "PWD" => ""
];

// establish database connection
$conn = sqlsrv_connect($serverName, $connectionOptions);

// get booking ID from URL
$bookingID = $_GET["id"];
$userID = $_SESSION["userid"];

// user must be logged in
if ($userID == "") {
    header("Location: ../Pages/login.php");
    exit();
}

// verify this booking belongs to the logged-in user
$checkSQL = "SELECT userID FROM BOOKINGS WHERE id=?";
$checkParams = [$bookingID];
$checkResult = sqlsrv_query($conn, $checkSQL, $checkParams);
$checkRow = sqlsrv_fetch_array($checkResult);

// if booking doesn't belong to user, redirect
if ($checkRow["userID"] != $userID) {
    header("Location: ../Pages/MyBookings.php");
    exit();
}

// update booking status to "Cancelled"
$sql = "UPDATE BOOKINGS SET status='Cancelled' WHERE id=?";
$params = [$bookingID];
sqlsrv_query($conn, $sql, $params);

// redirect back to My Bookings page
header("Location: ../Pages/MyBookings.php");
exit();

?>
