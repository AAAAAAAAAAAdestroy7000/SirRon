<?php
session_start();
if (@$_SESSION["userid"] == "") { $_SESSION["userid"] = ""; }
if (@$_SESSION["username"] == "") { $_SESSION["username"] = ""; }
if ($_SESSION["userid"] == "") { $_SESSION["userid"] = ""; $_SESSION["username"] = ""; }
require_once "../otherreqs/logic.php";

$min = ""; $max = ""; $sort = "";
if (@$_GET["min"] != "") { $min = $_GET["min"]; }
if (@$_GET["max"] != "") { $max = $_GET["max"]; }
if (@$_GET["sort"] != "") { $sort = $_GET["sort"]; }

$filtered = array(); $idx = 0;
$total = count($displayed);

for ($i = 0; $i < $total; $i = $i + 1) {
$item = $displayed[$i];
$priceText = $item["price"];
$priceNum = str_replace("₱","",$priceText);
$priceNum = str_replace(",","",$priceNum);
$priceNum = (int)$priceNum;
$ok = 1;
if ($min != "") { if ($priceNum < $min) { $ok = 0; } }
if ($max != "") { if ($priceNum > $max) { $ok = 0; } }
if ($ok == 1) { $item["priceNum"] = $priceNum; $filtered[$idx] = $item; $idx = $idx + 1; }
}

if ($sort != "") {
$count = count($filtered);
for ($i = 0; $i < $count; $i = $i + 1) {
for ($j = $i + 1; $j < $count; $j = $j + 1) {
if ($sort == "low") {
if ($filtered[$i]["priceNum"] > $filtered[$j]["priceNum"]) {
$temp = $filtered[$i]; $filtered[$i] = $filtered[$j]; $filtered[$j] = $temp;
}}
if ($sort == "high") {
if ($filtered[$i]["priceNum"] < $filtered[$j]["priceNum"]) {
$temp = $filtered[$i]; $filtered[$i] = $filtered[$j]; $filtered[$j] = $temp;
}}
}}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Trips - GalaExtremists</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/styles.css">
<style>
body{background:white;}
.nav-bar{padding:6px 0;background:#6f42c1;position:sticky;top:0;z-index:1000;}
.nav-inner{display:flex;align-items:center;justify-content:space-between;width:90%;margin:auto;}
.nav-left img{height:90px;}
.nav-center{display:flex;gap:20px;}
.nav-center a{color:white;padding:8px 16px;font-weight:bold;text-decoration:none;border-radius:8px;}
.nav-right{display:flex;align-items:center;gap:14px;}
.page{display:flex;width:90%;margin:40px auto;gap:30px;}
.sidebar{width:260px;position:sticky;top:120px;border:1px solid #e5e5e5;border-radius:12px;padding:20px;}
.sidebar h4{color:#6f42c1;font-weight:bold;margin-bottom:14px;}
.sidebar input,.sidebar select{width:100%;padding:8px;border-radius:8px;border:1px solid #ccc;margin-bottom:10px;}
.sidebar button{width:100%;background:#6f42c1;color:white;border:none;border-radius:8px;padding:10px;font-weight:bold;}
.content{flex:1;}
.search-bar{margin-bottom:20px;}
.results{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
.card{border:1px solid #eee;border-radius:14px;overflow:hidden;background:white;}
.card img{width:100%;height:200px;object-fit:cover;}
.card-body{padding:16px;}
.price{color:#6f42c1;font-weight:bold;font-size:18px;}
.more-info-btn{display:block;margin-top:10px;border:2px solid #6f42c1;color:#6f42c1;background:white;font-weight:bold;}
</style>
</head>
<body>

<div class="nav-bar">
<div class="nav-inner">
<div class="nav-left"><a href="start.php"><img src="../images/GalaExtremist.png"></a></div>
<div class="nav-center"><a href="start.php">Back to Home</a><a href="forums.php">Discussions</a></div>
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

<div class="page">
<div class="sidebar">
<h4>Filter</h4>
<form method="GET">
<input type="number" name="min" value="<?php if ($min == "") echo "0"; else echo $min; ?>">
<input type="number" name="max" value="<?php if ($max == "") echo "100000"; else echo $max; ?>">
<select name="sort">
<option value="">Sort by</option>
<option value="low" <?php if ($sort == "low") echo "selected"; ?>>Price: Low to High</option>
<option value="high" <?php if ($sort == "high") echo "selected"; ?>>Price: High to Low</option>
</select>
<button>Apply</button>
</form>
</div>

<div class="content">
<form method="GET" class="search-bar">
<input type="hidden" name="min" value="<?php echo $min; ?>">
<input type="hidden" name="max" value="<?php echo $max; ?>">
<input type="hidden" name="sort" value="<?php echo $sort; ?>">
<input type="text" name="q" value="<?php echo $query; ?>" class="form-control form-control-lg" placeholder="Search trips">
</form>

<div class="results">
<?php
$count = count($filtered);
for ($i = 0; $i < $count; $i = $i + 1) {
$item = $filtered[$i];
$name = $item["name"];
$city = $item["city"];
$img = $item["img"];
$price = $item["price"];
?>
<div class="card">
<img src="../images/<?php echo $img; ?>.jpg" onerror="this.src='../images/<?php echo $img; ?>.jpeg';this.onerror=null;" onError="this.src='../images/<?php echo $img; ?>.png';this.onerror=null;">
<div class="card-body">
<h5><?php echo $name; ?></h5>
<p class="text-muted"><?php echo $city; ?></p>
<p class="price"><?php echo $price; ?></p>
<a href="destination.php?id=<?php echo ($img - 1); ?>&place=<?php echo urlencode($name); ?>&location=<?php echo urlencode($city); ?>" class="btn more-info-btn">More Info</a>
</div>
</div>
<?php } ?>
</div>

</div>
</div>

</body>
</html>
