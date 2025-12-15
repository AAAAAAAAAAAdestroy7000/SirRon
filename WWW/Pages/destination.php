<?php
session_start();

// instead of using isset or empty, @ is used to suppress warnings
// this ensures session variables exist even if they were never set
if (@$_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
    $_SESSION["username"] = "";
}

// id is received from the URL to determine which place to display
$id = @$_GET["id"];

// places.php contains the hotels, restaurants, and activities arrays
require_once "../otherreqs/places.php";

// item will hold the selected place data
$item = "";
$found = 0;

// first range is reserved for hotels (0–9)
if ($id >= 0 && $id <= 9) {
    $item = $hotels[$id];
    $found = 1;
}

// if not found yet, check restaurants (10–19)
if ($found == 0) {
    if ($id >= 10 && $id <= 19) {
        $item = $restaurants[$id - 10];
        $found = 1;
    }
}

// if still not found, check activities (20–29)
if ($found == 0) {
    if ($id >= 20 && $id <= 29) {
        $item = $activities[$id - 20];
        $found = 1;
    }
}

// extract specific details from the selected item
$name = $item["name"];
$city = $item["city"];
$address = $item["address"];
$price = $item["price"];
$img = $item["img"];
$notes = $item["notes"];
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $name; ?> - GalaExtremists</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/styles.css">

<style>
/* Specific styles for destination page */
.main-wrapper{display:flex;justify-content:center;align-items:flex-start;padding-top:40px;padding-bottom:80px;width:90%;max-width:1200px;margin:0 auto;gap:50px;}
.left-side{width:50%;}
.left-side img{width:100%;height:500px;object-fit:cover;border-radius:24px;box-shadow:0 10px 40px rgba(0,0,0,0.15);}
.right-side{width:50%;color:#333;background:white;padding:40px;border-radius:24px;box-shadow:0 4px 20px rgba(0,0,0,0.05);}
.right-side h1{font-size:48px;font-weight:700;margin-bottom:20px;color:#111;line-height:1.2;}
.detail-label{font-weight:600;color:#888;font-size:14px;text-transform:uppercase;letter-spacing:1px;margin-top:24px;margin-bottom:8px;}
.detail-value{font-size:18px;color:#333;font-weight:500;}
.notes-box{line-height:1.8;font-size:16px;color:#555;}

/* Button styles */
.action-btn{
    display:inline-block;
    padding:14px 28px;
    border-radius:50px;
    font-weight:600;
    text-decoration:none;
    transition:0.3s;
    text-align:center;
}
.btn-primary{background:#6f42c1;color:white;box-shadow:0 4px 15px rgba(111, 66, 193, 0.4);}
.btn-primary:hover{background:#5a32a3;transform:translateY(-2px);}
.btn-outline{background:transparent;color:#6f42c1;border:2px solid #6f42c1;}
.btn-outline:hover{background:#6f42c1;color:white;}

</style>
</head>

<body>

<!-- main navigation bar that stays consistent across pages -->
<div class="nav-bar">
    <div class="nav-inner">

        <div class="nav-left">
            <a href="start.php" class="logo-text">
                GalaExtremist
            </a>
        </div>

        <!-- center navigation links -->
        <div class="nav-center">
            <a href="trips.php">Trips</a>
            <a href="forums.php">Forums</a>
        </div>

        <!-- login/register buttons change depending on session state -->
        <div class="nav-right">
            <?php if ($_SESSION["userid"] == "") { ?>
                <a href="login.php" class="nav-btn">Login</a>
                <a href="register.php" class="nav-btn">Register</a>
            <?php } else { ?>
                <!-- dropdown only appears when a user is logged in -->
                <div class="dropdown">
                    <a class="nav-btn dropbtn">Hello, <?php echo $_SESSION["username"]; ?></a>
                    <div class="dropdown-content">
                        <a href="MyBookings.php">My Bookings</a>
                        <a href="../otherreqs/logout.php">Logout</a>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</div>

<div class="main-wrapper">

    <!-- left side shows the main image of the place -->
    <div class="left-side">
        <img src="../images/<?php echo $img; ?>.jpg"
             onerror="this.src='../images/<?php echo $img; ?>.png';">
    </div>

    <!-- right side contains all textual details -->
    <div class="right-side">

        <h1><?php echo $name; ?></h1>

        <div class="detail-label">City:</div>
        <p class="detail-value"><?php echo $city; ?></p>

        <div class="detail-label">Address:</div>
        <p class="detail-value"><?php echo $address; ?></p>

        <div class="detail-label">Price Range:</div>
        <p class="detail-value" style="color:#6f42c1;font-weight:700;"><?php echo $price; ?></p>

        <!-- rating is loaded dynamically from TripAdvisor API -->
        <div class="detail-label">Rating:</div>
        <div id="ta-rating"
             data-name="<?php echo $name; ?>"
             style="margin-bottom:15px;color:#ffc107;font-weight:bold;">
            Loading TripAdvisor rating...
        </div>

        <div class="detail-label">About:</div>
        <div class="notes-box"><?php echo $notes; ?></div>

        <!-- navigation buttons -->
        <div style="margin-top:40px;display:flex;gap:20px;">
            <a href="start.php" class="action-btn btn-outline">
                Back to Homepage
            </a>

            <a href="booking.php?id=<?php echo $id; ?>" class="action-btn btn-primary">
                Book Now
            </a>
        </div>

    </div>

</div>

<!-- footer -->
<?php require_once "C:/xampp/htdocs/WWW/otherreqs/footerdetails.php"; ?>

<script>
// get the rating container and place name
var box = document.getElementById("ta-rating");
var name = box.getAttribute("data-name");

// request TripAdvisor rating using AJAX
var xhr = new XMLHttpRequest();
xhr.open("GET","../API/ta_lookup.php?name=" + encodeURIComponent(name));

xhr.onload = function(){
    var data = JSON.parse(this.responseText);

    // check if data exists and has rating property (not error)
    if (data != null && data.rating != null && data.rating != "" && data.error == null) {
        var r = parseFloat(data.rating);
        var html = "";

        // build visual rating bubbles
        for (var i = 1; i <= 5; i = i + 1) {
            if (i <= r) {
                html = html + "<span class='bubble filled'>●</span>";
            } else {
                html = html + "<span class='bubble empty'>●</span>";
            }
        }

        html = html + "<span style='font-size:20px;margin-left:10px;font-weight:bold;color:#7C3AED;'>" + r + "/5.0</span>";
        box.innerHTML = html;
    } else {
        box.innerHTML = "TripAdvisor rating not available";
    }
};

xhr.send();
</script>

</body>
</html>
