<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	coledit.php
//		Edit collection
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
    session_start();
ini_set("display_errors", "1");
error_reporting(E_ALL);
    require_once "../common.php";

    $config = setConfig();					// Connect to database
    $mysqli = dbConnect($config);
    $cked = $config['ckeditor'];
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Gallery Collection Edit</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
<?php
    echo "<script src='$cked'></script>";
?>

<style>
.input
{
	position:			absolute;
	left:				200px;
}
</style>
<script>
function showFileFrame(which)
{
//	var el = document.getElementById("frame" + which);
    switch (which)
    {
        case 1:
            var el = document.getElementById("frame1");
             break;
        case 2:
            var el = document.getElementById("frame2");
            break;
    }
/*        if (which == 2)
            el.src = 'colImageFrame2.html';
        else
            el.src = 'colImageFrame1.html'; */ 
    el.style.visibility = "visible";
}

</script>
</head>

<body>
<?php
    require_once "adminmenus.php";
    require_once "DataEdit.php";

class colEdit extends DataEdit
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
            'name' => '',
            'image' => '',
            'sequence' => '',
            'search' => '',
            'uselowprice' => '',
            'text' => ''
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
        $sql = "SELECT * FROM collections WHERE id=$id";
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
            $action = "collist.php?mode=ins";
        else {
            $id = $_GET['item'];
            $action = "collist.php?mode=upd&item=$id";
        }

        $dta = $this->record;

        echo "<div style='width: 600px; margin-left:20px'>";
            echo "\n<form method='post' action='$action'>";
                $this->showLine('Name', $dta, 'name', 45);
                $this->showLine('Image File', $dta, 'image', 45);
                $this->showLine('Hover image', $dta, 'search', 45);
                $this->showLine('Sequence', $dta, 'sequence', 8);
                $this->showCheckBox('Use Ebay price', $dta, 'uselowprice');
//                $this->showTextEditor($dta['text']);
                $this->textArea('Text', $dta, 'text', 10, 60);
                echo "<button type='submit'>Post</button>";
            echo "\n</form>";
        echo "</div>";
            
        echo "<button onClick='showFileFrame(1)' "
            . "style='position:absolute; top:162px; left:510px;'>Upload</button>";
        echo "<div style='position:absolute; left:70px; top:300px; visibility: hidden'>";
            echo "<iframe id='frame1' style='background-color:white; height:300px; width:500px;' "
                . "src='colImageFrame1.html'></iframe> ";
        echo "</div>";

        echo "<button onClick='showFileFrame(2)' "
            . "style='position:absolute; top:200px; left:510px;'>Upload</button>";
        echo "<div style='position:absolute; left:70px; top:300px; visibility: hidden'>";
            echo "<iframe id='frame2' style='background-color:white; height:300px; width:500px;' "
                . "src='colImageFrame2.html'></iframe> ";
        echo "</div>";

    }

    // -------------------------------------------
    //  Show the CKEdit window
    //  
    //  Parameter   html text to be edited
    // -------------------------------------------
    private function showTextEditor($html)
    {
        
        echo "\n<span class='prompt'>Text</span><br>";
//        <span class='input'><textarea rows='10' cols='60' name='text' onChange='fldChange()'>
        echo "<div>";
            echo "\n<textarea name='text' id='editTA' rows='10' cols='60' "
                . "onChange='fldChange()'>"
                . "$html</textarea>";

            echo "\n<script>";
                echo "  CKEDITOR.replace( 'text' );";
            echo "  </script>";
        echo "<div>\n";
    }

    private function collectionList()
    {
        $ar = array();
        array_push($ar, '');

        $sql = "SELECT * FROM collections";
        $result = $this->mysqli->query($sql)
                or die ("Error reading link" . mysqli_error($this->mysqli));
        while ($coll = mysqli_fetch_array($result, MYSQLI_ASSOC))
        {
            array_push($ar, $coll['name']);
        }

        mysqli_free_result($result);
        return $ar;
    }
}


    echo "<h3>Edit Collection</h3>";
    $lst = new colEdit($mysqli);
    $lst->run();
?>
</body>
</html>

