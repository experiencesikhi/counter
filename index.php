<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Counters</title>
	
	<!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/main.css" rel="stylesheet">
	<script src="js/jquery-1.11.2.min.js"></script>
	<script src="js/bootstrap.js"></script>

</head>
<body>

<?php

// Read the config file, and save attributes.
$ini_array = parse_ini_file("config.ini", true);
$fontPath = $ini_array['global']['font'];
$maxCounter = $ini_array['global']['counterMax'];
global $dirPath;
$dirPath = $ini_array['global']['counterDir'];

// Get the number of files in the 'counterdir' directory, to find out how many tabs to create.
$num_files = getNumberOfCounters($dirPath);

if (isset($_GET['active'])) { 
	$active_page = $_GET['active'];
} else {
	$active_page = 0;
}

// Helper Functions //
function getCounter($counterName, $counterid) {
	$mainPath = $GLOBALS['dirPath'];
	$path = $mainPath.'/'.$counterName;
	$myfile = fopen($path, "r") or die("Unable to open file!");

	// Create a box and center it. CSS avaliable in the css/ folder
	$tabContent = '';
	$tabContent = $tabContent.'<div id="counterBox">';
	$tabContent = $tabContent.'<span id="name">'.$counterName.'</span>';
	$tabContent = $tabContent.'<br/>';
	$tabContent = $tabContent.'<span id="number">'.fread($myfile,filesize($path)).'</span>';
	$tabContent = $tabContent.'<div id="buttons">';
	$tabContent = $tabContent.'<a href="process.php?operation=1&counter='.$counterName.'&id='.$counterid.'" id="add"> Add </a>';
	$tabContent = $tabContent.'&nbsp;<a href="process.php?operation=0&counter='.$counterName.'&id='.$counterid.'" id="subtract"> Subtract </a>';
	$tabContent = $tabContent.'</div>';
	$tabContent = $tabContent."</div>";

	fclose($myfile);
	return $tabContent;
}

function getNumberOfCounters($path) {
	return (count(scandir($path)) - 2);
}

function getCounterNames($path) {
	$files = scandir($path);
	unset($files[0]);
	unset($files[1]);
	$files = array_values($files);
	return $files;
}

function liOutput($href,$tabname,$active) {
	if ($active == 1) {
		return '<li role="presentation" class="active col-md-1"><a href="#'.$href.'" aria-controls="'.$href.'" role="tab" data-toggle="tab">'.$tabname.'</a></li>';	
	} else {
		return '<li role="presentation" class="col-md-1"><a href="#'.$href.'" aria-controls="'.$href.'" role="tab" data-toggle="tab">'.$tabname.'</a></li>';	
	}
	
}

function tabOutput ($href,$tabContent,$active) {
	if ($active == 1) {
		return '<div role="tabpanel" class="tab-pane active" id="'.$href.'">'.$tabContent.'</div>';
	} else {
		return '<div role="tabpanel" class="tab-pane" id="'.$href.'">'.$tabContent.'</div>'; 
	}
}

 	

/** MAIN **/

$counterNames = getCounterNames($dirPath);
echo '<div id="main" class="container-fluid">';
  	/** TABS **/ 
	echo '<div class="row" role="tabpanel">';

  	// Navigation for the tabs
  	if ($num_files < 1) {

  		echo "No counters are created";

  	} else if ($num_files == 1){
  		
  		echo '<ul class="nav nav-tabs" role="tablist">';
  		echo liOutput($counterNames[0],$counterNames[0],1);
		echo "</ul>";

  	} else {
  
  		echo '<ul class="nav nav-tabs" role="tablist">';
  		
  		for ($i = 0; $i < $num_files; $i++) {
  			if ($active_page == $i) {
  				echo liOutput($counterNames[$i],$counterNames[$i],1);		
  			} else {
  				echo liOutput($counterNames[$i],$counterNames[$i],0);	
  			}
			
		} 
		echo "</ul>";
  		
  	}

	
	// Tab Content
  	if ($num_files < 1) {
  		
  	}
  	else if ($num_files == 1){
  		
  		echo '<div class="tab-content">';
  		echo tabOutput($counterNames[0],getCounter($counterNames[0], 0),1);
		echo "</div>";

  	} else {
  
  		echo '<div class="tab-content">';
  		
  		for ($j = 0; $j < $num_files; $j++) {
	  		if ($active_page == $j) {
	  			echo tabOutput($counterNames[$j],getCounter($counterNames[$j], $j),1);	
	  		} else {
				echo tabOutput($counterNames[$j],getCounter($counterNames[$j], $j),0);
	  		}
		} 
		echo "</div>";
  	}  
  
	echo "</div>";
echo "</div>";

?> 
    

</body>
</html>