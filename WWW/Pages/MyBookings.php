<?php
session_start();

/*
I used @ to suppress errors instead of isset or empty.
This makes sure that even if the session does not exist yet,
it will not throw warnings and will safely default to empty.
*/
if (@$_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
    $_SESSION["username"] = "";
}

/*
If there is no user logged in, the page should not be accessible.
This forces the user to login first before seeing their bookings.
*/
if ($_SESSION["userid"] == "") {
    echo "<script>alert('You must login first.');</script>";
    echo "<script>window.location='login.php';</script>";
    exit();
}

/* Database connection setup */
$serverName = "KORU";
$connectionOptions = array(
    "Database" => "gala",
    "Uid" => "",
    "PWD" => ""
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

/* Get the currently logged-in user's ID */
$userID = $_SESSION["userid"];

/*
This query fetches all bookings made by the current user.
It is ordered by ID descending so the latest bookings appear first.
*/
$sql = "SELECT id, placeName, dateStart, dateEnd, message, pricePerDay, totalPrice, status 
        FROM BOOKINGS 
        WHERE userID='".$userID."' 
        ORDER BY id DESC";

$result = sqlsrv_query($conn, $sql);

/*
Bookings are stored into a custom array because sqlsrv_fetch_array
only returns one row at a time.
*/
$bookings = array();
$idx = 0;

$row = sqlsrv_fetch_array($result);
while ($row != null) {
    $bookings[$idx] = $row;
    $idx = $idx + 1;
    $row = sqlsrv_fetch_array($result);
}

/*
places.php is required here so we can match booking names
with their corresponding images from hotels, restaurants, or activities.
*/
require_once "../otherreqs/places.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>My Bookings - GalaExtremists</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../css/styles.css">

<style>
/* Specific styles for My Bookings */
body{background:#f9f9f9;padding-top:80px;}
.page-wrapper{width:100%;padding-top:100px;padding-bottom:60px;}
.container{width:90%;max-width:1200px;margin:auto;}
.booking-grid{display:grid;grid-template-columns:repeat(auto-fill, minmax(450px, 1fr));gap:30px;}
.booking-card{display:flex;gap:24px;border-radius:20px;padding:20px;background:white;box-shadow:0 6px 20px rgba(0,0,0,0.06);align-items:center;transition:all 0.3s;}
.booking-card:hover{transform:translateY(-5px);box-shadow:0 10px 30px rgba(0,0,0,0.12);}
.booking-img{width:180px;height:140px;flex:0 0 180px;object-fit:cover;border-radius:16px;box-shadow:0 4px 12px rgba(0,0,0,0.1);}
.booking-details{flex:1;display:flex;flex-direction:column;justify-content:space-between;height:100%;}
.booking-title{font-size:20px;font-weight:700;color:#333;margin-bottom:8px;}
.small-label{font-weight:600;font-size:12px;color:#888;text-transform:uppercase;margin-top:10px;}
.small-value{font-size:15px;color:#444;font-weight:500;}
.price-badge{background:#ecf0f1;color:#6f42c1;padding:6px 14px;border-radius:50px;font-weight:700;}

/* Action buttons */
.action-link{font-size:13px;font-weight:600;text-decoration:none;padding:8px 16px;border-radius:50px;transition:0.3s;}
.receipt-btn{color:#6f42c1;border:1px solid #6f42c1;background:transparent;}
.receipt-btn:hover{background:#6f42c1;color:white;}
.details-btn{background:#6f42c1;color:white;border:1px solid #6f42c1;}
.details-btn:hover{background:#5a32a3;border-color:#5a32a3;}
.pay-btn{background:#2ecc71;color:white;border:none;}
.pay-btn:hover{background:#27ae60;}
.cancel-btn{background:#f39c12;color:white;border:none;}
.cancel-btn:hover{background:#e67e22;}
.delete-btn{background:#e74c3c;color:white;border:none;}
.delete-btn:hover{background:#c0392b;}

.no-bookings{text-align:center;padding:80px;font-size:24px;color:#aaa;font-weight:600;}

@media(max-width:900px){
    .booking-grid{grid-template-columns:1fr;}
    .booking-card{flex-direction:column;align-items:flex-start;}
    .booking-img{width:100%;height:200px;}
}
</style>
</head>

<body>

<!-- Navigation bar reused across pages -->
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

        <div class="nav-right">
            <?php
            /*
            Dropdown is only shown when a user is logged in.
            Otherwise, login and register buttons are displayed.
            */
            if ($_SESSION["userid"] != "") {
                echo "
                <div class='dropdown'>
                    <a class='nav-btn dropbtn'>Hello, ".$_SESSION["username"]."</a>
                    <div class='dropdown-content'>
                        <a href='MyBookings.php'>My Bookings</a>
                        <a href='../otherreqs/logout.php'>Logout</a>
                    </div>
                </div>
                ";
            } else {
                echo "<a href='login.php' class='nav-btn'>Login</a>";
                echo "<a href='register.php' class='nav-btn'>Register</a>";
            }
            ?>
        </div>

    </div>
</div>

<div class="page-wrapper">
    <div class="container">

        <h1 style="color:#4a2a8a;margin-bottom:18px;">My Bookings</h1>

        <?php
        /*
        If there are no bookings, a message is shown instead of cards.
        */
        $total = count($bookings);

        if ($total == 0) {
            echo "<div class='no-bookings'>You have no bookings yet.</div>";
        }

        echo "<div class='booking-grid'>";

        for ($i = 0; $i < $total; $i = $i + 1) {

            /*
            Each booking row is unpacked into readable variables
            so it is easier to display in HTML.
            */
            $placeName = $bookings[$i]["placeName"];
            $ds = $bookings[$i]["dateStart"];
            $de = $bookings[$i]["dateEnd"];
            $msg = $bookings[$i]["message"];
            $tp = $bookings[$i]["totalPrice"];

            /*
            Dates from SQL Server sometimes return as DateTime objects,
            so they are formatted into readable strings.
            */
            if ($ds instanceof DateTime) { $ds = $ds->format("Y-m-d"); }
            if ($de instanceof DateTime) { $de = $de->format("Y-m-d"); }
            if ($msg == "") { $msg = "(No message)"; }

            /*
            Image number is determined by matching the booking name
            against hotels, restaurants, and activities.
            */
            $imgNumber = 1;

            $tcount = count($hotels);
            for ($j = 0; $j < $tcount; $j = $j + 1) {
                if ($hotels[$j]["name"] == $placeName) {
                    $imgNumber = $hotels[$j]["img"];
                    break;
                }
            }

            if ($imgNumber == 1) {
                $tcount = count($restaurants);
                for ($j = 0; $j < $tcount; $j = $j + 1) {
                    if ($restaurants[$j]["name"] == $placeName) {
                        $imgNumber = $restaurants[$j]["img"];
                        break;
                    }
                }
            }

            if ($imgNumber == 1) {
                $tcount = count($activities);
                for ($j = 0; $j < $tcount; $j = $j + 1) {
                    if ($activities[$j]["name"] == $placeName) {
                        $imgNumber = $activities[$j]["img"];
                        break;
                    }
                }
            }
        ?>
        <div class="booking-card">

            <!-- Image fallback uses onerror to support different extensions -->
            <img src="../images/<?php echo $imgNumber; ?>.jpg"
                 class="booking-img"
                 onerror="this.src='../images/<?php echo $imgNumber; ?>.jpeg';this.onerror=null;"
                 onError="this.src='../images/<?php echo $imgNumber; ?>.png';this.onerror=null;">

            <div class="booking-details">

                <div>
                    <div class="top-row">
                        <div>
                            <div class="booking-title"><?php echo $placeName; ?></div>
                        </div>
                        <div>
                            <div class="price-badge">₱<?php echo $tp; ?></div>
                        </div>
                    </div>

                    <div>
                        <div class="small-label">Start Date:</div>
                        <div class="small-value"><?php echo $ds; ?></div>

                        <div class="small-label">End Date:</div>
                        <div class="small-value"><?php echo $de; ?></div>

                        <div class="small-label">Message:</div>
                        <div class="small-value"><?php echo $msg; ?></div>

                        <div class="small-label">Status:</div>
                        <div class="small-value" style="font-weight:700;color:<?php 
                            if ($bookings[$i]["status"] == "Payment Successful") {
                                echo "#27ae60";
                            } else if ($bookings[$i]["status"] == "Cancelled") {
                                echo "#e74c3c";
                            } else {
                                echo "#f39c12";
                            }
                        ?>;"><?php echo $bookings[$i]["status"]; ?></div>
                    </div>
                </div>

                <!-- Action buttons related to the booking -->
                <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;">
                    <a href="BookingSuccess.php?place=<?php echo urlencode($placeName); ?>&price=<?php echo urlencode($tp); ?>&img=<?php echo urlencode($imgNumber); ?>"
                       class="action-link receipt-btn">
                        Receipt
                    </a>

                    <a href="destination.php?id=<?php echo ($imgNumber - 1); ?>"
                       class="action-link details-btn">
                        Details
                    </a>

                    <?php
                    // Show different buttons based on status
                    if ($bookings[$i]["status"] == "Payment not initiated") {
                        // Show Pay Online button - goes to PayMongo then updates status
                        echo "<a href='paymongo_create.php?booking_id=".$bookings[$i]["id"]."&amount=".$tp."&title=".urlencode($placeName)."' class='action-link pay-btn'>Pay Online</a>";
                    } else if ($bookings[$i]["status"] == "Payment Successful") {
                        // Show Cancel button
                        echo "<a href='../otherreqs/cancel_booking.php?id=".$bookings[$i]["id"]."' class='action-link cancel-btn' onclick='return confirm(\"Are you sure you want to cancel this booking?\");'>Cancel</a>";
                    } else if ($bookings[$i]["status"] == "Cancelled") {
                        // Show Delete button
                        echo "<a href='../otherreqs/delete_booking.php?id=".$bookings[$i]["id"]."' class='action-link delete-btn' onclick='return confirm(\"Are you sure you want to delete this booking?\");'>Delete</a>";
                    }
                    ?>
                </div>

            </div>

        </div>
        <?php
        }

        echo "</div>";
        ?>
    </div>
</div>



</body>
</html>
