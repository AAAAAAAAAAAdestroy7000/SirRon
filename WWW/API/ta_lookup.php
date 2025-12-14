<?php
header("Content-Type: application/json; charset=utf-8");
$name = "";
if ($_GET["name"] != null) { $name = trim($_GET["name"]); }
if ($name == "") { echo json_encode(array("error"=>"Missing name")); exit; }

$apiKey = "CF117863AC60432ABABC13AFD193329E";
$referer = "https://abcdxd.com";
$userAgent = "GalaExtremists/1.0";

function curlGet($url,$params,$referer,$userAgent){
$fullUrl = $url."?".http_build_query($params);
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$fullUrl);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_HTTPHEADER,array(
"Referer: ".$referer,
"User-Agent: ".$userAgent,
"Accept: application/json"
));
$result = curl_exec($ch);
curl_close($ch);
return $result;
}

$searchUrl = "https://api.content.tripadvisor.com/api/v1/location/search";
$searchParams = array("key"=>$apiKey,"language"=>"en","searchQuery"=>$name);
$searchJson = curlGet($searchUrl,$searchParams,$referer,$userAgent);
$searchData = json_decode($searchJson,true);

if ($searchData == null || @$searchData["data"] == null) { echo json_encode(array("error"=>"No TripAdvisor result")); exit; }

$matchIndex = -1;
$dataList = $searchData["data"];
$count = count($dataList);

for ($i = 0; $i < $count; $i = $i + 1) {
$n = @$dataList[$i]["name"];
if ($n != null) {
if (strtolower($n) == strtolower($name)) { $matchIndex = $i; break; }
}
}

if ($matchIndex == -1 && $count > 0) { $matchIndex = 0; }

$taId = @$dataList[$matchIndex]["location_id"];
if ($taId == null) { echo json_encode(array("error"=>"No location id")); exit; }

$detailsUrl = "https://api.content.tripadvisor.com/api/v1/location/".$taId."/details";
$detailsParams = array("key"=>$apiKey,"language"=>"en");
$detailsJson = curlGet($detailsUrl,$detailsParams,$referer,$userAgent);
$detailsData = json_decode($detailsJson,true);

$rating = "";
$reviews = "";

if ($detailsData != null) {
if (@$detailsData["data"] != null) {
if (@$detailsData["data"]["rating"] != null) { $rating = $detailsData["data"]["rating"]; }
if (@$detailsData["data"]["num_reviews"] != null) { $reviews = $detailsData["data"]["num_reviews"]; }
} else {
if (@$detailsData["rating"] != null) { $rating = $detailsData["rating"]; }
if (@$detailsData["num_reviews"] != null) { $reviews = $detailsData["num_reviews"]; }
}
}

if ($rating == "") { echo json_encode(array("error"=>"No rating")); exit; }

echo json_encode(array("rating"=>$rating,"num_reviews"=>$reviews));
exit;
