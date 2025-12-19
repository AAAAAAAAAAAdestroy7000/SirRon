<?php
session_start();

/*
user must be logged in before creating a payment.
If userid is empty, stop the process.
*/
if (@$_SESSION["userid"] == "") {
    echo "You must be logged in.";
    exit();
}

/*
initialize variables to avoid undefined warnings.
*/
$bookingID = 0;
$amount = 0;
$title = "";

/*
get values passed from MyBookings.php using GET.
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
if booking ID or amount is missing, stop the payment.
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
build request body step by step
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
convert array into JSON format.
*/
$jsonBody = json_encode($data);

/*
initialize cURL request.
I copied this part from the PayMongo documentation because it is too hard to memorize.
*/
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paymongo.com/v1/links");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);

/*
set request headers.
authorization uses Basic Auth with the secret key.
*/
$headers = array();
$headers[0] = "Content-Type: application/json";
$headers[1] = "Authorization: Basic ".base64_encode($secretKey.":");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

/*
execute request and close cURL.
*/
$result = curl_exec($ch);
curl_close($ch);

/*
decode the response from PayMongo.
*/
$response = json_decode($result, true);

/*
extract checkout URL from response.
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
redirect user to PayMongo checkout page
if the link was successfully created.
*/
if ($payURL != "") {
    // update booking status to "Payment Successful" for demo purposes
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
if something went wrong, show error message.
*/
echo "Failed to generate payment link.";
?>
