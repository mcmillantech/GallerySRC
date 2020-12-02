<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	eventlist.php
//		List pictures
//
//  Parameters	default - show list
//		mode - ins or upd
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
class PicList extends DataList
	public function showListLine($line)
	public function showHeading()
	protected function insertItem()
	protected function upDateItem()
	protected function deleteItem()
	private function setLinks($id)
	private function lookupColl($stmtColl)
	protected function SQLDate($dt)
	js function doDelete(item)
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Pictures</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
<script>
function doDelete(item, name)
{
    var msg = "Delete " + name 
        + "\nWARNING If you have sold a work, do not do this until you have shipped it"
        + "\nare you sure?";
    if (confirm(msg))
        window.location="piclist.php?mode=del&item=" + item;
}
</script>
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
	left:			330px;
}

.lsButton
{
	position:		absolute;
	left:			380px;
}

</style>
</head>
<body>
<?php
	session_start();
	require_once "../common.php";
	require_once "adminmenus.php";
	require_once "DataList.php";

class PicList extends DataList
{
    function __construct($mysqli)
    {
        parent::__construct($mysqli);
    }

    public function showListLine($line)
    {
        $id = $line['id'];
        $name = $line['name'];
        $seq = $line['seq'];
        $onEdit = "window.location=\"picedit.php?mode=upd&item=$id\"";

        echo "\n<span class='lsTitle'>$name</span>";
        echo "\n<span class='lsSequence'>$seq</span>";
        echo "<span class='lsButton'>";
        if ($_SESSION['userLevel'] < 3) {
            echo "<button onClick='$onEdit'>Edit</button>";
            echo "&nbsp;";
            if ($line['deleted'] != 1) {
                echo "<button onClick='doDelete($id, \"$name\")'>";
                echo "Remove from display</button>";
            }
            else {
                echo "Deleted";
            }
        }
        else {
            $artist = $line['artist'];
            echo " &nbsp;$artist";
        }
        echo "</span>";
        echo '<br>';
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
        if ($_SESSION['picEdit'] == 0)		// Guard against repeating
                return;

        $recent = $this->getCheckBox('recent');
        $archive = $this->getCheckBox('archive');
        $costcovered = $this->getCheckBox('costcovered');

        $sql = "INSERT INTO paintings "
            . "(name, recent, dateset, media, size, mount, tags, priceweb, priceebay, "
            . " costcovered, datesold, archive, image, notes, shippingrate, "
            . "year, quantity, away, seq) "
            . " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if (!($stmt = $this->mysqli->prepare($sql)))
            echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

        if (!$stmt->bind_param('sisssssiiisissiiisi', $name, $recent, $dateset, $media, $size, 
            $mount, $tags, $priceweb, $priceebay, $costcovered, $datesold, $archive, $image,
            $notes, $shippingrate, $year, $quantity, $away, $seq))
        echo	"Bind failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

        $name = addslashes($_POST['name']);
        $dt = $_POST['dateset'];
        $dateset = substr($dt, 6, 4) . '-' . substr($dt, 3, 2) . '-' . substr($dt, 0, 2);
        $recent = $this->getCheckBox('recent');
        $year = $_POST['year'];
        $media = $_POST['media'];
        $mount = $_POST['mount'];
        $size = addslashes($_POST['size']);
        $tags = $_POST['tags'];
        $priceweb = $_POST['priceweb'] * 100;
        $priceebay = $_POST['priceebay'] * 100;
        $archive = $this->getCheckBox('archive');
        $away = $this->SQLDate($_POST['away']);
        $costcovered = $this->getCheckBox('costcovered');
        $datesold  = $this->SQLDate($_POST['datesold']);
        $image  = $_POST['image'];
        $notes  = $_POST['notes'];
        $shippingrate = $_POST['shippingrate'];
        $quantity = $_POST['quantity'];
        $seq = $_POST['seq'];

        if (!$stmt->execute())
            $this->sqlError("Insert painting failed");
        $newId = $this->mysqli->insert_id;
        $stmt->close();
        $this->setLinks($newId);

        $_SESSION['picEdit'] = 0;
    }

