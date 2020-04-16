<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	futureeventedit.php
//		Edit forthcoming event
//
//  Parameters	
//		mode - ins or upd
//		id - index of item, upd mode only
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------

?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Future Event</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<style>
</style>
</head>
<body>
<?php
	include "adminmenus.php";
	require_once "../common.php";
	require_once "DataEdit.php";

class ftEvEdit extends DataEdit
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
			'image' => '',
			'dates' => '',
			'times' => '',
			'location' => '',
			'text' => ''
		);
	}

	protected function fetchItem()
	{
		$id = $_GET['item'];
		$sql = "SELECT * FROM futureevents WHERE id=$id";
		$result = $this->mysqli->query($sql)
			or die ("Error fetching item" . mysqli_error($this->mysqli));
		$this->record =  mysqli_fetch_array($result, MYSQLI_ASSOC);
	}

// -------------------------------------------
//	Show the event edit form
//
// -------------------------------------------
	protected function showForm($mode)
	{
		$dta = $this->record;
		if ($mode=='ins')
			$action = "futureeventlist.php?mode=ins";
		else
		{
			$id = $_GET['item'];
			$action = "futureeventlist.php?mode=upd&item=$id";
		}
		$streams = array (
			'Exhibitions',
			'Workshops',
			'Other Events'
		);
		echo "<form method='post' action='$action'>";
			$this->showDropDown('Event type', $dta, 'stream', $streams);
			$this->showLine('Title', $dta, 'title', 60);
			$this->showLine('Image File', $dta, 'image', 60);
			$this->showLine('Dates', $dta, 'dates', 60);
			$this->showLine('Times', $dta, 'times', 60);
			$this->showLine('Location', $dta, 'location', 60);
			$this->textArea('Text', $dta, 'text', 4, 60);
			echo "<button type='submit'>Post</button>";
		echo "</form>";
	}


// -------------------------------------------
//	Show the event edit form
//
// -------------------------------------------
	protected function showForm2($mode)
	{
		$dta = $this->record;
		if ($mode=='ins')
			$action = "briefinglist.php?mode=ins";
		else
		{
			$id = $_GET['item'];
			$action = "briefinglist.php?mode=upd&item=$id";
		}
/*		$streams = array (
			'Exhibitions',
			'Workshops',
			'Other Events'
		); */
		echo "<form method='post' action='$action'>";
			$this->showLine('Date', $dta, 'date', 60);
			$this->showLine('Speaker', $dta, 'speaker', 60);
			$this->showLine('Bio', $dta, 'bio', 60);
			$this->showLine('Title', $dta, 'title', 60);
			$this->textArea('Details', $dta, 'subject', 4, 60);
			echo "<button type='submit'>Post</button>";
		echo "</form>";
	}
}

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	echo "<h3>Forthcoming Event </h3>";
	$lst = new ftEvEdit($mysqli);
	$lst->run();
?>
</body>
</html>

