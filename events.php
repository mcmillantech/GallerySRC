<?php
// ------------------------------------------------------
//  Project	OnLIne Gallery
//  File	events.php
//		Lists of events
//
//  Parameter
//		Mode - exhibitions, other events
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
function showLeftPanel($stream)
function showFutureEvent($event)
function showMainPanel($stream)
function showCurrentEvent($event)
*/

	session_start();
	require_once "common.php";
	require "top2.php";

	$mysqli = dbConnect($config);
	$dta = array();
?>
<?php
	$stream = $_GET['mode'];
	
	$title = ucwords($stream);
	$dta["stream"] = "$stream";

	showTop("Art by Lupe Cunha - $stream", $title);

	showLeftPanel($title);
	showMainPanel($stream);

	$dta['footer'] = footer();
//print_r($dta);
	showView("events.html", $dta);

// ----------------------------------------------
//	Show the left panel forthcoming events
//
// ----------------------------------------------
function showLeftPanel($stream)
{
	global $mysqli, $dta;

	$list = array();
	
	date_default_timezone_set("Europe/London");	// Today's date
	$dt = date('Y-m-d');

	$sql = "SELECT * FROM events WHERE stream='$stream' AND date1>= '$dt' ORDER BY date1";
	$result = $mysqli->query($sql)
		or die("Error reading events table" . mysqli_error($mysqli));
	if (mysqli_num_rows($result) < 1)		// A status to display no records
		$dta['forthRows'] = "No forthcoming $stream<br>";
	else $dta['forthRows'] = "";

	while ($event = mysqli_fetch_array($result, MYSQLI_ASSOC))
	{
		$item = showFutureEvent($event);
		array_push($list, $item);
	}

	$dta["leftPanel"] = $list;
}

// ----------------------------------------------
//	Display one future event
//
//	The main image, dates and other information
//
//	Parameter	Event record from the table
//
//	Returns		Array of data to display
// ----------------------------------------------
function showFutureEvent($event)
{
	global $impath;

	$event['image1'] = $impath . '/small/' . $event['image1'];
	$event['dates'] = dispDate($event['date1']) . " to " . dispDate($event['date2']);
	
	return $event;
}

// ----------------------------------------------
//	Show the current events in the main panel
//
// ----------------------------------------------
function showMainPanel($stream)
{
	global $mysqli, $dta;

	date_default_timezone_set("Europe/London");	// Today's date
	$dt = date('Y-m-d');
	$list = array();				// To hold list of events

	$sql = "SELECT * FROM events WHERE stream='$stream' AND date1 < '$dt' ORDER BY date1 DESC";
	$result = $mysqli->query($sql)
		or die("Error reading events table" . mysqli_error($mysqli));
	while ($event = mysqli_fetch_array($result, MYSQLI_ASSOC))
	{
		$item = showCurrentEvent($event, $stream);
		array_push($list, $item);
	}

	$dta["mainPanel"] = $list;		// Add the list of events to the output
}

// ----------------------------------------------
//	Show one current or past event
//
//	Parameter	SQL event array
//
//	Returns		Event modified for display
// ----------------------------------------------
function showCurrentEvent($event, $stream)
{
	global $impath;

	if ($stream == "exhibitions" || $stream == "workshops")
	{
		$event['picClick'] = "onClick='window.location=\"eventenlarge.php?stream=$stream&image="
			. $event['image1'] . "\"'";
	}
	else
		$event['picClick'] = '';
	
	$event['image1'] = $impath . '/small/' .$event['image1'];
	$event['image2'] = $impath . '/small/' .$event['image2'];
	$event['image3'] = $impath . '/small/' .$event['image3'];
/*	$event['style1'] = imageFit($event['image1']);
	$event['style2'] = imageFit($event['image2']);
	$event['style3'] = imageFit($event['image3']);
*/
	$date1 = dispDate($event['date1']);
	$date2 = dispDate($event['date2']);
	$event['dateStart'] = $date1;
	$year = $event['year'];
	$times = $event['times'];
	$location = $event['location'];
	$text = $event['text'];

	if (strlen($event['image2']) > 15)
		$event['display2'] = "block";
	else
		$event['display2'] = "none";

	if (strlen($event['image3']) > 15)
		$event['display3'] = "block";
	else 
		$event['display3'] = "none";

	if ($date2 == '')
		$event['dates'] = "$date1";
	else
		$event['dates'] = "From $date1 to $date2";

	$event['address'] = textToHTML($event['address']);
//print_r($event);

	return $event;
}

?>
