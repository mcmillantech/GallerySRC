<?php
// ------------------------------------------------------
//  Project	Art Gallery
//	File	index.php
//		Home page
//
//  Author	John McMillan 
//  Copyright   McMillan Technology 2019
// ------------------------------------------------------
    session_start();
    require_once "common.php";
    require "top2.php";
                                        // From here on we're in mainPanel
    $dta = array();
    $dta['impath'] = $impath;

    $mysqli = dbConnect($config);
    $title = "Art by " . ARTIST;
    $title = ARTIST;
    $title = "Online art for sale from " . ARTIST;
    $heading = "Art sales from " . ARTIST;
    $dta['tags'] = GEN_KWS;
    showTop($title, $heading);
//    echo "<h2>The Artists</h2>";
    echo "<h2>Third NAFY virtual exhibition<br>18th November to 14th December 2020</h2>";

    $dta['imgrecent'] = $impath . '/small/Recent.jpg';

    fetchData();

                            //	Show the images for the collections
    $colls = array();
    $sql = "SELECT u.status, c.* FROM collections c"
        ." JOIN users u ON u.collection=c.id"
        ." WHERE u.status=1 ORDER BY sequence";
    $result = $mysqli->query($sql)
        or myError(ERR_HOME_COLLECT, "Collections error " . $mysqli->error);
    while ($coll = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $ar = showOneImage($coll);
        array_push($colls, $ar);
    }
    $dta["colls"] = $colls;

    mysqli_free_result($result);

    $dta['title'] = $title;
    $dta['footer'] = footer();
    showView("indexv.html", $dta);

// ------------------------------------------------------
//	Fetch text from the database
// ------------------------------------------------------
function fetchData()
{
    global $mysqli, $dta;

    $sql = "SELECT * FROM text WHERE type='hometext'";
    $result = $mysqli->query($sql)
            or myError(ERR_HOME_TEXT, "Text table error " . $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $dta['hometext'] = $record['text'];
    mysqli_free_result($result);

    $sql = "SELECT * FROM text WHERE type='signupprompt'";
    $result = $mysqli->query($sql)
            or myError(ERR_HOME_TEXT, "Text table error " . $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $dta['signup'] = $record['text'];
    mysqli_free_result($result);

    $sql = "SELECT * FROM text WHERE type='signupsubject'";
    $result = $mysqli->query($sql)
            or myError(ERR_HOME_TEXT, "Text table error " . $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $dta['signupsubject'] = $record['text'];
    mysqli_free_result($result);

    $sql = "SELECT * FROM text WHERE type='signuptext'";
    $result = $mysqli->query($sql)
            or myError(ERR_HOME_TEXT, "Text table error " . $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $dta['signuptext'] = str_replace ("\n", "%0d%0a", $record['text']);
    mysqli_free_result($result);

    $sql = "SELECT * FROM text WHERE type='homeimage'";
    $result = $mysqli->query($sql)
            or myError(ERR_HOME_TEXT, "Text table error " . $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $dta['imghome'] = str_replace ("\n", "%0d%0a", $record['text']);
    mysqli_free_result($result);

    $dta['mailto'] = USER_EMAIL;
    return;
}

// ----------------------------------------------
//  Place an element to hold a collection image
//
//  Parameter	DB record for the artist / collection
//
//  Create a collectImage span element to contain:
//	A col<i> div for the picture
//	A hidded colTxt div for the details
// ----------------------------------------------
function showOneImage($pic)
{
    global $impath;

    $ar = array();

    $name = $pic['name'];
    $id = $pic['sequence'];
    $ar['id'] = $id;
    $ar['img'] = $impath . '/' . $pic['image'];
    $ar['hover'] = $impath . '/' . $pic['search'];
    $ar['alttext'] = "Art sales from $name";

    $imid = 'coli' . $id;		// Make the ID of the image
    $ar['imid'] = $imid;
    $ar['id2'] = $imid . 'm';
    $ar['idtxt'] = 'coltx' . $id;
    $ar['name'] = $name;
                                        // The collectImage has the handlers
    return $ar;
}

// ----------------------------------------------
//	Fetch the image to use for the collection
//
//	Parameter	Collection id
//
//	Returns		Name of image file
//
//	Find the latest picture in this collection
//	Return its name
// ----------------------------------------------
function getImageName($id)
{
    global $mysqli;

    $sql = "SELECT l.*, p.name, dateset, image FROM links l "
        . "JOIN paintings p on p.id = l.picture "
        . "WHERE l.collection=$id "
        . "ORDER BY p.dateset DESC";
    $result = $mysqli->query($sql)
            or die("Collections error $sql: " . $mysqli>error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $image = $record['image'];

    mysqli_free_result($result);

    return $image;
}

?>

