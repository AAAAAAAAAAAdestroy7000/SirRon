<?php
require_once "places.php";
$items = array();
$index = 0;
$total = count($hotels);
for ($i = 0; $i < $total; $i = $i + 1) {
    $items[$index] = $hotels[$i];
    $index = $index + 1;
}
$total = count($restaurants);
for ($i = 0; $i < $total; $i = $i + 1) {
    $items[$index] = $restaurants[$i];
    $index = $index + 1;
}
$total = count($activities);
for ($i = 0; $i < $total; $i = $i + 1) {
    $items[$index] = $activities[$i];
    $index = $index + 1;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Homepage</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 text-end p-3">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-secondary">Sign Up</a>
        </div>
    </div>
</div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-6">
            <input type="text" class="form-control" placeholder="Search places">
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="row">
<?php
$total = count($items);
for ($i = 0; $i < $total; $i = $i + 1) {
    $name = $items[$i]["name"];
    $city = $items[$i]["city"];
    $price = $items[$i]["price"];
    $img = $items[$i]["img"];
?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="images/<?php echo $img; ?>.jpg"
                     class="card-img-top"
                     style="height:200px;object-fit:cover;">

                <div class="card-body">
                    <h5 class="card-title"><?php echo $name; ?></h5>
                    <p class="text-muted"><?php echo $city; ?></p>
                    <p class="fw-bold"><?php echo $price; ?></p>
                </div>
            </div>
        </div>
<?php
}
?>
    </div>
</div>
</body>
</html>
