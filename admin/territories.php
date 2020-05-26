<?php
// ------------------------------------------------------
//  Project OnLine Gallery
//  File    territories.php
//          Edit shipping bands
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
<title>Shipping Territories</title>
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

class Territory extends DataEdit
{
    function __construct($mysqli)
    {
        parent::__construct($mysqli);
    }

    protected function setNewItem()
    {
        $this->record = array (
            'territory1' => 'UK',
            'territory2' => 'EU',
            'territory3' => 'USA',
            'territory4' => 'Australia'
            );
    }

// -------------------------------------------
//	Override of DataEdit virtual function
//
//	Reads event into class member record
// -------------------------------------------
    protected function fetchItem()
    {
        $artist = $_SESSION['loggedColl'];

        $sql = "SELECT * FROM users WHERE collection=$artist";
        $result = $this->mysqli->query($sql)
                or die ("Error fetching shipping band" . mysqli_error($this->mysqli));
        $this->record =  mysqli_fetch_array($result, MYSQLI_ASSOC);
    }

// -------------------------------------------
//	Show the event edit form
//
// -------------------------------------------
    protected function showForm($mode)
    {
//        if ($mode=='ins')
        $action = "territorypost.php";
/*        else {
            $id = $_GET['item'];
            $action = "shipbandpost.php?mode=upd&item=$id";
        }
*/
        $dta = $this->record;

        echo "<form method='post' action='$action'>";
            $this->showLine('Territory 1', $dta, 'territory1', 10);
            $this->showLine('Territory 2', $dta, 'territory2', 10);
            $this->showLine('Territory 3', $dta, 'territory3', 10);
            $this->showLine('Territory 4', $dta, 'territory4', 10);
            echo "<button type='submit'>Post</button>";
            echo "</form>";

    }

}
    $config = setConfig();				// Connect to database
    $mysqli = dbConnect($config);

    echo "<h3>Edit Shipping Territories</h3>";
    $lst = new Territory($mysqli);
    $lst->run('upd');
?>
</body>
</html>

