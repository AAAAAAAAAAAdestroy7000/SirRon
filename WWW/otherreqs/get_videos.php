<?php
// set header to JSON
header('Content-Type: application/json');

$directory = '../videos/';
$videos = [];
$i = 1;

// loop 1 to 100 until video runs out.
while ($i < 100) {
    // check for videos.
    if (file_exists($directory . $i . '.mp4')) {
        $videos[] = $i . '.mp4';
        $i++;
    } 
    else {
        break;
    }
}

echo json_encode($videos);
?>
