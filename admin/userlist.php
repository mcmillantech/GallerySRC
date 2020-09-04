<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	userlist.php
//		List users
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
<title>Gallery Users</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
<style>
.lsUser
{
	position:		absolute;
	left:			20px;
	width:			320px;
}

.lsName
{
	position:		absolute;
	left:			240px;
}

.lsLevel
{
	position:		absolute;
	left:			550px;
    
}

.lsButton
{
	position:		absolute;
	left:			620px;
}

</style>
</head>
<body>
<?php
//	session_start();
//	include "adminmenus.php";
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
        $onEdit = "window.location=\"useredit.php?mode=upd&item=$id\"";
        $onDelete = "window.location=\"userlist.php?mode=del&item=$id\"";

        echo "\n<span class='lsUser'>" . $line['username'] . "</span>";
        echo "\n<span class='lsName'>" . $line['fullname'] . "</span>";
        echo "\n<span class='lsLevel'>" . $line['level'] . "</span>";
        echo "<span class='lsButton'>";
                echo "<button onClick='$onEdit'>Edit</button>";
                echo "&nbsp;";
                echo "<button onClick='$onDelete'>Delete</button>";
        echo "</span><br>";
    }

    public function showHeading()
    {
        echo "<h2>Users</h2>";
        echo "<span class='lsUser'>User</span>";
        echo "<span class='lsName'>Name</span>";
        echo "<span class='lsLevel'>Level</span>";
        echo "<span class='lsButton'> Edit</span>";
        echo "</b><br><br>";
    }

    // -------------------------------------------
    //	Insert new item
    //
    // -------------------------------------------
    protected function insertItem()
    {
        $sql = "INSERT INTO users "
            . "(username, password, fullname, firstname, email, addr1, "
            . "addr2, addr3, addr4, postcode, phone, website, level, "
            . "collection, status, nworks, enddate ) "
            . " VALUES ("
            . $this->postField('username') . ','
            . $this->postField('password') . ','
            . $this->postField('fullname') . ','
            . $this->postField('firstname') . ','
            . $this->postField('email') . ','
            . $this->postField('addr1') . ','
            . $this->postField('addr2') . ','
            . $this->postField('addr3') . ','
            . $this->postField('addr4') . ','
            . $this->postField('postcode') . ','
            . $this->postField('phone') . ','
            . $this->postField('website') . ','
            . $this->postField('level') . ','
            . $this->postField('collection') . ','
            . $this->postField('status') . ','
            . $this->postField('nworks') . ','
            . $this->postField('enddate')
            . ")";
        $this->mysqli->query($sql)
            or die ("Error inserting user " . mysqli_error($this->mysqli));
        
//    $this->createCollection();
    }

    // -------------------------------------------
    // Create a collection for this user
    // 
    // -------------------------------------------
    private function createCollection()
    {
        $sql = "SELECT MAX(sequence) as sequence FROM collections";
        $result = $this->mysqli->query($sql);
        $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $nextSequence = $record['sequence'] + 1;
        
        $sql2 = "INSERT INTO collections (name, search, sequence) VALUES ("
            . $this->postField('fullname')  . ','
            . $this->postField('fullname')  . ','
            . "$nextSequence)";
        echo $sql2;
        $this->mysqli->query($sql2)
            or die ("Error creating collection" . mysqli_error($this->mysqli));
    }

    protected function upDateItem()
    {
        $id = $_GET['item'];

        $sql = "UPDATE users SET "
            . " username=" . $this->postField('username')
            . " ,password=" . $this->postField('password')
            . " ,fullname=" . $this->postField('fullname')
            . " ,firstname=" . $this->postField('firstname')
            . " ,email=" . $this->postField('email')
            . " ,addr1=" . $this->postField('addr1')
            . " ,addr2=" . $this->postField('addr2')
            . " ,addr3=" . $this->postField('addr3')
            . " ,addr4=" . $this->postField('addr4')
            . " ,postcode=" . $this->postField('postcode')
            . " ,phone=" . $this->postField('phone')
            . " ,website=" . $this->postField('website')
            . " ,collection=" . $this->postField('collection')
            . " ,level=" . $_POST['level']
            . " ,status=" . $this->postField('status')
            . " ,nworks=" . $this->postField('nworks')
            . " ,enddate=" . $this->postField('enddate')
            . " WHERE id=$id";

        $this->mysqli->query($sql)
            or die ("Error updating item " . mysqli_error($this->mysqli));
    }

    protected function deleteItem()
    {
        $id = $_GET['item'];
        $sql = "DELETE FROM users WHERE id=$id";

        $this->mysqli->query($sql)
            or die ("Error deleting item " . mysqli_error($this->mysqli));
    }

}

// -------------------------------------------
//  Entry point to page
// -------------------------------------------

    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);

    echo "<h3>Users</h3>";
    $lst = new UserList($mysqli);
    $lst->sqlShow("SELECT * FROM users");
    $lst->editPage("useredit.php");
    $lst->run(); 
?>
</body>
</html>
