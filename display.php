<?php
// Start the session
session_start();

$img_array = $_SESSION["img_array"];
$refresh = $_SESSION['refresh'];

//select first 3 images in array
$imgs = array_slice($img_array, 0, $_SESSION["group_count"]);

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
  $page='display.php'; 
 }  else { 
 $page='index.php';  
}
header("Refresh:$refresh; url=$page");
?>

