<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	collections.php
//		Show collections
//
//  Author	John McMillan
//  Copyright   McMillan Technology 2019
// ------------------------------------------------------
/*
function showOnePicture ($pic, $uselowprice)
*/
?>
<?php
    session_start();
    require_once "common.php";
    require "top2.php";

    $mysqli = dbConnect($config);
    $dta = array();
    $dta['impath'] = $impath;

    $title = "Art by " . ARTIST;
    showTop($title, $title);

    fetchData();

/*    $sql = "SELECT * FROM collections WHERE sequence=$sequence";
    $result = $mysqli->query($sql)
        or myError(ERR_COLLECT_LIST, $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $colId = $record['id'];
    $title = "Collection: " . $record['name'];
    $uselowprice = $record['uselowprice'];
    showTop("Art by " . ARTIST, $title);

    $dta["colImage"] = $impath . '/small/' . $record['image'];
    $dta['colText'] = $record['text'];
    mysqli_free_result($result);
*/
    $sql = "SELECT * FROM paintings ORDER BY dateset DESC";

    $result = $mysqli->query($sql)
        or myError(ERR_COLLECT_PICTURES, $mysqli->error);

    $list = array();
    while ($pic = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $image = showOnePicture($pic);
        $image['collection'] = -1;
        array_push($list, $image);
    }
    mysqli_free_result($result);

    $dta["list"] = $list;
    $dta['footer'] = footer();
//    print_r($dta);
    showView("index2.html", $dta);

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
    $dta['text'] = $record['text'];
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

    $dta['mailto'] = USER_EMAIL;
    return;
}

// ----------------------------------------------
//	Set up the details for a picture in the
//	main panel:
//			path to image file
//			format the price
//			"Buy" button or "sold" 
//
//	Parameter	Array of date for the painting
//
//	Returns		Modified array, for the view
// ----------------------------------------------
function showOnePicture ($pic)
{
    global $impath;

    $pic["image"] = $impath . '/small/' . $pic['image']; // Path to image file

    $pic['style'] = imageFit($pic["image"]);		// Fit image 

//    if ($uselowprice)
//        $pic["price"] = '&pound;' . sprintf('%.2f', $pic['priceebay'] / 100.0);
//    else
        $pic["price"] = '&pound;' . sprintf('%.2f', $pic['priceweb'] / 100.0);

    $dsold = $pic['datesold'];
    $id = $pic['id'];

//	if ($dsold == null)			// Now use qty
    if ($pic['away'] != null) {
        $pic["buy"] = "<p>" . AWAY . dispDate($pic['away']) . "<br><br>";;
    }
    else {
        if ($pic['quantity'] > 0)
            $pic["buy"] = "<p><button onClick='buy($id)'>Buy</button><br><br>";
        else
            $pic["buy"] = "<p style='color:red;font-size:140%'>Sold";
//	echo $pic['buy'] . "<br>";
    }
	return $pic;
}

