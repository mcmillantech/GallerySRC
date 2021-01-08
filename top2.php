
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
function showTop($title, $heading, $dsc = "gen", $showFilter="none")
{
    global $impath, $dta;

    $dta = collectionsList($dta);	// Fetch the entries for artists pull down
    $dta["title"] = $title;
    $dta["heading"] = $heading;
    $dta["impath"] = $impath;
                                        // Search drop downs
    $dta['colours'] = makeSelection('colours');
    $dta['prices'] = makeSelection('prices');
    $dta['subjects'] = makeSelection('subjects');
    $dta['sizes'] = makeSelection('sizes', 'blank');
    
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
    $dta['showFilter'] = $showFilter;

    showView("topv.html", $dta);
}

// ----------------------------------------------
//	Show the list of collections
//
// ----------------------------------------------
function collectionsList($dta)
{
    global $mysqli;

    $sql = "SELECT u.status, c.* FROM collections c"
        ." JOIN users u ON u.collection=c.id"
        ." WHERE u.status=1 ORDER BY sequence";
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



