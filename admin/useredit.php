<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	useredit.php
//		Edit user table
//
//  Parameters	
//		mode - ins or upd
//		id - index of item, upd mode only
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
protected function setNewItem
protected function fetchItem()
protected function dispDate($dt)
protected function showForm($mode)

JS function showFileFrame()
*/
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Edit Gallery Users</title>
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
    require_once "../common.php";
    require_once "DataEdit.php";

class userEdit extends DataEdit
{
    function __construct($mysqli)
    {
        parent::__construct($mysqli);
        $_SESSION['colEdit'] = 1;
    }

    protected function setNewItem()
    {
        $this->record = array
        (
            'username' => '',
            'collection' => '0',
            'password' => '',
            'fullname' => '',
            'firstname' => '',
            'email' => '',
            'addr1' => '',
            'addr2' => '',
            'addr3' => '',
            'addr4' => '',
            'postcode' => '',
            'phone' => '',
            'website' => '',
            'level' => 1,
            'status' => 0,
            'nworks' => 6,
            'enddate' => ''
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
        $sql = "SELECT * FROM users WHERE id=$id";
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
            $action = "userlist.php?mode=ins";
        else {
            $id = $_GET['item'];
            $action = "userlist.php?mode=upd&item=$id";
        }

        $dta = $this->record;

        echo "<form method='post' action='$action'>";
            $this->showLine('User Name', $dta, 'username', 16);
            $this->showLine('Password', $dta, 'password', 16);
            $this->showLine('Collection', $dta, 'collection', 5);
            $this->showLine('First Name', $dta, 'firstname', 45);
            $this->showLine('Full Name', $dta, 'fullname', 45);
            $this->showLine('Email', $dta, 'email', 45);
            $this->showLine('Address', $dta, 'addr1', 45);
            $this->showLine('', $dta, 'addr2', 45);
            $this->showLine('', $dta, 'addr3', 45);
            $this->showLine('', $dta, 'addr4', 45);
            $this->showLine('Postcode', $dta, 'postcode', 16);
            $this->showLine('Phone', $dta, 'phone', 20);
            $this->showLine('Website', $dta, 'website', 45);
            $this->showLine('Level', $dta, 'level', 2);
            echo " &nbsp;(Level: 2 artist, 3 superadmin)";
            echo " Status: 0 pending, 1 live, 9 expired";
            echo "<br>";
            $this->showLine('Status', $dta, 'status', 2);
            $this->showLine('no. works', $dta, 'nworks', 2);
            $this->showLine('End date (yyyy-mm)', $dta, 'enddate', 8);
            echo "<button type='submit'>Post</button>";
        echo "</form>";

    }

    private function userList()
    {
        $ar = array();
        array_push($ar, '');

        $sql = "SELECT * FROM users";
        $result = $this->mysqli->query($sql)
            or die ("Error reading link" . mysqli_error($this->mysqli));
        while ($coll = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
            array_push($ar, $coll['name']);
        }

        mysqli_free_result($result);
        return $ar;
    }
}

    $config = setConfig();					// Connect to database
    $mysqli = dbConnect($config);

    echo "<h3>Edit Users</h3>";
    $edit = new userEdit($mysqli);
    $edit->run();
?>
</body>
</html>

