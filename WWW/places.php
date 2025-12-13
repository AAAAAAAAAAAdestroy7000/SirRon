<?php
require_once 'notes.php';
$hotels = array(
    array("name"=>"Okada Manila","city"=>"Parañaque, Metro Manila","address"=>"New Seaside Drive, Entertainment City, Parañaque, Metro Manila 1701","price"=>"₱19,500","rating"=>4.5,"img"=>"1","notes"=>$notes[0]),
    array("name"=>"Shangri-La The Fort","city"=>"Taguig, Metro Manila","address"=>"30th Street corner 5th Avenue, Bonifacio Global City, Taguig 1634","price"=>"₱21,500","rating"=>4.7,"img"=>"2","notes"=>$notes[1]),
    array("name"=>"Sofitel Philippine Plaza","city"=>"Pasay, Metro Manila","address"=>"CCP Complex, Roxas Boulevard, Pasay 1300","price"=>"₱17,000","rating"=>4.4,"img"=>"3","notes"=>$notes[2]),
    array("name"=>"The Peninsula Manila","city"=>"Makati, Metro Manila","address"=>"Corner of Ayala and Makati Avenues, Makati 1226","price"=>"₱23,000","rating"=>4.8,"img"=>"4","notes"=>$notes[3]),
    array("name"=>"Discovery Primea","city"=>"Makati, Metro Manila","address"=>"6749 Ayala Avenue, Makati 1226","price"=>"₱20,000","rating"=>4.6,"img"=>"5","notes"=>$notes[4]),
    array("name"=>"Crimson Resort and Spa Mactan","city"=>"Lapu-Lapu City, Cebu","address"=>"Seascapes Resort Town, Mactan Island, Lapu-Lapu City 6015","price"=>"₱18,000","rating"=>4.5,"img"=>"6","notes"=>$notes[5]),
    array("name"=>"Shangri-La Mactan","city"=>"Lapu-Lapu City, Cebu","address"=>"Punta Engaño Road, Lapu-Lapu City 6015","price"=>"₱21,500","rating"=>4.7,"img"=>"7","notes"=>$notes[6]),
    array("name"=>"Henann Regency Resort & Spa","city"=>"Boracay Island, Aklan","address"=>"Station 2, Beachfront, Boracay Island, Malay, Aklan 5608","price"=>"₱12,000","rating"=>4.3,"img"=>"8","notes"=>$notes[7]),
    array("name"=>"Amorita Resort","city"=>"Panglao, Bohol","address"=>"Alona Beach, Tawala, Panglao, Bohol 6340","price"=>"₱17,000","rating"=>4.6,"img"=>"9","notes"=>$notes[8]),
    array("name"=>"The Bellevue Resort","city"=>"Panglao, Bohol","address"=>"Barangay Doljo, Panglao, Bohol 6340","price"=>"₱14,000","rating"=>4.5,"img"=>"10","notes"=>$notes[9])
);
$restaurants = array(
    array("name"=>"Spiral at Sofitel","city"=>"Pasay, Metro Manila","address"=>"Sofitel Philippine Plaza, CCP Complex, Roxas Blvd, Pasay","price"=>"₱4,250","rating"=>4.6,"img"=>"11","notes"=>$notes[10]),
    array("name"=>"Antonio's","city"=>"Tagaytay, Cavite","address"=>"Barangay Neogan, Tagaytay City","price"=>"₱5,000","rating"=>4.8,"img"=>"12","notes"=>$notes[11]),
    array("name"=>"Toyo Eatery","city"=>"Makati, Metro Manila","address"=>"The Alley at Karrivin, 2316 Chino Roces Ave, Makati","price"=>"₱4,000","rating"=>4.7,"img"=>"13","notes"=>$notes[12]),
    array("name"=>"Gallery by Chele","city"=>"Taguig, Metro Manila","address"=>"5th Ave corner 30th St, BGC, Taguig","price"=>"₱6,500","rating"=>4.6,"img"=>"14","notes"=>$notes[13]),
    array("name"=>"Manam Comfort Filipino","city"=>"Multiple Locations","address"=>"Various branches","price"=>"₱850","rating"=>4.5,"img"=>"15","notes"=>$notes[14]),
    array("name"=>"Cafe Ilang-Ilang","city"=>"Manila","address"=>"Manila Hotel, One Rizal Park","price"=>"₱3,650","rating"=>4.4,"img"=>"16","notes"=>$notes[15]),
    array("name"=>"Vikings Luxury Buffet","city"=>"Multiple Locations","address"=>"SM Mall of Asia, SM North, etc.","price"=>"₱1,138","rating"=>4.3,"img"=>"17","notes"=>$notes[16]),
    array("name"=>"Harbor View Restaurant","city"=>"Manila","address"=>"South Harbor, Roxas Blvd","price"=>"₱2,250","rating"=>4.5,"img"=>"18","notes"=>$notes[17]),
    array("name"=>"Romulo Cafe","city"=>"Quezon City","address"=>"64 Scout Tuason","price"=>"₱1,300","rating"=>4.4,"img"=>"19","notes"=>$notes[18]),
    array("name"=>"Abe's Farm","city"=>"Mabalacat, Pampanga","address"=>"Magalang","price"=>"₱1,850","rating"=>4.6,"img"=>"20","notes"=>$notes[19])
);
$activities = array(
    array("name"=>"Underground River Tour","city"=>"Puerto Princesa","address"=>"Sabang Wharf","price"=>"₱1,750","rating"=>4.8,"img"=>"21","notes"=>$notes[20]),
    array("name"=>"Island Hopping El Nido","city"=>"El Nido","address"=>"Town Proper","price"=>"₱1,400","rating"=>4.7,"img"=>"22","notes"=>$notes[21]),
    array("name"=>"Chocolate Hills Tour","city"=>"Carmen, Bohol","address"=>"Chocolate Hills Complex","price"=>"₱2,150","rating"=>4.6,"img"=>"23","notes"=>$notes[22]),
    array("name"=>"Kawasan Canyoneering","city"=>"Badian, Cebu","address"=>"Kawasan Falls","price"=>"₱1,750","rating"=>4.8,"img"=>"24","notes"=>$notes[23]),
    array("name"=>"Taal Volcano Trek","city"=>"Talisay, Batangas","address"=>"Lake Shore","price"=>"₱3,000","rating"=>4.4,"img"=>"25","notes"=>$notes[24]),
    array("name"=>"Siargao Island Hopping","city"=>"General Luna","address"=>"Cloud 9 Boardwalk","price"=>"₱1,750","rating"=>4.7,"img"=>"26","notes"=>$notes[25]),
    array("name"=>"Coron Island Tour","city"=>"Coron","address"=>"Town Proper","price"=>"₱1,750","rating"=>4.8,"img"=>"27","notes"=>$notes[26]),
    array("name"=>"Oslob Whale Shark Watching","city"=>"Oslob, Cebu","address"=>"Tan-awan","price"=>"₱1,250","rating"=>4.2,"img"=>"28","notes"=>$notes[27]),
    array("name"=>"Vigan Heritage Tour","city"=>"Vigan","address"=>"Calle Crisologo","price"=>"₱850","rating"=>4.6,"img"=>"29","notes"=>$notes[28]),
    array("name"=>"Banaue Rice Terraces Trip","city"=>"Banaue, Ifugao","address"=>"Viewpoint","price"=>"₱3,250","rating"=>4.7,"img"=>"30","notes"=>$notes[29])
);
?>
