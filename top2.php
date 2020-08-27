
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
    $config = setConfig();			// Get path to images
    $impath = $config['images'];


// --------------------------------------------------
//  Call point - set up the metadata and page heading
//  
//  Before calling, the view array ($dta) and
//  the image path must be set
//  
//  Parameters  meta title
//              page heading
//              meta description
// ---------------------------------------------------
function showTop($title, $heading, $dsc = "gen")
{
    global $impath, $dta;

    $dta = collectionsList($dta);	// Fetch the entries for collections pull down
    $dta["title"] = $title;
    $dta["heading"] = $heading;
    $dta["impath"] = $impath;
    if (array_key_exists('tags', $dta)) {
        $dta['keywords'] = $dta['tags'];
    } else {
        $dta['keywords'] = "";
    }
                                        // Set up the description
    if ($dsc == "gen") {                // Default, fetch from common.php
        $dta["metadsc"] = GEN_DSC;
    } else {
        $dta["metadsc"] = $dsc;
    }

    showView("topv.html", $dta);
}

// ----------------------------------------------
//	Show the list of collections
//
// ----------------------------------------------
function collectionsList($dta)
{
    global $mysqli;

//# option 11
    $sql = "SELECT u.status, c.* FROM collections c"
        ." JOIN users u ON u.collection=c.id"
        ." WHERE u.status=1 ORDER BY sequence";
//# alt 11
    $sql = "SELECT * FROM collections WHERE sequence > 0 ORDER BY sequence";
//# end 11
    $result = $mysqli->query($sql)
        or myError(ERR_COMMON_MENU, "Collections error " . $mysqli->error);

    $menu = array();				// Holds pull down list
    $mline = array();				// Holds a pull down line
    while ($coll = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $mline['id'] = urlencode($coll['name']);
        $mline['name'] = $coll['name'];
        array_push($menu, $mline);
    }
    $dta['collectMenu'] = $menu;
    return $dta;
}

?>



