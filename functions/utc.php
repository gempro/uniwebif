<?php 
//UTC
$us_date = date("m.d.Y");
$hour = date("H");
$minute = date("i");

if ($hour < 12 or $hour == 24 ){ $time = 'AM'; 
$hour = str_replace("24", "00", $hour);
$hour = str_replace("01", "1", $hour);
$hour = str_replace("02", "2", $hour);
$hour = str_replace("03", "3", $hour);
$hour = str_replace("04", "4", $hour);
$hour = str_replace("05", "5", $hour);
$hour = str_replace("06", "6", $hour);
$hour = str_replace("07", "7", $hour);
$hour = str_replace("08", "8", $hour);
$hour = str_replace("09", "9", $hour);
$us_start_date = $us_date. " " .$hour. ":" .$minute. " " .$time. "";
echo $us_start_date;
}

if ($hour > 12 or $hour == 12){ $time = 'PM'; 
$hour = str_replace("13", "1", $hour);
$hour = str_replace("14", "2", $hour);
$hour = str_replace("15", "3", $hour);
$hour = str_replace("16", "4", $hour);
$hour = str_replace("17", "5", $hour);
$hour = str_replace("18", "6", $hour);
$hour = str_replace("19", "7", $hour);
$hour = str_replace("20", "8", $hour);
$hour = str_replace("21", "9", $hour);
$hour = str_replace("22", "10", $hour);
$hour = str_replace("23", "11", $hour);
$us_start_date = $us_date. " " .$hour. ":" .$minute. " " .$time. "";
echo $us_start_date;
}
?>