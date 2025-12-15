<?php
// loads all place data such as hotels, restaurants, and activities
require_once "../otherreqs/places.php";

// variables used to store search input from the URL
$query = "";
$type = "";

// reads the search keyword if it exists
if (@$_GET["q"] != "") {
    $query = $_GET["q"];
}

// reads the selected category (hotels, restaurants, attractions)
if (@$_GET["type"] != "") {
    $type = $_GET["type"];
}

// array that will store all items that match the search
$displayed = array();
$index = 0;

// function used to check if a place name matches the search keyword
// returns 1 if it matches, and 0 if it does not
function matchName($text, $query) {

    // if there is no search keyword, everything is allowed
    if ($query == "") {
        return 1;
    }

    // converts both text and query to lowercase for fair comparison
    $text = strtolower($text);
    $query = strtolower($query);

    // checks if the query exists inside the text
    $pos = strpos($text, $query);

    // if the word is not found, return false (0)
    if ($pos === false) {
        return 0;
    }

    // if found, return true (1)
    return 1;
}

// if the user selected hotels only
if ($type == "hotels") {

    $total = count($hotels);

    for ($i = 0; $i < $total; $i = $i + 1) {
        if (matchName($hotels[$i]["name"], $query) == 1) {
            $displayed[$index] = $hotels[$i];
            $index = $index + 1;
        }
    }

}
// if the user selected restaurants only
else if ($type == "restaurants") {

    $total = count($restaurants);

    for ($i = 0; $i < $total; $i = $i + 1) {
        if (matchName($restaurants[$i]["name"], $query) == 1) {
            $displayed[$index] = $restaurants[$i];
            $index = $index + 1;
        }
    }

}
// if the user selected attractions only
else if ($type == "attractions") {

    $total = count($activities);

    for ($i = 0; $i < $total; $i = $i + 1) {
        if (matchName($activities[$i]["name"], $query) == 1) {
            $displayed[$index] = $activities[$i];
            $index = $index + 1;
        }
    }

}
// if no category is selected, search through all categories
else {

    $total = count($hotels);
    for ($i = 0; $i < $total; $i = $i + 1) {
        if (matchName($hotels[$i]["name"], $query) == 1) {
            $displayed[$index] = $hotels[$i];
            $index = $index + 1;
        }
    }

    $total = count($restaurants);
    for ($i = 0; $i < $total; $i = $i + 1) {
        if (matchName($restaurants[$i]["name"], $query) == 1) {
            $displayed[$index] = $restaurants[$i];
            $index = $index + 1;
        }
    }

    $total = count($activities);
    for ($i = 0; $i < $total; $i = $i + 1) {
        if (matchName($activities[$i]["name"], $query) == 1) {
            $displayed[$index] = $activities[$i];
            $index = $index + 1;
        }
    }

}
?>
