<?php
//Configs
$display_refresh = 5; // Display refresh, number of seconds to display each group of pdfs
$main_page_refresh = 5; // Main page refresh, number of seconds to display the main page before loading display page.
$group_count = 3; // Number of PDFs to display per page.

session_start();

if (isset($_SESSION['img_array'])) {
    $img_array = $_SESSION['img_array'];
       //select first 3 images in array
    $imgs = array_slice($img_array, 0, $group_count);
    ?> 
    <html>
    <head>
    <style>
    .pdfobject { border: 0px; }
    </style>
       <title>Slideshow</title>
    </head>
    <body>
        <?PHP
         //display images
    foreach ($imgs as $img) {
        echo "<embed src='$img#toolbar=0&navpanes=0&scrollbar=0&statusbar=0&overflow=hidden&view=fit' type='application/pdf' border='0' width='33%' height='100%' /> ";
    }
        ?>
    </body>
    </html>

    <?PHP
     $img_diff = array_diff($img_array, $imgs);

    $_SESSION["img_array"] = $img_diff;

    $count = count($img_diff);

    if ($count > 0) {
            $_SESSION["img_array"] = $img_diff;
        }else {
            unset($GLOBALS[_SESSION]["img_array"]); 
        }
    header("Refresh:$display_refresh");

}else {
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
    header("Refresh:$main_page_refresh");
}
