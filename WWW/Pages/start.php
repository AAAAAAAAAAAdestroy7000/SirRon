<?php
session_start();
if (@$_SESSION["userid"] == "") { $_SESSION["userid"] = ""; }
if (@$_SESSION["username"] == "") { $_SESSION["username"] = ""; }
if ($_SESSION["userid"] == "") { $_SESSION["userid"] = ""; $_SESSION["username"] = ""; }
require_once "../otherreqs/logic.php";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GalaExtremists</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/styles.css">
<style>
.nav-bar{padding:6px 0;}
.nav-inner{display:flex;align-items:center;justify-content:space-between;}
.nav-left img{height:90px;}
.nav-center{display:flex;gap:20px;}
.nav-center a{color:white;padding:8px 16px;font-weight:bold;text-decoration:none;border-radius:8px;}
.nav-right{display:flex;align-items:center;gap:14px;}
</style>
</head>
<body>

<div class="nav-bar">
<div class="nav-inner">
<div class="nav-left">
<a href="start.php"><img src="../images/GalaExtremist.png"></a>
</div>
<div class="nav-center">
<a href="trips.php">Trips / Destinations</a>
<a href="forums.php">Discussions</a>
</div>
<div class="nav-right">
<?php if ($_SESSION["userid"] == "") { ?>
<a href="login.php" class="nav-btn">Login</a>
<a href="register.php" class="nav-btn">Register</a>
<?php } else { ?>
<div class="dropdown">
<a class="nav-btn dropbtn">Welcome, <?php echo $_SESSION["username"]; ?></a>
<div class="dropdown-content">
<a href="MyBookings.php">My Bookings</a>
<a href="../otherreqs/logout.php">Logout</a>
</div>
</div>
<?php } ?>
</div>
</div>
</div>

<div class="hero text-white text-center">
<div class="slide active" style="background-image:url('../images/pic1.jpg')"></div>
<div class="slide" style="background-image:url('../images/pic2.jpg')"></div>
<div class="slide" style="background-image:url('../images/pic3.jpg')"></div>
<div class="slide" style="background-image:url('../images/pic4.jpg')"></div>
<div class="slide" style="background-image:url('../images/pic5.jpg')"></div>

<div class="container position-relative">
<h1 style="font-size:60px;font-weight:bold;margin-bottom:20px;">Travel till you're out of money</h1>
<p style="font-size:34px;margin-bottom:40px;">You may find some hot gals in these trips</p>

<div class="row justify-content-center g-2 mb-4">
<div class="col-auto"><a href="?q=<?php echo urlencode($query); ?>" class="btn btn1-toggle <?php if ($type == "") echo "active"; ?>">All</a></div>
<div class="col-auto"><a href="?q=<?php echo urlencode($query); ?>&type=hotels" class="btn btn1-toggle <?php if ($type == "hotels") echo "active"; ?>">Hotels</a></div>
<div class="col-auto"><a href="?q=<?php echo urlencode($query); ?>&type=restaurants" class="btn btn1-toggle <?php if ($type == "restaurants") echo "active"; ?>">Restaurants</a></div>
<div class="col-auto"><a href="?q=<?php echo urlencode($query); ?>&type=attractions" class="btn btn1-toggle <?php if ($type == "attractions") echo "active"; ?>">Activities</a></div>
</div>

<form method="GET" class="search-bar">
<input type="text" name="q" value="<?php echo $query; ?>" class="form-control form-control-lg" placeholder="Search a place...">
<button class="btn btn-primary btn-lg px-5">Search</button>
</form>
</div>
</div>

<div class="container my-5">
<div class="results">
<?php
$total = count($displayed);
for ($i = 0; $i < $total; $i = $i + 1) {
$item = $displayed[$i];
$name = $item["name"];
$city = $item["city"];
$address = $item["address"];
$price = $item["price"];
$img = $item["img"];
?>
<div class="card shadow-sm border-0 position-relative">
<img src="../images/<?php echo $img; ?>.jpg" onerror="this.src='../images/<?php echo $img; ?>.png';" class="card-img-top" style="height:220px;object-fit:cover;">
<div class="card-body pb-5">
<h5><?php echo $name; ?></h5>
<p class="text-muted"><?php echo $city; ?></p>
<p style="font-weight:bold;color:#6f42c1;"><?php echo $price; ?></p>
</div>
<a href="destination.php?id=<?php echo ($img - 1); ?>" class="more-info-btn btn">More Info</a>
</div>
<?php } ?>
</div>
</div>

<script src="../js/scripts.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
