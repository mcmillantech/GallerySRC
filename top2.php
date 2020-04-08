
<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	top.php
//      	HTML <head>, Banner, menu and title
//
//  This opens the container div and presents
//  the banner and menu divs. On leaving, the
//  html is within the container div
//
//  Author	John McMillan, McMillan Technology
// ------------------------------------------------------

    require_once "view.php";
    $config = setConfig();				// Get path to images
    $impath = $config['images'];


// ----------------------------------------------
//	Call point
// ----------------------------------------------
function showTop($title, $heading)
{
    global $impath;

    $dta = array();

    $dta = collectionsList($dta);		// Fetch the entries for collections pull down
    $dta["title"] = $title;
    $dta["heading"] = $heading;
    $dta["impath"] = $impath;
    showView("topv.html", $dta);
}

// ----------------------------------------------
//	Show the list of collections
//
// ----------------------------------------------
function collectionsList($dta)
{
    global $mysqli;

    $sql = "SELECT * FROM collections WHERE sequence > 0 ORDER BY sequence";
    $result = $mysqli->query($sql)
        or myError(ERR_COMMON_MENU, "Collections error " . $mysqli->error);

    $menu = array();				// Holds pull down list
    $mline = array();				// Holds a pull down line
    while ($coll = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $mline['id'] = $coll['sequence'];
        $mline['name'] = $coll['name'];
        array_push($menu, $mline);
    }
    $dta['collectMenu'] = $menu;
    return $dta;
}

?>



