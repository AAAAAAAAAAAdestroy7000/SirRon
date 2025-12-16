<?php
session_start();

// session fallback using @ to suppress warnings
// ensures userid and username always exist even if session is empty
if (@$_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
    $_SESSION["username"] = "";
}

// id is passed from destination page to identify what place is being booked
$id = @$_GET["id"];

// loads arrays for hotels, restaurants, and activities
require_once "../otherreqs/places.php";

$item = "";
$found = 0;

// hotel ids range from 0–9
if ($id >= 0 && $id <= 9) {
    $item = $hotels[$id];
    $found = 1;
}

// restaurant ids range from 10–19
if ($found == 0) {
    if ($id >= 10 && $id <= 19) {
        $real = $id - 10;
        $item = $restaurants[$real];
        $found = 1;
    }
}

// activity ids range from 20–29
if ($found == 0) {
    if ($id >= 20 && $id <= 29) {
        $real = $id - 20;
        $item = $activities[$real];
        $found = 1;
    }
}

// extracting values from the selected item array
$placeName = $item["name"];
$city = $item["city"];
$address = $item["address"];
$price = $item["price"];
$rating = $item["rating"];
$notes = $item["notes"];
$img = $item["img"];

// booking is restricted to logged-in users only
if ($_SESSION["userid"] == "") {
    echo "<script>alert('You must login or register first.');</script>";
    echo "<script>window.location='login.php?redirect=booking.php&id=".$id."';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Booking - <?php echo $placeName; ?></title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/mobile_fix.css">

<style>
.wrapper{width:900px;margin:40px auto;background:white;padding:25px;border-radius:15px;border:2px solid #6f42c1;box-shadow:0 0 20px rgba(0,0,0,0.08);}
.top-section{display:flex;gap:20px;margin-bottom:20px;}
.place-img{width:350px;height:260px;object-fit:cover;border-radius:12px;box-shadow:0 4px 15px rgba(0,0,0,0.15);}
.details{flex:1;}
.details h1{font-size:42px;color:#4a2a8a;margin-bottom:20px;}
.price-box{margin-top:10px;font-size:28px;font-weight:bold;color:#6f42c1;}
.label{font-weight:bold;color:#6f42c1;margin-top:10px;}
.form-box{margin-top:25px;}
input,textarea{width:100%;padding:10px;border-radius:8px;border:1px solid #bbb;margin-top:6px;}
.btn-purple{width:100%;padding:14px;background:#6f42c1;color:white;border:none;border-radius:10px;margin-top:20px;cursor:pointer;font-weight:bold;}
.btn-purple:hover{background:#532e99;}
</style>

<script>
// calculates total price based on selected dates
// uses price per day multiplied by number of days
function updateTotal(){
    var price = "<?php echo $price; ?>";
    price = price.replace("₱","").replace(",","");
    price = parseInt(price);

    var d1 = document.getElementById("startDate").value;
    var d2 = document.getElementById("endDate").value;

    if (d1 === "" || d2 === "" || isNaN(price)) {
        document.getElementById("totalDisplay").innerHTML = "Total Price: ₱0";
        document.getElementById("totalPrice").value = 0;
        return;
    }

    var start = new Date(d1);
    var end = new Date(d2);
    var diff = end - start;
    var days = diff / (1000 * 60 * 60 * 24);

    if (days < 1) {
        document.getElementById("totalDisplay").innerHTML = "Total Price: ₱0";
        document.getElementById("totalPrice").value = 0;
        return;
    }

    var total = days * price;
    document.getElementById("totalDisplay").innerHTML = "Total Price: ₱" + total;
    document.getElementById("totalPrice").value = total;
}
</script>
</head>

<body>

<!-- main navigation bar that stays consistent across pages -->
<!-- Navigation Bar -->
<?php require_once "../otherreqs/navigationbar.php"; ?>

<div class="wrapper">

    <div class="top-section">

        <!-- displays the image based on the selected item -->
        <img src="../images/<?php echo $img; ?>.jpg"
             class="place-img"
             onerror="this.src='../images/<?php echo $img; ?>.jpeg';this.onerror=null;"
             onError="this.src='../images/<?php echo $img; ?>.png';this.onerror=null;">

        <div class="details">

            <h1><?php echo $placeName; ?></h1>

            <div class="price-box">
                Price Per Day: <?php echo $price; ?>
            </div>

        </div>

    </div>

    <hr style="margin:25px 0;">

    <h2 style="color:#4a2a8a;">Booking Form</h2>

    <div class="form-box">

        <!-- booking form sends data to bookingcheck.php for database insertion -->
        <form method="POST" action="../Inserts/bookingcheck.php">

            <input type="hidden" name="placeName" value="<?php echo $placeName; ?>">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="img" value="<?php echo $img; ?>">

            <!-- price per day is stored separately for database consistency -->
            <input type="hidden" id="pricePerDay" name="pricePerDay"
                   value="<?php echo str_replace(array('₱',','),'',$price); ?>">

            <!-- total price is calculated using JavaScript -->
            <input type="hidden" id="totalPrice" name="totalPrice" value="0">

            <label class="label">Start Date:</label>
            <input type="date" name="dateStart" id="startDate" required oninput="updateTotal()">

            <label class="label">End Date:</label>
            <input type="date" name="dateEnd" id="endDate" required oninput="updateTotal()">

            <p id="totalDisplay" style="font-weight:bold;color:#4a2a8a;margin-top:10px;">
                Total Price: ₱0
            </p>

            <label class="label">Message / Notes (optional):</label>
            <textarea name="message" rows="4"></textarea>

            <button class="btn-purple">Confirm Booking</button>

        </form>

        <!-- returns the user back to the destination details page -->
        <a href="destination.php?id=<?php echo $id; ?>"
           style="display:block;margin-top:15px;color:#6f42c1;font-weight:bold;">
            Back to Details
        </a>

    </div>

</div>

</body>
</html>
