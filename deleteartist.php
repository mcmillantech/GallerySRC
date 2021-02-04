<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require "common.php";

$config = setConfig();
$mysqli = dbConnect($config);

echo "<h3>Delete artist</h3>";
$user = $_GET['user'];
$mode = $_GET['mode'];
if ($mode == 2) {
    doDelete ($user, $mysqli);
    exit;
}
$sqlu = "SELECT * FROM users WHERE id=$user";
echo " $sqlu ";
    $resultu = $mysqli->query($sqlu);
    $recordu = mysqli_fetch_array($resultu, MYSQLI_ASSOC);
    $artist = $recordu['collection'];
    $name = $recordu['fullname'];

echo "$name $artist";
$check = '"' . $name . '"';
echo "\n<button onClick='checka($artist, $check)'>Go</button>\n";

function doDelete ($user, $mysqli)
{
    $sql = "SELECT l.*, p.* FROM links l "
       . "JOIN paintings p ON p.id = l.picture "
        . "WHERE l.collection = $user";
    echo "$sql <br>";
    $result = $mysqli->query($sql);
    while ($pic = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        echo $pic['name'] . ', ';
        $pic = $pic['picture'];
        $sql1 = "DELETE from paintings WHERE id = $pic";
        echo "$sql1<br>";
        $mysqli->query($sql1)
                or die ("Error in paintings");
    }

    $sql2 = "DELETE FROM links WHERE collection = $user";
    echo "$sql2<br>";
    $mysqli->query($sql2)
            or die ("Error in coll");
    
}

?>
<script>
    function checka(artist, name)
    {
        if (confirm("Delete " + name)) {
            window.location.href = "deleteartist.php?mode=2&user=" + artist;
        }
    }
</script>