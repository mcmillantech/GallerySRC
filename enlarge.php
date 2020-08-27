<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	enlarge.php
//		Show large picture
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
    require_once "common.php";
    require "top2.php";

    $mysqli = dbConnect($config);

    $id = $_GET['id'];
    $sql = "SELECT * FROM paintings WHERE id=$id";
    $result = $mysqli->query($sql)
            or myError(ERR_COLLECT_ENLARGE, $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $title = $record['name'];
    if ($record['tags'] != "") {
        $dta['keywords'] = $record['tags'];
        $dta['tags'] = $record['tags'];
    }
    mysqli_free_result($result);

    showTop($title, $title);
    showImage($id, $record);

// ---------------------------------------------
//  Show the image
//  
// ---------------------------------------------
function showImage($id, $record)
{
    global $impath;

    $img = $impath . '/large/' . $record['image'];
    $col = $_GET['col'];
    $dsold = $record['datesold'];
    echo "\n<div id='mainPanel'>";
        echo "<div id='largeImage'>";
            echo "<img src='$img'  style='width:98%;' onload='loadLargeImage(this)' onClick='back($col)'>";
            if ($record['tags'] != "") {
                echo "Tags " . $record['tags'] . "<br>";
            }
            if ($dsold == null)
                echo "<p><button onClick='buy($id)'>Buy</button>&nbsp;&nbsp;";
            else
                echo "<p style='color:red;font-size:150%'>Sold</p><p>";
            echo "<button onClick='back($col)'>Back to collection</button></p>";
        echo "</div>";
    echo "</div>";
}

?>
	</div>
<script>
function back(col)
{
    var str = "collections.php?col=" + col;
    document.location = str;
}

</script>

</body>
</html>
