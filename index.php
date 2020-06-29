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
//# option 11
    showTop("Online art for sale from NewArtForYou", "New Art for You: artists");
//# alt 11
    showTop($title, $title);
//# end 11

    $dta['imgrecent'] = $impath . '/small/Recent.jpg';

    fetchData();

                            //	Show the images for the collections
    $colls = array();
    $sql = "SELECT * FROM collections WHERE sequence > 0 ORDER BY sequence";
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
//  Parameter	DB record for the picture
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

