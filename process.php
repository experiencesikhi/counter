<?php
$operation = $_GET["operation"];
$counter = $_GET["counter"];
$id = $_GET["id"];
$mainPath = './counterdir';

$path = $mainPath.'/'.$counter;
$myfile = fopen($path, "r") or die("Unable to open file!");
$count = intval(fread($myfile,filesize($path)));
fclose($myfile);
//SUBTRACT
if ($operation == 0) {
		if ($count > 0)
		{
			$count--;
		}
} 
// ADD
if ($operation == 1) {
	$count++;
}
$myfile = fopen($path, "w") or die("Unable to open file!");
fwrite($myfile, (string)$count);
fclose($myfile);


$page = $_SERVER['PHP_SELF'];
$sec = "0";
header("Refresh: $sec; url=./index.php?active=".$id);

?>