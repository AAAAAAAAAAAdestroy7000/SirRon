<?php
// values are passed through the URL after a successful booking
// @ is used to suppress warnings in case the parameters are missing
$place = @$_GET["place"];
$price = @$_GET["price"];
$img = @$_GET["img"];
?>
<!DOCTYPE html>
<html>
<head>
<title>Booking Success</title>

<style>
.box{width:400px;margin:100px auto;padding:30px;text-align:center;border-radius:12px;border:2px solid #6f42c1;background:#f4f4f4;font-family:Arial;}
img{width:100%;height:200px;object-fit:cover;border-radius:10px;}
.btn{padding:12px;background:#6f42c1;color:white;border-radius:8px;margin-top:15px;display:block;text-decoration:none;}
</style>
</head>

<body>

<div class="box">

    <!-- confirmation message shown after data is successfully inserted into the database -->
    <h2>Booking Successful!</h2>

    <!-- image number is passed from booking page to display the correct image -->
    <img src="../images/<?php echo $img; ?>.jpg">

    <!-- place name passed via URL to confirm what was booked -->
    <h3><?php echo $place; ?></h3>

    <!-- total price is calculated earlier and passed here for display -->
    <p><b>Total Price Paid:</b> ₱<?php echo $price; ?></p>

    <!-- navigation buttons after booking -->
    <a href="start.php" class="btn">Return to Homepage</a>
    <a href="MyBookings.php" class="btn">View My Bookings</a>

</div>

</body>
</html>
