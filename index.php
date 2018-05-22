<?php
//Configs
$display_refresh = 5; // Display refresh, number of seconds to display each group of pdfs
$main_page_refresh = 5; // Main page refresh, number of seconds to display the main page before loading display page.
$group_count = 3; // Number of PDFs to display per page.

// Start the session
session_start();
// Set session variables
$_SESSION["refresh"] = $display_refresh;
$_SESSION["group_count"] = $group_count;

//path to directory to scan. i have included a wildcard for a subdirectory
$directory = "pdfshare/";

//get all image files with a .pdf extension.
$images = glob("" . $directory . "*.pdf");

$imgs = '';
// create array
foreach($images as $image){ $imgs[] = "$image"; }

// Set session variables
$_SESSION["img_array"] = $imgs;

echo 'Getting array of PDFs, Please stand by .....<br>';
echo 'This was just a place holder message, you dont get the \'wait for loading... at the 10 o\'clock at night';
header("Refresh:$main_page_refresh; url=display.php");
?> 