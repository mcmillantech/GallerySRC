<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	eventedit.php
//		Edit current event
//
//  Parameters	
//		mode - ins or upd
//		id - index of item, upd mode only
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
protected function setNewItem()
protected function fetchItem()
protected function dispDate($dt)
protected function showForm($mode)
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Event Edit</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<script src="../Cunha.js"></script>
<script>
function showFileFrame()
{
	var el = document.getElementById("frame");
	el.style.visibility = "visible";
}

</script>
</head>
<body>
<?php
	session_start();
	include "adminmenus.php";
	require_once "../common.php";
	require_once "DataEdit.php";

class EvEdit extends DataEdit
{
	function __construct($mysqli)
	{
		parent::__construct($mysqli);
	}

	protected function setNewItem()
	{
		$this->record = array
		(
			'title' => '',
			'stream' => 'Exhibitions',
//			'mainimage' => '',
			'date1' => '',
			'date2' => '',
			'times' => '',
			'location' => '',
			'address' => '',
			'contact' => '',
			'image1' => '',
			'image2' => '',
			'image3' => '',
			'image4' => '',
			'image5' => '',
			'image6' => '',
			'text' => ''
		);
	}

// -------------------------------------------
//	Override of DataEdit virtual function
//
//	Reads event into class member record
// -------------------------------------------
	protected function fetchItem()
	{
		$id = $_GET['item'];
		$sql = "SELECT * FROM events WHERE id=$id";
		$result = $this->mysqli->query($sql)
			or die ("Error fetching item" . mysqli_error($this->mysqli));
		$this->record =  mysqli_fetch_array($result, MYSQLI_ASSOC);
						// Convert dates to display
		$date = $this->dispDate($this->record['date1']);
		$this->record['date1'] = $date;
		$date = $this->dispDate($this->record['date2']);
		$this->record['date2'] = $date;
	}

// -------------------------------------------
//	Convert SQL date to display format
//
// -------------------------------------------
	protected function dispDate($dt)
	{
		if ($dt == '')			// Null date
			return '';
		list($year, $mon, $day) = explode("-", $dt);
		$dtm = "$day/$mon/$year";
		return $dtm;
	}

// -------------------------------------------
//	Show the event edit form
//
// -------------------------------------------
	protected function showForm($mode)
	{
		$dta = $this->record;
		if ($mode=='ins')
			$action = "eventlist.php?mode=ins";
		else
		{
			$id = $_GET['item'];
			$action = "eventlist.php?mode=upd&item=$id";
		}
		$streams = array (
			'Exhibitions',
			'Workshops',
			'Other Events'
		);
		echo "<form method='post' action='$action'>";
			$this->showDropDown('Event type', $dta, 'stream', $streams);
			$this->showLine('Title', $dta, 'title', 60);
//			$this->showLine('Main Image File', $dta, 'mainimage', 60);
			$this->showLine('Start date (dd/mm/yyyy)', $dta, 'date1', 12);
			$this->showLine('End date', $dta, 'date2', 12);
			$this->showLine('Times', $dta, 'times', 30);
			$this->showLine('Location', $dta, 'location', 30);
			$this->textArea('Address', $dta, 'address', 4, 45);
			echo '<br>';
			$this->showLine('Contact', $dta, 'contact', 60);
			$this->showLine('Image Files', $dta, 'image1', 60);
			$this->showLine('', $dta, 'image2', 60);
			$this->showLine('', $dta, 'image3', 60);
			$this->showLine('', $dta, 'image4', 60);
			$this->showLine('', $dta, 'image5', 60);
			$this->showLine('', $dta, 'image6', 60);
			$this->textArea('Text', $dta, 'text', 4, 60);
			echo "<button type='submit'>Post</button>";
		echo "</form>\n";

		echo "<button onClick='showFileFrame()' "
			. "style='position:absolute; top:400px; left:700px;'>Pick</button>";
		echo "<div style='position:absolute; left:700px; top:400px; visibility: hidden'>";
//		echo "<div style='position:absolute; left:700px; top:480px; height:600px;'>";
		echo "<iframe id='frame' style='background-color:white; height:300px; width:500px;' "
			. "src='eventImageFrame.php?which=event'></iframe> ";
		echo "</div>";
		
	}
}

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	echo "<h3>Edit Event </h3>";
	$lst = new EvEdit($mysqli);
	$lst->run();
?>
</body>
</html>

