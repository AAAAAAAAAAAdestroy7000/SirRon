<?php
session_start();

/*
User must be logged in before creating a payment.
If userid is empty, stop the process.
*/
if (@$_SESSION["userid"] == "") {
    echo "You must be logged in.";
    exit();
}

/*
Initialize variables to avoid undefined warnings.
*/
$bookingID = 0;
$amount = 0;
$title = "";

/*
Get values passed from MyBookings.php using GET.
*/
if ($_GET["booking_id"] != "") {
    $bookingID = $_GET["booking_id"];
}

if ($_GET["amount"] != "") {
    $amount = $_GET["amount"];
}

if ($_GET["title"] != "") {
    $title = $_GET["title"];
}

/*
Basic validation.
If booking ID or amount is missing, stop the payment.
*/
if ($bookingID == 0 || $amount == 0) {
    echo "Invalid payment.";
    exit();
}

/*
PayMongo requires the amount in centavos,
so we multiply the peso amount by 100.
*/
$amount = $amount * 100;

/*
PayMongo secret key (TEST MODE).
*/
$secretKey = "sk_test_aojcRtZRJuiea8sCvoGBMXbw";

/*
Build request body step by step
using simple arrays.
*/
$data = array();
$dataAttributes = array();
$dataAttributes["amount"] = $amount;
$dataAttributes["currency"] = "PHP";
$dataAttributes["description"] = "Booking Payment for: ".$title;

$dataInner = array();
$dataInner["attributes"] = $dataAttributes;

$data["data"] = $dataInner;

/*
Convert array into JSON format.
*/
$jsonBody = json_encode($data);

/*
Initialize cURL request.
*/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paymongo.com/v1/links");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

/*
Set request headers.
Authorization uses Basic Auth with the secret key.
*/
$headers = array();
$headers[0] = "Content-Type: application/json";
$headers[1] = "Authorization: Basic ".base64_encode($secretKey.":");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

/*
Execute request and close cURL.
*/
$result = curl_exec($ch);
curl_close($ch);

/*
Decode the response from PayMongo.
*/
$response = json_decode($result, true);

/*
Extract checkout URL from response.
*/
$payURL = "";

if ($response != null) {
    if ($response["data"] != "") {
        if ($response["data"]["attributes"] != "") {
            if ($response["data"]["attributes"]["checkout_url"] != "") {
                $payURL = $response["data"]["attributes"]["checkout_url"];
            }
        }
    }
}

/*
Redirect user to PayMongo checkout page
if the link was successfully created.
*/
if ($payURL != "") {
    // Update booking status to "Payment Successful" for demo purposes
    // (since test API doesn't actually verify payment)
    $serverName = "KORU";
    $connectionOptions = [
        "Database" => "gala",
        "Uid" => "",
        "PWD" => ""
    ];
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    
    $updateSQL = "UPDATE BOOKINGS SET status='Payment Successful' WHERE id=?";
    $updateParams = [$bookingID];
    sqlsrv_query($conn, $updateSQL, $updateParams);
    
    header("Location: ".$payURL);
    exit();
}

/*
If something went wrong, show error message.
*/
echo "Failed to generate payment link.";
?>
