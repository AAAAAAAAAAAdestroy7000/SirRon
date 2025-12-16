<?php
session_start();
// I used @ instead to suppress error messages
// this ensures userid and username always exist even if the session is new
if (@$_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
}

if (@$_SESSION["username"] == "") {
    $_SESSION["username"] = "";
}

// this extra check makes sure no leftover username exists if userid is empty
if ($_SESSION["userid"] == "") {
    $_SESSION["userid"] = "";
    $_SESSION["username"] = "";
}

// places.php contains all the hotels, restaurants, and activities arrays
require_once "../otherreqs/places.php";

// featured destination is always the first hotel
$featured = $hotels[0];

// prepare top 5 items for each category
// these are constant lists, not randomized
$topHotels = array();
for ($i = 0; $i < 5; $i = $i + 1) {
    $topHotels[$i] = $hotels[$i];
}

$topActivities = array();
for ($i = 0; $i < 5; $i = $i + 1) {
    $topActivities[$i] = $activities[$i];
}

$topRestaurants = array();
for ($i = 0; $i < 5; $i = $i + 1) {
    $topRestaurants[$i] = $restaurants[$i];
}

// combine all arrays for random selection
// this creates one big array with all destinations
$allPlaces = array();
$allIndex = 0;

$hotelCount = count($hotels);
for ($i = 0; $i < $hotelCount; $i = $i + 1) {
    $allPlaces[$allIndex] = $hotels[$i];
    $allIndex = $allIndex + 1;
}

$activityCount = count($activities);
for ($i = 0; $i < $activityCount; $i = $i + 1) {
    $allPlaces[$allIndex] = $activities[$i];
    $allIndex = $allIndex + 1;
}

$restaurantCount = count($restaurants);
for ($i = 0; $i < $restaurantCount; $i = $i + 1) {
    $allPlaces[$allIndex] = $restaurants[$i];
    $allIndex = $allIndex + 1;
}

// simple shuffle logic using manual swapping
// this randomizes the order of all places
$totalPlaces = count($allPlaces);
for ($i = 0; $i < $totalPlaces; $i = $i + 1) {
    // pick a random index to swap with
    $randomIndex = rand(0, $totalPlaces - 1);
    
    // swap current item with random item
    $temp = $allPlaces[$i];
    $allPlaces[$i] = $allPlaces[$randomIndex];
    $allPlaces[$randomIndex] = $temp;
}

