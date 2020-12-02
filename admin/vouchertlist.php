<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	eventlist.php
//		List current events
//
//  Parameters	default - show list
//		mode - ins or upd
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
public function showListLine($line)
public function showHeading()
protected function insertItem()
protected function upDateItem()
protected function deleteItem()
protected function SQLDate($dt)
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Events</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
<style>
.lsTitle
{
	position:		absolute;
	left:			20px;
	width:			320px;
}

.lsDate
{
	position:		absolute;
	left:			380px;
}

.lsButton
{
	position:		absolute;
	left:			480px;
}

</style>
</head>
<body>
<?php
	session_start();
	include "adminmenus.php";
	require_once "../common.php";
	require_once "DataList.php";

class UserList extends DataList
{
	function __construct($mysqli)
	{
		parent::__construct($mysqli);
	}

	public function showListLine($line)
	{
		$id = $line['id'];
		$title = substr($line['title'], 0, 38);
		$dt = $line['date1'];
		$sDate = substr($dt, 8, 2) . '/' . substr($dt, 5, 2) . '/' . substr($dt, 0, 4);
		$onEdit = "window.location=\"eventedit.php?mode=upd&item=$id\"";
		$onDelete = "window.location=\"eventlist.php?mode=del&item=$id\"";
	
		echo "\n<span class='lsTitle'>$title</span>";
		echo "\n<span class='lsDate'>$sDate</span>";
		echo "<span class='lsButton'>";
			echo "<button onClick='$onEdit'>Edit</button>";
			echo "&nbsp;";
			echo "<button onClick='$onDelete'>Delete</button>";
		echo "</span><br>";
	}

	public function showHeading()
	{
		echo "\n<b><span class='lsTitle'>Title</span>";
		echo "<span class='lsDate'>Start</span>";
		echo "<span class='lsButton'> Edit</span>";
		echo "</b><br><br>";
	}

	// -------------------------------------------
	//	Insert new item
	//
	// -------------------------------------------
	protected function insertItem()
	{
		$date1 = $this->postField('date1');
		if (strlen($date1) > 3)
			$date1 = $this->SQLDate($date1);
		else
			$date1 = "null";
		$date2 = $this->postField('date2');
		if (strlen($date2) > 3)
			$date2 = $this->SQLDate($date2);
		else
			$date2 = "null";

		$sql = "INSERT INTO events "
			. "(title, stream, date1, date2, times, location, contact, address, "
			. "image1, image2, image3, image4, image5, image6, text) "
			. " VALUES ("
			. $this->postField('title') . ','
			. $this->postField('stream') . ','
//			. $this->postField('mainimage') . ','
			. $date1 . ','
			. $date2 . ','
			. $this->postField('times') . ','
			. $this->postField('location') . ','
			. $this->postField('contact') . ','
			. $this->postField('address') . ','
			. $this->postField('image1') . ','
			. $this->postField('image2') . ','
			. $this->postField('image3') . ','
			. $this->postField('image4') . ','
			. $this->postField('image5') . ','
			. $this->postField('image5') . ','
			. $this->postField('text')
			. ")";

		$this->mysqli->query($sql)
			or die ("Error inserting item " . mysqli_error($this->mysqli));
	}

	protected function upDateItem()
	{
		$id = $_GET['item'];
		$date1 = $this->postField('date1');
		if (strlen($date1) > 3)
			$date1 = $this->SQLDate($date1);
		else
			$date1 = "null";
		$date2 = $this->postField('date2');
		if (strlen($date2) > 3)
			$date2 = $this->SQLDate($date2);
		else
			$date2 = "null";

		$sql = "UPDATE events SET "
			. "stream=" . $this->postField('stream')
			. ", title=" . $this->postField('title')
//			. " ,mainimage=" . $this->postField('mainimage')
			. " ,date1=" . $date1
			. " ,date2=" . $date2
			. " ,times=" . $this->postField('times')
			. " ,location=" . $this->postField('location')
			. " ,contact=" . $this->postField('contact')
			. " ,address=" . $this->postField('address')
			. " ,image1=" . $this->postField('image1')
			. " ,image2=" . $this->postField('image2')
			. " ,image3=" . $this->postField('image3')
			. " ,image4=" . $this->postField('image4')
			. " ,image5=" . $this->postField('image5')
			. " ,image6=" . $this->postField('image6')
			. " ,text=" . $this->postField('text')
			. " WHERE id=$id";

		$this->mysqli->query($sql)
			or die ("Error updating item " . mysqli_error($this->mysqli));
	}
	
	protected function deleteItem()
	{
		$id = $_GET['item'];
		$sql = "DELETE FROM events WHERE id=$id";

		$this->mysqli->query($sql)
			or die ("Error deleting item " . mysqli_error($this->mysqli));
	}

	

	protected function SQLDate($dt)
	{
		$dt = str_replace('"', '', $dt);
		list($day, $mon, $year) = explode("/", $dt);
		$dtm = '"' . "$year-$mon-$day" . '"';
		return $dtm;
	}
	
}

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	echo "<h3>Current Events</h3>";
	$lst = new UserList($mysqli);
	$lst->sqlShow("SELECT * FROM events  ORDER BY date1 DESC");
	$lst->editPage("eventedit.php");
	$lst->run();
?>
</body>
</html>
