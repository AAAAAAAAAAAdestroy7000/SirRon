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

// values coming from session and booking form
$userID      = $_SESSION["userid"];
$placeName   = $_POST["placeName"];
$dateStart   = $_POST["dateStart"];
$dateEnd     = $_POST["dateEnd"];
$message     = $_POST["message"];
$id          = $_POST["id"];
$img         = $_POST["img"];
$totalPrice  = $_POST["totalPrice"];
$pricePerDay = $_POST["pricePerDay"];

// user must be logged in before making a booking
if ($userID == "") {
    echo "<script>alert('You must login before booking.');</script>";
    echo "<script>window.location='login.php';</script>";
    exit();
}

// both start and end dates are required to calculate the total price
if ($dateStart == "" || $dateEnd == "") {
    echo "<script>alert('Please choose both dates.');</script>";
    echo "<script>window.location='../Pages/booking.php?id=".$id."';</script>";
    exit();
}

// end date must be after start date (not same day, not before)
if ($dateEnd <= $dateStart) {
    echo "<script>alert('End date must be after start date. You cannot book for the same day or past dates.');</script>";
    echo "<script>window.location='../Pages/booking.php?id=".$id."';</script>";
    exit();
}

// SQL query to save the booking details into the database
$sql = "
    INSERT INTO BOOKINGS
    (userID, placeName, dateStart, dateEnd, message, pricePerDay, totalPrice, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
";

// parameters used in the SQL query
$params = [
    $userID,
    $placeName,
    $dateStart,
    $dateEnd,
    $message,
    $pricePerDay,
    $totalPrice,
    "Payment not initiated"
];

// execute booking insert query
sqlsrv_query($conn, $sql, $params);


// redirect user to booking success page with details
header(
    "Location: ../Pages/BookingSuccess.php?place=".urlencode($placeName).
    "&price=".urlencode($totalPrice).
    "&img=".urlencode($img)
);
exit();

?>
