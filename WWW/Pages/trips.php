<?php
session_start();

// instead of using isset or empty, I used @ to suppress warnings
// this ensures userid and username always exist as session variables
if (@$_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
}

if (@$_SESSION["username"] == "") {
    $_SESSION["username"] = "";
}

// if no user is logged in, reset both values to avoid leftover session data
if ($_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
    $_SESSION["username"] = "";
}

// logic.php is required because it prepares the $displayed array
// this file handles search and type filtering using places.php
require_once "../otherreqs/logic.php";

// default filter values so the page loads with all results
$min = "";
$max = "";
$sort = "";
$cityFilter = "";

// GET values are only assigned if the user interacted with filters
// @ prevents errors when the values do not exist yet
if (@$_GET["min"] != "") {
    $min = $_GET["min"];
}

if (@$_GET["max"] != "") {
    $max = $_GET["max"];
}

if (@$_GET["sort"] != "") {
    $sort = $_GET["sort"];
}

if (@$_GET["city"] != "") {
    $cityFilter = $_GET["city"];
}

// filtered array will store results that pass the price and city checks
$filtered = array();
$idx = 0;

// $displayed comes from logic.php and contains search/type results
$total = count($displayed);

// loop through all displayed items and manually apply filtering
for ($i = 0; $i < $total; $i = $i + 1) {

    // item temporarily holds one place entry from the array
    $item = $displayed[$i];

    // price is stored as text so it must be cleaned before comparison
    $priceText = $item["price"];
    $priceNum = str_replace("₱", "", $priceText);
    $priceNum = str_replace(",", "", $priceNum);
    $priceNum = (int)$priceNum;

    // ok acts as a flag to decide if this item should be included
    $ok = 1;

    // minimum price check
    if ($min != "") {
        if ($priceNum < $min) {
            $ok = 0;
        }
    }

    // maximum price check
    if ($max != "") {
        if ($priceNum > $max) {
            $ok = 0;
        }
    }

    // city filter check
    if ($cityFilter != "") {
        $itemCity = $item["city"];
        // check if the selected city is contained in the item's city string
        if (strpos($itemCity, $cityFilter) == false && $itemCity != $cityFilter) {
            $ok = 0;
        }
    }

    // only valid items are added to the filtered array
    if ($ok == 1) {
        $item["priceNum"] = $priceNum;
        $filtered[$idx] = $item;
        $idx = $idx + 1;
    }
}

