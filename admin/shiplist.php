<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	shiplist.php
//		List shipping grid
//
//  Parameters	default - show list
//		mode - ins or upd
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
class shiplist extends DataList
	public function showListLine($line)
	public function showHeading()
	protected function insertItem()
	protected function upDateItem()
	protected function deleteItem()
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Collections</title>
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
<body>
<?php
	session_start();
	require_once "../common.php";
	require_once "adminmenus.php";
	require_once "DataList.php";

class shiplist extends DataList
{
    function __construct($mysqli)
    {
        parent::__construct($mysqli);
    }

    public function showListLine($line)
    {
        $id = $line['sizeband'];
        $description = $line['description'];
        $onEdit = "window.location=\"shipedit.php?mode=upd&item=$id\"";
        $onDelete = "window.location=\"shiplist.php?mode=del&item=$id\"";

        echo "\n<span class='lsTitle'>$description</span>";
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
        echo "\n<b><span class='lsTitle'>Description</span>";
        echo "<span class='lsButton'> Edit</span>";
        echo "</b><br><br>";
    }

    // -------------------------------------------
    //	Insert new item
    //
    // -------------------------------------------
    protected function insertItem()
    {
        $artist = $_SESSION['artistId'];

        $uselowprice = $this->getCheckBox('uselowprice');

        $sql = "INSERT INTO shipping "
            . "(sizeband, description, collect, uk, eu, usa, aus, artist) "
            . " VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        if (!($stmt = $this->mysqli->prepare($sql)))
            echo "SQL prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

        if (!$stmt->bind_param('isiiiiii', $sizeband, $description, 
            $collect, $uk, $eu, $usa, $aus, $artist)) 
            echo "SQL bind failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

        $sizeband = $_POST['sizeband'];
        $description = $_POST['description'];
        $collect = $_POST['collect'] * 100;
        $uk = $_POST['uk'] * 100;
        $eu = $_POST['eu'] * 100;
        $usa = $_POST['usa'] * 100;
        $aus = $_POST['aus'] * 100;

        if (!$stmt->execute())
            $this->sqlError("Insert shipping band failed");
        $newId = $this->mysqli->insert_id;
        $stmt->close();
    }

    // ----------------------------------------------
    //	Post updated record
    //	Called from shipedit.php
    //
    // ----------------------------------------------
    protected function upDateItem()
    {
        $id = $_GET['item'];
        $artist = $_SESSION['artistId'];

        $sql = "UPDATE shipping SET sizeband=?, description=?, collect=?, uk=?, "
            . "eu=?, usa=?, aus=?"
            . " WHERE sizeband=$id AND artist=$artist";

        if (!($stmt = $this->mysqli->prepare($sql)))
            echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

        if (!$stmt->bind_param('isiiiii', $sizeband, $description, $collect, $uk, $eu, $usa, $aus)) 
            echo "Bind failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

        $sizeband = $_POST['sizeband'];
        $description = $_POST['description'];
        $collect = $_POST['collect'];
        $uk = $_POST['uk'] * 100;
        $eu = $_POST['eu'] * 100;
        $usa = $_POST['usa'] * 100;
        $aus = $_POST['aus'] * 100;

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
        $artist = $_SESSION['artistId'];

        $sql = "DELETE FROM shipping "
            . " WHERE sizeband=$id AND artist=$artist";

        $this->mysqli->query($sql)
            or die ("Error deleting shipping band " . mysqli_error($this->mysqli));
    }

}

// ----------------------------------------------
//	Non class data
//
//	Connect to database and create instance 
//	of shiplist
// ----------------------------------------------

    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);

    $artist = $_SESSION['artistId'];
    echo "<h3>Shipping bands</h3>";
    $lst = new shiplist($mysqli);
    $lst->sqlShow("SELECT * FROM shipping WHERE artist=$artist");
    $lst->editPage("shipedit.php");
    $lst->run();
?>
</body>
</html>
