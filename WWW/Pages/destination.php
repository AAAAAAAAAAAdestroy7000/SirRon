<?php
session_start();
if (@$_SESSION["userid"] == "") { $_SESSION["userid"] = ""; $_SESSION["username"] = ""; }
$id = @$_GET["id"];
require_once "../otherreqs/places.php";
$item = ""; $found = 0;
if ($id >= 0 && $id <= 9) { $item = $hotels[$id]; $found = 1; }
if ($found == 0) { if ($id >= 10 && $id <= 19) { $item = $restaurants[$id - 10]; $found = 1; } }
if ($found == 0) { if ($id >= 20 && $id <= 29) { $item = $activities[$id - 20]; $found = 1; } }
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
<link rel="stylesheet" href="../css/styles.css">
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial;}
.main-wrapper{display:flex;justify-content:center;align-items:flex-start;padding-top:150px;padding-bottom:50px;width:100%;}
.left-side{width:50%;padding:20px;}
.left-side img{width:100%;height:500px;object-fit:cover;border-radius:15px;box-shadow:0 0 12px rgba(0,0,0,0.4);}
.right-side{width:50%;padding:20px;color:#333;}
.right-side h1{font-size:42px;font-weight:bold;margin-bottom:10px;}
.detail-label{font-weight:bold;color:#6f42c1;margin-top:15px;}
.notes-box{margin-top:20px;line-height:1.6;font-size:18px;color:#444;}
.bubble{font-size:28px;margin-right:6px;}
.filled{color:#6f42c1;}
.empty{color:#ccc;}
</style>
</head>
<body>

<div class="nav-bar">
<div class="nav-inner">
<div class="nav-left">
<a href="start.php"><img src="../images/GalaExtremist.png" style="height:110px;"></a>
</div>
<div class="nav-right">
<?php
if ($_SESSION["userid"] != "") {
echo "<div class='dropdown'><button class='nav-btn dropbtn'>Welcome, ".$_SESSION["username"]."</button><div class='dropdown-content'><a href='MyBookings.php'>My Bookings</a><a href='../otherreqs/logout.php'>Logout</a></div></div>";
} else {
echo "<a href='login.php' class='nav-btn'>Login</a>";
echo "<a href='register.php' class='nav-btn'>Register</a>";
}
?>
</div>
</div>
</div>

<div class="main-wrapper">
<div class="left-side">
<img src="../images/<?php echo $img; ?>.jpg" onerror="this.src='../images/<?php echo $img; ?>.png';">
</div>
<div class="right-side">
<h1><?php echo $name; ?></h1>
<div class="detail-label">City:</div>
<p><?php echo $city; ?></p>
<div class="detail-label">Address:</div>
<p><?php echo $address; ?></p>
<div class="detail-label">Price Range:</div>
<p style="font-weight:bold;color:#6f42c1;"><?php echo $price; ?></p>
<div class="detail-label">Rating:</div>
<div id="ta-rating" data-name="<?php echo $name; ?>" style="margin-bottom:15px;color:#6f42c1;font-weight:bold;">Loading TripAdvisor rating...</div>
<div class="detail-label">About:</div>
<div class="notes-box"><?php echo $notes; ?></div>
<div style="margin-top:30px;display:flex;gap:20px;">
<a href="start.php" style="padding:12px 25px;background:#6f42c1;color:white;border-radius:8px;text-decoration:none;font-weight:bold;border:2px solid #b59cff;">Back to Homepage</a>
<a href="booking.php?id=<?php echo $id; ?>" style="padding:12px 25px;background:#4a2a8a;color:white;border-radius:8px;text-decoration:none;font-weight:bold;border:2px solid #6f42c1;">Book Now</a>
</div>
</div>
</div>

<script>
var box=document.getElementById("ta-rating");
var name=box.getAttribute("data-name");
var xhr=new XMLHttpRequest();
xhr.open("GET","../API/ta_lookup.php?name="+encodeURIComponent(name));
xhr.onload=function(){
var data=JSON.parse(this.responseText);
if (data != null && data.rating != "") {
var r=parseFloat(data.rating);
var html="";
for (var i=1;i<=5;i=i+1) {
if (i <= r) { html=html+"<span class='bubble filled'>●</span>"; }
else { html=html+"<span class='bubble empty'>●</span>"; }
}
html=html+"<span style='font-size:20px;margin-left:10px;font-weight:bold;color:#6f42c1;'>"+r+"/5.0</span>";
box.innerHTML=html;
} else {
box.innerHTML="TripAdvisor rating not found";
}
};
xhr.send();
</script>

</body>
</html>
