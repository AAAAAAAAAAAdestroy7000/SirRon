<?php
require_once "../otherreqs/places.php";
$query="";
$type="";
if (@$_GET["q"]!="") { $query=$_GET["q"]; }
if (@$_GET["type"]!="") { $type=$_GET["type"]; }
$displayed=array();
$index=0;
function matchName($text,$query){
if ($query=="") { return 1; }
$text=strtolower($text);
$query=strtolower($query);
$pos=strpos($text,$query);
if ($pos===false) { return 0; }
return 1;
}
if ($type=="hotels") {
$total=count($hotels);
for ($i=0;$i<$total;$i=$i+1){
if (matchName($hotels[$i]["name"],$query)==1){
$displayed[$index]=$hotels[$i];
$index=$index+1;
}
}
}
else if ($type=="restaurants") {
$total=count($restaurants);
for ($i=0;$i<$total;$i=$i+1){
if (matchName($restaurants[$i]["name"],$query)==1){
$displayed[$index]=$restaurants[$i];
$index=$index+1;
}
}
}
else if ($type=="attractions") {
$total=count($activities);
for ($i=0;$i<$total;$i=$i+1){
if (matchName($activities[$i]["name"],$query)==1){
$displayed[$index]=$activities[$i];
$index=$index+1;
}
}
}
else {
$total=count($hotels);
for ($i=0;$i<$total;$i=$i+1){
if (matchName($hotels[$i]["name"],$query)==1){
$displayed[$index]=$hotels[$i];
$index=$index+1;
}
}
$total=count($restaurants);
for ($i=0;$i<$total;$i=$i+1){
if (matchName($restaurants[$i]["name"],$query)==1){
$displayed[$index]=$restaurants[$i];
$index=$index+1;
}
}
$total=count($activities);
for ($i=0;$i<$total;$i=$i+1){
if (matchName($activities[$i]["name"],$query)==1){
$displayed[$index]=$activities[$i];
$index=$index+1;
}
}
}
?>
