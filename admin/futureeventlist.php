<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	fteventlist.php
//		List forthcoming events
//
//  Parameters	default - show list
//		mode - ins or upd
//
//  Author	John McMillan, McMillan Technology
//
//  Class ftEvList which extends DataList
// ------------------------------------------------------

?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Future events</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<style>
.lsTitle
{
	position:		absolute;
	left:			20px;
	width:			480px;
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
	include "adminmenus.php";
	require_once "../common.php";
	require_once "DataList.php";

class ftEvList extends DataList
{
	function __construct($mysqli)
	{
		parent::__construct($mysqli);
	}

	public function showListLine($line)
	{
		$id = $line['id'];
		$title = $line['title'];
		$onEdit = "window.location=\"futureeventedit.php?mode=upd&item=$id\"";
		$onDelete = "window.location=\"futureeventlist.php?mode=del&item=$id\"";
	
		echo "\n<span class='lsTitle'>$title</span>";
		echo "<span class='lsButton'>";
			echo "<button onClick='$onEdit'>Edit</button>";
			echo "&nbsp;";
			echo "<button onClick='$onDelete'>Delete</button>";
		echo "</span><br>";
	}

	public function showHeading()
	{
		echo "\n<b><span class='lsTitle'>Title</span>";
		echo "<span class='lsButton'> Edit</span>";
		echo "</b><br><br>";
	}

	// -------------------------------------------
	//	Insert new item
	//
	// -------------------------------------------
	protected function insertItem()
	{
		$sql = "INSERT INTO futureevents "
			. "(title, stream, image, dates, times, location, text) "
			. " VALUES ("
			. $this->postField('title') . ','
			. $this->postField('stream') . ','
			. $this->postField('image') . ','
			. $this->postField('dates') . ','
			. $this->postField('times') . ','
			. $this->postField('location') . ','
			. $this->postField('text')
			. ")";

		$this->mysqli->query($sql)
			or die ("Error inserting event " . mysqli_error($this->mysqli));
	}

	protected function upDateItem()
	{
		$id = $_GET['item'];

		$sql = "UPDATE futureevents SET "
			. "stream=" . $this->postField('stream')
			. ", title=" . $this->postField('title')
			. " ,image=" . $this->postField('image')
			. " ,dates=" . $this->postField('dates')
			. " ,times=" . $this->postField('times')
			. " ,location=" . $this->postField('location')
			. " ,text=" . $this->postField('text')
			. " WHERE id=$id";

		$this->mysqli->query($sql)
			or die ("Error updating item " . mysqli_error($this->mysqli));
	}

	protected function deleteItem()
	{
		$id = $_GET['item'];

		$sql = "DELETE FROM futureevents "
			. " WHERE id=$id";

		$this->mysqli->query($sql)
			or die ("Error deleting event " . mysqli_error($this->mysqli));
	}
}

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	echo "<h3>Future Events</h3>";
	$lst = new ftEvList($mysqli);
	$lst->sqlShow("SELECT * FROM futureevents");
	$lst->editPage("futureeventedit.php");
	$lst->run();
?>
</body>
</html>