// take first 6 items from shuffled array for display
$randomPlaces = array();
for ($i = 0; $i < 6; $i = $i + 1) {
    $randomPlaces[$i] = $allPlaces[$i];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GalaExtremists - Discover Amazing Destinations</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/mobile_fix.css">

</head>

<body>

<!-- main navigation bar that stays consistent across pages -->
<?php require_once "../otherreqs/navigationbar.php"; ?>

<!-- hero section with slideshow images -->
<div class="hero text-white text-center">

    <!-- background images rotate using JavaScript -->
    <div class="slide active" style="background-image:url('../images/pic1.jpg')"></div>
    <div class="slide" style="background-image:url('../images/pic2.jpg')"></div>
    <div class="slide" style="background-image:url('../images/pic3.jpg')"></div>
    <div class="slide" style="background-image:url('../images/pic4.jpg')"></div>
    <div class="slide" style="background-image:url('../images/pic5.jpg')"></div>

    <div class="container position-relative">

        <!-- main headline text -->
        <h1>
            Explore the world with us
        </h1>

        <p>
            Discover unforgettable destinations and create new memories
        </p>

    </div>
</div>

<!-- featured destination card -->
<div class="container my-5">
    <div class="featured-card">
        
        <!-- left side shows large image -->
        <div class="featured-image">
            <img src="../images/<?php echo $featured["img"]; ?>.jpg" 
                 onerror="this.src='../images/<?php echo $featured["img"]; ?>.png';">
        </div>

        <!-- right side shows details and book button -->
        <div class="featured-content">
            <h2><?php echo $featured["name"]; ?></h2>
            <p class="featured-location"><?php echo $featured["city"]; ?></p>
            <p class="featured-description"><?php echo $featured["notes"]; ?></p>
            <p class="featured-price"><?php echo $featured["price"]; ?></p>
            <a href="destination.php?id=<?php echo ($featured["img"] - 1); ?>" class="featured-btn">
                view details
            </a>
        </div>

    </div>
</div>

<!-- two-column layout: left = random destinations, right = top category -->
<div class="container my-5">
    <div class="two-column-layout">
        
        <!-- left column: random destinations grid -->
        <div class="left-column">
            <h2 class="section-title">Featured Destinations</h2>

            <div class="results">
                <?php
                // loop through the 6 random places
                for ($i = 0; $i < 6; $i = $i + 1) {

                    // each item represents one random place
                    $item = $randomPlaces[$i];
                    $name = $item["name"];
                    $city = $item["city"];
                    $price = $item["price"];
                    $img = $item["img"];
                ?>
                <div class="card shadow-sm border-0 position-relative">

                    <!-- image fallback uses onerror since PHP cannot detect load failure -->
                    <img src="../images/<?php echo $img; ?>.jpg"
                         onerror="this.src='../images/<?php echo $img; ?>.png';"
                         class="card-img-top">

                    <!-- basic place details -->
                    <div class="card-body pb-5">
                        <h5><?php echo $name; ?></h5>
                        <p class="text-muted"><?php echo $city; ?></p>
                        <p style="font-weight:bold;color:#7C3AED;"><?php echo $price; ?></p>
                    </div>

                    <!-- redirects to destination page using image index -->
                    <a href="destination.php?id=<?php echo ($img - 1); ?>" class="more-info-btn btn">
                        More Info
                    </a>

                </div>
                <?php } ?>
            </div>
        </div>

        <!-- right column: top category vertical list -->
        <div class="right-column">
            <h2 class="section-title">Top Destinations</h2>

            <!-- category tabs -->
            <div class="category-tabs-vertical">
                <a href="javascript:void(0)" onclick="showCategory('hotels')" class="tab-btn-vertical active" id="btn-hotels">
                    Hotels
                </a>
                <a href="javascript:void(0)" onclick="showCategory('activities')" class="tab-btn-vertical" id="btn-activities">
                    Activities
                </a>
                <a href="javascript:void(0)" onclick="showCategory('restaurants')" class="tab-btn-vertical" id="btn-restaurants">
                    Restaurants
                </a>
            </div>

            <!-- vertical list of top items - HOTELS -->
            <div class="top-list-vertical" id="list-hotels">
                <?php
                for ($i = 0; $i < 5; $i = $i + 1) {
                    $item = $topHotels[$i];
                    $rank = $i + 1;
                ?>
                <a href="destination.php?id=<?php echo ($item["img"] - 1); ?>" class="top-item-horizontal" style="text-decoration:none;">
                    <div class="rank-number"><?php echo $rank; ?></div>
                    <img src="../images/<?php echo $item["img"]; ?>.jpg" 
                         onerror="this.src='../images/<?php echo $item["img"]; ?>.png';">
                    <div class="top-item-details">
                        <h5><?php echo $item["name"]; ?></h5>
                        <p><?php echo $item["city"]; ?></p>
                    </div>
                </a>
                <?php } ?>
            </div>

            <!-- vertical list of top items - ACTIVITIES -->
            <div class="top-list-vertical" id="list-activities" style="display:none;">
                <?php
                for ($i = 0; $i < 5; $i = $i + 1) {
                    $item = $topActivities[$i];
                    $rank = $i + 1;
                ?>
                <a href="destination.php?id=<?php echo ($item["img"] - 1); ?>" class="top-item-horizontal" style="text-decoration:none;">
                    <div class="rank-number"><?php echo $rank; ?></div>
                    <img src="../images/<?php echo $item["img"]; ?>.jpg" 
                         onerror="this.src='../images/<?php echo $item["img"]; ?>.png';">
                    <div class="top-item-details">
                        <h5><?php echo $item["name"]; ?></h5>
                        <p><?php echo $item["city"]; ?></p>
                    </div>
                </a>
                <?php } ?>
            </div>

            <!-- vertical list of top items - RESTAURANTS -->
            <div class="top-list-vertical" id="list-restaurants" style="display:none;">
                <?php
                for ($i = 0; $i < 5; $i = $i + 1) {
                    $item = $topRestaurants[$i];
                    $rank = $i + 1;
                ?>
                <a href="destination.php?id=<?php echo ($item["img"] - 1); ?>" class="top-item-horizontal" style="text-decoration:none;">
                    <div class="rank-number"><?php echo $rank; ?></div>
                    <img src="../images/<?php echo $item["img"]; ?>.jpg" 
                         onerror="this.src='../images/<?php echo $item["img"]; ?>.png';">
                    <div class="top-item-details">
                        <h5><?php echo $item["name"]; ?></h5>
                        <p><?php echo $item["city"]; ?></p>
                    </div>
                </a>
                <?php } ?>
            </div>

            <script>
            function showCategory(category) {
                // hide all lists
                document.getElementById('list-hotels').style.display = 'none';
                document.getElementById('list-activities').style.display = 'none';
                document.getElementById('list-restaurants').style.display = 'none';

                // remove active class from all buttons
                document.getElementById('btn-hotels').classList.remove('active');
                document.getElementById('btn-activities').classList.remove('active');
                document.getElementById('btn-restaurants').classList.remove('active');

                // show selected list
                document.getElementById('list-' + category).style.display = 'flex';

                // add active class to clicked button
                document.getElementById('btn-' + category).classList.add('active');
            }
            </script>

        </div>

    </div>
</div>

<!-- popular hotels carousel -->
<div class="container my-5">
    <h2 class="section-title">Popular Hotels</h2>
    <div class="carousel-container">
        <?php
        // show first 8 hotels in horizontal scroll
        $hotelCount = count($hotels);
        $displayCount = 8;
        if ($hotelCount < 8) {
            $displayCount = $hotelCount;
        }
        
        for ($i = 0; $i < $displayCount; $i = $i + 1) {
            $hotel = $hotels[$i];
        ?>
        <div class="carousel-card">
            <img src="../images/<?php echo $hotel["img"]; ?>.jpg" 
                 onerror="this.src='../images/<?php echo $hotel["img"]; ?>.png';">
            <div class="carousel-card-body">
                <h5><?php echo $hotel["name"]; ?></h5>
                <p class="location"><?php echo $hotel["city"]; ?></p>
                <p class="price"><?php echo $hotel["price"]; ?></p>
                <a href="destination.php?id=<?php echo ($hotel["img"] - 1); ?>" class="carousel-link">
                    Details
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<!-- best activities carousel -->
<div class="container my-5">
    <h2 class="section-title">Best Activities</h2>
    <div class="carousel-container">
        <?php
        // show first 8 activities in horizontal scroll
        $activityCount = count($activities);
        $displayCount = 8;
        if ($activityCount < 8) {
            $displayCount = $activityCount;
        }
        
        for ($i = 0; $i < $displayCount; $i = $i + 1) {
            $activity = $activities[$i];
        ?>
        <div class="carousel-card">
            <img src="../images/<?php echo $activity["img"]; ?>.jpg" 
                 onerror="this.src='../images/<?php echo $activity["img"]; ?>.png';">
            <div class="carousel-card-body">
                <h5><?php echo $activity["name"]; ?></h5>
                <p class="location"><?php echo $activity["city"]; ?></p>
                <p class="price"><?php echo $activity["price"]; ?></p>
                <a href="destination.php?id=<?php echo ($activity["img"] - 1); ?>" class="carousel-link">
                    Details
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<!-- top-rated restaurants carousel -->
<div class="container my-5">
    <h2 class="section-title">Top-Rated Restaurants</h2>
    <div class="carousel-container">
        <?php
        // show first 8 restaurants in horizontal scroll
        $restaurantCount = count($restaurants);
        $displayCount = 8;
        if ($restaurantCount < 8) {
            $displayCount = $restaurantCount;
        }
        
        for ($i = 0; $i < $displayCount; $i = $i + 1) {
            $restaurant = $restaurants[$i];
        ?>
        <div class="carousel-card">
            <img src="../images/<?php echo $restaurant["img"]; ?>.jpg" 
                 onerror="this.src='../images/<?php echo $restaurant["img"]; ?>.png';">
            <div class="carousel-card-body">
                <h5><?php echo $restaurant["name"]; ?></h5>
                <p class="location"><?php echo $restaurant["city"]; ?></p>
                <p class="price"><?php echo $restaurant["price"]; ?></p>
                <a href="destination.php?id=<?php echo ($restaurant["img"] - 1); ?>" class="carousel-link">
                    Details
                </a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>


<!-- footer -->
<?php require_once "C:/xampp/htdocs/WWW/otherreqs/footerdetails.php"; ?>

<!-- scripts for slideshow and Bootstrap -->
<script src="../js/scripts.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