    // ----------------------------------------------
    //	Post updated record
    //	Called from picedit.php
    //
    // ----------------------------------------------
    protected function upDateItem()
    {
        $id = $_GET['item'];

        $sql = "UPDATE paintings SET name=?, recent=?, dateset=?, media=?, "
            . "size=?, mount=?, tags=?, priceweb=?, priceebay=?, costcovered=?, "
            . "datesold=?, archive=?, image=?, notes=?, shippingrate=?, year=?, "
            . "quantity=?, away=?, seq=?"
            . " WHERE id=$id";

        if (!($stmt = $this->mysqli->prepare($sql)))
            echo "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

        if (!$stmt->bind_param('sisssssiiisissiiisi', $name, $recent, $dateset, $media, $size, 
            $mount, $tags, $priceweb, $priceebay, $costcovered, $datesold, $archive, 
            $image, $notes, $shippingrate, $year, $quantity, $away, $seq))
            "Bind failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;

        $name = $_POST['name'];
        $dt = $_POST['dateset'];
        $dateset = substr($dt, 6, 4) . '-' . substr($dt, 3, 2) . '-' . substr($dt, 0, 2);
        $recent = $this->getCheckBox('recent');
        $year = $_POST['year'];
        $media = $_POST['media'];
        $mount = $_POST['mount'];
        $size = $_POST['size'];
        $tags = $_POST['tags'];
        $priceweb = $_POST['priceweb'] * 100;
        $priceebay = $_POST['priceebay'] * 100;
        $archive = $this->getCheckBox('archive');
        $costcovered = $this->getCheckBox('costcovered');
        $datesold  = $this->SQLDate($_POST['datesold']);
        $image  = $_POST['image'];
        $notes  = $_POST['notes'];
        $shippingrate = $_POST['shippingrate'];
        $quantity = $_POST['quantity'];
        $away = $this->SQLDate($_POST['away']);
        $seq = $_POST['seq'];

        $status = $stmt->execute();
        if ($status === false)
                $this->sqlError ("Execute failed");
        $stmt->close();

        $this->setLinks($id);
    }

    // ----------------------------------------------
    //	Set up the links to collections
    //
    //	Parameter	id of painting
    // ----------------------------------------------
    private function setLinks($id)
    {
        $collName = $_POST['coll1'];
                                    // Start by deleting the existing links
        $sql = "DELETE FROM links WHERE picture=?";
        $stmtDel = $this->mysqli->prepare($sql);
        $stmtDel->bind_param('i', $idL);

        $idL = $id;				// 
        $status = $stmtDel->execute();
        if ($status == false)
                $this->sqlError ("Execute delete linksfailed");
        $stmtDel->close();

                                            // Prepare to insert links
        $sql = "INSERT INTO links SET picture=?, collection=?";
        if (!($stmtLink = $this->mysqli->prepare($sql)))
                $this->sqlError ("Prepare link failed");
        if (!$stmtLink->bind_param('ii', $picId, $collId))
                $this->sqlError ("Bind coll failed");
        $picId = $id;

        $collName = $_POST['coll1'];
        if ($collName <> '') {
            $collId = $this->lookupColl($collName);
            $status = $stmtLink->execute();
            if (!$status)
                $this->sqlError ("Link insert failed");
        }

        $collName = $_POST['coll2'];
        if ($collName <> '') {
            $collId = $this->lookupColl($collName);
            $status = $stmtLink->execute();
            if (!$status)
                $this->sqlError ("Link insert failed");
        }

        $collName = $_POST['coll3'];
        if ($collName <> '') {
            $collId = $this->lookupColl($collName);
            $status = $stmtLink->execute();
            if (!$status)
                $this->sqlError ("Link insert failed");
        }

        $stmtLink->close();
    }

    // ----------------------------------------------
    //	Helper for setLinks
    //
    //	Find the id of a collection given its name
    // ----------------------------------------------
    private function lookupColl($colName)
    {
        global $mysqli;
                                        // Prepare to look up collections		
        $sql = "SELECT * FROM collections WHERE name='$colName'";
        $result = $mysqli->query($sql) 
            or die ("Error looking up coll: $sql");
        if (mysqli_num_rows($result) == 0) {
            echo ("Error: $colName not found<br>");
            return 0;
        }
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        mysqli_free_result($result);
        return $row['id'];
    }

    // ----------------------------------------------
    //	Process call to delete a painting
    //
    // ----------------------------------------------
    protected function deleteItem()
    {
        $id = $_GET['item'];

        $sql = "UPDATE paintings SET deleted=1"
            . " WHERE id=$id";

        $this->mysqli->query($sql)
            or die ("Error deleting event " . mysqli_error($this->mysqli));
    }

    protected function SQLDate($dt)
    {
        if ($dt == '')
                return null;
        $dt = str_replace('"', '', $dt);
        list($day, $mon, $year) = explode("/", $dt);
        $dtm = "$year-$mon-$day";
        return $dtm;
    }
}

// ----------------------------------------------
//	Non class data
//
//	Connect to database and create instance 
//	of PicList
// ----------------------------------------------

    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);

    echo "<h3>Pictures</h3>";
    $lst = new PicList($mysqli);
    require_once 'artgroup.php';
//    $artist = $_SESSION['loggedColl'];
    $sql = picListSql($mysqli);
    $lst->sqlShow($sql);
    $lst->editPage("picedit.php");
    $lst->run();
?>
</body>
</html>
