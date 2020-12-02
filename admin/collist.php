<?php
    session_start();
// ------------------------------------------------------
//  Project	Lupe Cunha
//	File	collist.php
//			List collections
//
//	Parameters	default - show list
//				mode - ins or upd
//
//	Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
class collist extends DataList
	public function showListLine($line)
	public function showHeading()
	protected function insertItem()
	protected function upDateItem()
	protected function deleteItem()
	private function setLinks($id)
	private function lookupColl($stmtColl)
	protected function SQLDate($dt)
*/
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Lupe Collections</title>
<link type="text/css" rel="stylesheet" href="../Cunha.css">
<script src="../Cunha.js"></script>
<style>
.lsTitle
{
	position:		absolute;
	left:			20px;
	width:			320px;
}

.lsSequence
{
	position:		absolute;
	left:			280px;
}

.lsButton
{
	position:		absolute;
	left:			330px;
}

</style>
</head>
<body onload="adminLoad()">
<?php
	require_once "../common.php";
	require_once "adminmenus.php";
	require_once "DataList.php";

/*$config = setConfig();
$mysqli = dbConnect($config);
//print_r($config);
$sql = "SELECT * FROM collections INTO OUTFILE 'collections.csv' "
        . "FIELDS ENCLOSED BY '\"' TERMINATED BY ',' "
        . "LINES TERMINATED BY '\r\n';";
if (!$mysqli->query($sql))
    die ("Query error"  . $mysqli -> error); */
class collist extends DataList
{
	function __construct($mysqli)
	{
		parent::__construct($mysqli);
	}

	public function showListLine($line)
	{
		$id = $line['id'];
		$name = $line['name'];
		$sequence= $line['sequence'];
		$onEdit = "window.location=\"coledit.php?mode=upd&item=$id\"";
		$onDelete = "window.location=\"collist.php?mode=del&item=$id\"";
	
		echo "\n<span class='lsTitle'>$name</span>";
		echo "<span class='lsSequence'>$sequence</span>";
		echo "<span class='lsButton'>";
			echo "<button onClick='$onEdit'>Edit</button>";
			echo "&nbsp;";
			echo "<button onClick='$onDelete'>Delete</button>";
		echo "</span><br>";
	}

	// ----------------------------------------------
	//	Show headings
	//
	// ----------------------------------------------
	public function showHeading()
	{
		echo "\n<b><span class='lsTitle'>Title</span>";
		echo "<span class='lsSequence'>Seq</span>";
		echo "<span class='lsButton'> Edit</span>";
		echo "</b><br><br>";
	}

	// -------------------------------------------
	//	Insert new item
	//
	// -------------------------------------------
	protected function insertItem()
	{
		if ($_SESSION['colEdit'] == 0)				// Guard against repeating
			return;

		$uselowprice = $this->getCheckBox('uselowprice');

		$sql = "INSERT INTO collections "
			. "(name, image, sequence, search, uselowprice, text) "
			. " VALUES (?, ?, ?, ?, ?, ?)";
		if (!($stmt = $this->mysqli->prepare($sql)))
			echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

		if (!$stmt->bind_param('ssisis', $name, $image, $sequence, $search, $uselowprice, $text)) 
			echo "Bind failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

		$name = $_POST['name'];
		$image = $_POST['image'];
		$sequence  = $_POST['sequence'];
		$search = $_POST['search'];
		$text = $_POST['text'];

		if (!$stmt->execute())
			$this->sqlError("Insert collection failed");
		$newId = $this->mysqli->insert_id;
		$stmt->close();

		$_SESSION['colEdit'] = 0;
	}

	// ----------------------------------------------
	//	Post updated record
	//	Called from coledit.php
	//
	// ----------------------------------------------
	protected function upDateItem()
	{
		$id = $_GET['item'];

		$sql = "UPDATE collections SET name=?, image=?, sequence=?, search=?, uselowprice=?, text=?"
			. " WHERE id=$id";

		if (!($stmt = $this->mysqli->prepare($sql)))
			echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

		if (!$stmt->bind_param('ssisis', $name, $image, $sequence, $search, $uselowprice, $text)) 
			echo "Bind failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

		$name = $_POST['name'];
		$sequence  = $_POST['sequence'];
		$search = $_POST['search'];
		$image = $_POST['image'];
		$uselowprice = $this->getCheckBox('uselowprice');
		$text = $_POST['text'];

		$status = $stmt->execute();
		if ($status === false)
			$this->sqlError ("Execute failed");
		$stmt->close();

	}

	
	// ----------------------------------------------
	//	Process call to delete a painting
	//
	// ----------------------------------------------
	protected function deleteItem()
	{
		$id = $_GET['item'];

		$sql = "DELETE FROM collections "
			. " WHERE id=$id";

		$this->mysqli->query($sql)
			or die ("Error deleting collection " . mysqli_error($this->mysqli));
	}

}

// ----------------------------------------------
//	Non class data
//
//	Connect to database and create instance 
//	of collist
// ----------------------------------------------

	$config = setConfig();					// Connect to database
	$mysqli = dbConnect($config);

	echo "<h3>Collections</h3>";
	$lst = new collist($mysqli);
	$lst->sqlShow("SELECT * FROM collections ORDER BY sequence");
	$lst->editPage("coledit.php");
	$lst->run();
?>
</body>
</html>
