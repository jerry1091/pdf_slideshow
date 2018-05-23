<?php
//Configs
$default_display_refresh = 5; // Default display refresh, number of seconds to display each group of pdfs if not giving in url.
$main_page_refresh = 5; // Main page refresh, number of seconds to display the main page before loading display page.
$group_count = 1; // Number of PDFs to display per page.

session_start();

$file_name = basename(__FILE__);


if (isset($_GET['delay'])) {
    $display_refresh = $_GET['delay'];
}else{
    $display_refresh = $default_display_refresh;
}

if (isset($_GET['loc'])) {
    $session_name = $_GET['loc'];
    $url = "{$file_name}?loc={$_GET['loc']}&delay={$display_refresh}";
}else{
    $session_name = 'default';
    $url = "{$file_name}?delay={$display_refresh}";
}

// unset($GLOBALS['_SESSION']["{$session_name}"]); // Uncomment to clear session --- debug ---


function get_pdf_page_count($pdf_loc) {
$pdf_info = shell_exec("pdfinfo ".$pdf_loc);
$info_array1 = explode("\n",$pdf_info);
foreach ($info_array1 as $key => $value) {
    $info_array2 = explode(":",$value);
        if ($info_array2[0] == 'Pages') {
            $pdf_pages = $info_array2[1];
        }
}
// echo "Number of page in {$pdf_loc} is {$pdf_pages}";
return $pdf_pages;
}


function get_pdf_page_url($file_array) {
    foreach ($file_array as $img) {
        $pdf_url = "{$img['name']}#page={$img['page']}";
        $pdf_url_array[] = $pdf_url;
    }
    
return $pdf_url_array;
}

function get_pdf_loc($name) {
    switch ($name) {
    case 'dynamic':
        $directory = "pdfshare/dynamic/";
        break;
    case 'static':
        $directory = "pdfshare/static/";
        break;
    case 'stats':
        $directory = "pdfshare/stats/";
        break;
    default:
       $directory = "pdfshare/";

    }
return $directory;
}

if (isset($_SESSION["{$session_name}"])) {
    $img_array = $_SESSION["{$session_name}"];
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
//        echo "<BR><BR><BR><BR>File name {$img} <BR>\n"; // Used for debugging
//     echo "<object data=\"{$img}\" type=\"application/pdf\" width=\"33%\" height=\"690px\"></object>";
    echo "<object data=\"{$img}\" type=\"application/pdf\" width=\"100%\" height=\"100%\"></object>";
//     echo "<embed src='{$img}&toolbar=0&navpanes=0&scrollbar=0&statusbar=0&overflow=hidden&view=fit' type='application/pdf' border='0' width='33%' height='100%' /> ";
}
        ?>
    </body>
    </html>

    <?PHP
     $img_diff = array_diff($img_array, $imgs);

    $_SESSION["{$session_name}"] = $img_diff;

    $count = count($img_diff);

    if ($count > 0) {
            $_SESSION["{$session_name}"] = $img_diff;
        }else {
            unset($GLOBALS['_SESSION']["{$session_name}"]); 
        }
    header("refresh:$display_refresh; url=$url", FALSE, 307);

}else {   
    $directory = get_pdf_loc($_GET["loc"]);

    //get all image files with a .pdf extension.
    $files = glob("" . $directory . "*.[pP][dD][fF]");
    
    $file_array = '';
    // create array of filename/pages numbers
    foreach($files as $file){
        
        for ($x = 1; $x <= get_pdf_page_count($file); $x++) {
            $pdf_array['name'] = $file;
            $pdf_array['page'] = $x;
            $file_array["{$pdf_array['name']}-{$pdf_array['page']}"] = $pdf_array;
        } 
    }
            
    // Set session variables
    $_SESSION["{$session_name}"] = get_pdf_page_url($file_array);
    
    echo "<BR><BR><BR>Getting array of PDFs from ".get_pdf_loc($_GET['loc']).",<BR>\nRefresh set to {$display_refresh}<br>\n";
    header("refresh:$main_page_refresh; url=$url", FALSE, 307);
}
