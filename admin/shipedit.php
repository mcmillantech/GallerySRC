<?php
// ------------------------------------------------------
//  Project OnLine Gallery
//  File    shipedit.php
//          Edit shipping grid
//
//  Parameters	
//	mode - ins or upd
//	id - index of item, upd mode only
//
//  Author  John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
protected function setNewItem
protected function fetchItem()
protected function showForm($mode)

*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Edit Shipping List</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
<style>
.input
{
	position:			absolute;
	left:				200px;
}
</style>
</head>

<body>
<?php
    session_start();
    include "adminmenus.php";
    require_once "../common.php";
    require_once "DataEdit.php";
    require_once "artgroup.php";

class shipedit extends DataEdit
{
    function __construct($mysqli)
    {
        parent::__construct($mysqli);
    }

    protected function setNewItem()
    {
        $this->record = array (
            'sizeband' => '',
            'description' => '',
            'collect' => '0',
            'uk' => '0',
            'eu' => '0',
            'usa' => '0',
            'aus' => '0'
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
        $collection = $_SESSION['loggedColl'];

        $sql = "SELECT * FROM shipping WHERE sizeband=$id AND artist=$collection";
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
        if ($mode=='ins')
                $action = "shiplist.php?mode=ins";
        else {
            $id = $_GET['item'];
            $action = "shiplist.php?mode=upd&item=$id";
        }

        $artist = $_SESSION['loggedColl'];
        $terr = getTerritories($artist);
        
        $dta = $this->record;
        $dta['collect'] /= 100.0;
        $dta['uk'] /= 100.0;
        $dta['eu'] /= 100.0;
        $dta['usa'] /= 100.0;
        $dta['aus'] /= 100.0;

        echo "<form method='post' action='$action'>";
            $this->showLine('Size Band', $dta, 'sizeband', 4);
            $this->showLine('Description', $dta, 'description', 45);
            $this->showLine('Collect', $dta, 'collect', 10);
            $this->showLine($terr['territory1'], $dta, 'uk', 10);
            $this->showLine($terr['territory2'], $dta, 'eu', 10);
            $this->showLine($terr['territory3'], $dta, 'usa', 10);
            $this->showLine($terr['territory4'], $dta, 'aus', 10);
            echo "<button type='submit'>Post</button>";
            echo "</form>";

    }

}

    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);

    echo "<h3>Edit Shipping List</h3>";
    $lst = new shipedit($mysqli);
    $lst->run();
?>
</body>
</html>