// sorting only runs if a sort option was selected
if ($sort != "") {

    $count = count($filtered);

    // simple manual sorting using nested loops
    // avoids advanced PHP functions to stay beginner-level
    for ($i = 0; $i < $count; $i = $i + 1) {
        for ($j = $i + 1; $j < $count; $j = $j + 1) {

            // sort from lowest to highest price
            if ($sort == "low") {
                if ($filtered[$i]["priceNum"] > $filtered[$j]["priceNum"]) {
                    $temp = $filtered[$i];
                    $filtered[$i] = $filtered[$j];
                    $filtered[$j] = $temp;
                }
            }

            // sort from highest to lowest price
            if ($sort == "high") {
                if ($filtered[$i]["priceNum"] < $filtered[$j]["priceNum"]) {
                    $temp = $filtered[$i];
                    $filtered[$i] = $filtered[$j];
                    $filtered[$j] = $temp;
                }
            }

        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Trips - GalaExtremists</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/styles.css">

<style>
.page{display:flex;width:90%;max-width:1400px;margin:40px auto;gap:40px;align-items:flex-start;position:relative;}
.sidebar{width:300px;background:white;border-radius:20px;padding:28px;box-shadow:0 4px 20px rgba(124, 58, 237, 0.08);border:1px solid #F3F4F6;flex-shrink:0;position:relative;}
.sidebar h4{color:#7C3AED;font-weight:700;margin-bottom:24px;font-size:22px;}
.sidebar label{display:block;font-weight:600;color:#6B7280;font-size:14px;margin-bottom:8px;margin-top:16px;}
.sidebar input,.sidebar select{width:100%;padding:12px 16px;border-radius:12px;border:1px solid #E5E7EB;margin-bottom:4px;background:white;font-size:15px;transition:all 0.2s;}
.sidebar input:focus,.sidebar select:focus{outline:none;border-color:#7C3AED;box-shadow:0 0 0 3px rgba(124, 58, 237, 0.1);}
.sidebar button{width:100%;background:linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%);color:white;border:none;border-radius:50px;padding:14px;font-weight:700;box-shadow:0 4px 16px rgba(124, 58, 237, 0.3);transition:all 0.3s;margin-top:20px;}
.sidebar button:hover{background:linear-gradient(135deg, #6D28D9 0%, #5B21B6 100%);transform:translateY(-2px);box-shadow:0 6px 20px rgba(124, 58, 237, 0.4);}
.content{flex:1;min-width:0;max-width:100%;position:relative;}
.search-bar{margin-bottom:30px;width:100%;}
.search-bar input{width:100%;}
.search-btn:hover{background:linear-gradient(135deg, #6D28D9 0%, #5B21B6 100%) !important;transform:translateY(-2px);box-shadow:0 6px 20px rgba(124, 58, 237, 0.4);}
.results{display:grid;grid-template-columns:repeat(auto-fill, minmax(280px, 1fr));gap:30px;min-height:400px;width:100%;}
</style>
</head>

<body>

<!-- navigation bar consistent with start.php -->
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

        <!-- session is used to determine which buttons should be shown -->
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

<div class="page">

    <!-- sidebar filter allows users to narrow results -->
    <div class="sidebar">
        <h4>Filters</h4>

        <form method="GET">
            <!-- preserve search query and type when filtering -->
            <input type="hidden" name="q" value="<?php echo $query; ?>">
            <input type="hidden" name="type" value="<?php echo $type; ?>">

            <label>City</label>
            <select name="city">
                <option value="">All Cities</option>
                <option value="Manila" <?php if ($cityFilter == "Manila") echo "selected"; ?>>Manila</option>
                <option value="Parañaque" <?php if ($cityFilter == "Parañaque") echo "selected"; ?>>Parañaque</option>
                <option value="Makati" <?php if ($cityFilter == "Makati") echo "selected"; ?>>Makati</option>
                <option value="Taguig" <?php if ($cityFilter == "Taguig") echo "selected"; ?>>Taguig</option>
                <option value="Pasay" <?php if ($cityFilter == "Pasay") echo "selected"; ?>>Pasay</option>
                <option value="Quezon City" <?php if ($cityFilter == "Quezon City") echo "selected"; ?>>Quezon City</option>
                <option value="Cebu" <?php if ($cityFilter == "Cebu") echo "selected"; ?>>Cebu</option>
                <option value="Boracay" <?php if ($cityFilter == "Boracay") echo "selected"; ?>>Boracay</option>
                <option value="Bohol" <?php if ($cityFilter == "Bohol") echo "selected"; ?>>Bohol</option>
                <option value="Tagaytay" <?php if ($cityFilter == "Tagaytay") echo "selected"; ?>>Tagaytay</option>
                <option value="Palawan" <?php if ($cityFilter == "Palawan") echo "selected"; ?>>Palawan</option>
                <option value="Pampanga" <?php if ($cityFilter == "Pampanga") echo "selected"; ?>>Pampanga</option>
                <option value="Batangas" <?php if ($cityFilter == "Batangas") echo "selected"; ?>>Batangas</option>
                <option value="Siargao" <?php if ($cityFilter == "Siargao") echo "selected"; ?>>Siargao</option>
                <option value="Coron" <?php if ($cityFilter == "Coron") echo "selected"; ?>>Coron</option>
                <option value="Vigan" <?php if ($cityFilter == "Vigan") echo "selected"; ?>>Vigan</option>
                <option value="Ifugao" <?php if ($cityFilter == "Ifugao") echo "selected"; ?>>Ifugao</option>
            </select>

            <label>Min Price (₱)</label>
            <input type="number" name="min" value="<?php if ($min == "") echo "0"; else echo $min; ?>" placeholder="0">

            <label>Max Price (₱)</label>
            <input type="number" name="max" value="<?php if ($max == "") echo "100000"; else echo $max; ?>" placeholder="100000">

            <label>Sort By</label>
            <select name="sort">
                <option value="">Default</option>
                <option value="low" <?php if ($sort == "low") echo "selected"; ?>>Price: Low to High</option>
                <option value="high" <?php if ($sort == "high") echo "selected"; ?>>Price: High to Low</option>
            </select>

            <button>Apply Filters</button>
        </form>
    </div>

    <div class="content">

        <!-- search bar with hidden inputs to preserve filters -->
        <form method="GET" class="search-bar">
            <input type="hidden" name="min" value="<?php echo $min; ?>">
            <input type="hidden" name="max" value="<?php echo $max; ?>">
            <input type="hidden" name="sort" value="<?php echo $sort; ?>">
            <input type="hidden" name="city" value="<?php echo $cityFilter; ?>">
            <input type="hidden" name="type" value="<?php echo $type; ?>">
            <div style="display:flex;gap:12px;align-items:center;">
                <input type="text" name="q" value="<?php echo $query; ?>" class="form-control form-control-lg" placeholder="Search destinations..." style="flex:1;border-radius:50px;padding:12px 24px;border:1px solid #E5E7EB;">
                <button type="submit" class="search-btn" style="background:linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%);color:white;border:none;border-radius:50px;padding:12px 32px;font-weight:700;cursor:pointer;transition:all 0.3s;white-space:nowrap;">Search</button>
            </div>
        </form>

        <!-- category filter buttons -->
        <div style="margin-bottom:30px;display:flex;gap:12px;flex-wrap:wrap;">
            <a href="?q=<?php echo urlencode($query); ?>&min=<?php echo $min; ?>&max=<?php echo $max; ?>&sort=<?php echo $sort; ?>&city=<?php echo $cityFilter; ?>" 
               class="btn btn1-toggle <?php if ($type == "") echo "active"; ?>">
                All
            </a>
            <a href="?q=<?php echo urlencode($query); ?>&type=hotels&min=<?php echo $min; ?>&max=<?php echo $max; ?>&sort=<?php echo $sort; ?>&city=<?php echo $cityFilter; ?>" 
               class="btn btn1-toggle <?php if ($type == "hotels") echo "active"; ?>">
                Hotels
            </a>
            <a href="?q=<?php echo urlencode($query); ?>&type=restaurants&min=<?php echo $min; ?>&max=<?php echo $max; ?>&sort=<?php echo $sort; ?>&city=<?php echo $cityFilter; ?>" 
               class="btn btn1-toggle <?php if ($type == "restaurants") echo "active"; ?>">
                Restaurants
            </a>
            <a href="?q=<?php echo urlencode($query); ?>&type=attractions&min=<?php echo $min; ?>&max=<?php echo $max; ?>&sort=<?php echo $sort; ?>&city=<?php echo $cityFilter; ?>" 
               class="btn btn1-toggle <?php if ($type == "attractions") echo "active"; ?>">
                Activities
            </a>
        </div>

        <div class="results">
            <?php
            // loop through all filtered items and display them as cards
            $count = count($filtered);

            for ($i = 0; $i < $count; $i = $i + 1) {

                // item holds one place entry from the filtered array
                $item = $filtered[$i];
                $name = $item["name"];
                $city = $item["city"];
                $img = $item["img"];
                $price = $item["price"];
                $rating = $item["rating"];
            ?>
            <div class="card shadow-sm border-0 position-relative">

                <!-- image fallback uses onerror because PHP cannot detect client-side load failures -->
                <img src="../images/<?php echo $img; ?>.jpg"
                     onerror="this.src='../images/<?php echo $img; ?>.png';"
                     class="card-img-top">

                <!-- card content displays place information with rating -->
                <div class="card-body pb-5">
                    <h5><?php echo $name; ?></h5>
                    <p class="text-muted" style="font-size:14px;margin-bottom:8px;"><?php echo $city; ?></p>
                    
                    <!-- rating display with stars -->
                    <div style="margin-bottom:10px;">
                        <?php
                        // display rating as stars
                        $r = $rating;
                        for ($s = 1; $s <= 5; $s = $s + 1) {
                            if ($s <= $r) {
                                echo "<span class='bubble filled'>●</span>";
                            } else {
                                echo "<span class='bubble empty'>●</span>";
                            }
                        }
                        ?>
                        <span style="font-size:14px;margin-left:6px;font-weight:600;color:#6B7280;"><?php echo $rating; ?>/5.0</span>
                    </div>

                    <p style="font-weight:bold;color:#7C3AED;font-size:20px;margin-bottom:0;"><?php echo $price; ?></p>
                </div>

                <!-- link to destination page -->
                <a href="destination.php?id=<?php echo ($img - 1); ?>" class="more-info-btn btn">
                    More Info
                </a>
            </div>
            <?php } ?>
        </div>

    </div>

</div>

<!-- footer -->
<?php require_once "C:/xampp/htdocs/WWW/otherreqs/footerdetails.php"; ?>

</body>
</html>
