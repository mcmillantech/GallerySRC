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

    $sequence = $_GET['col'];			// Use filter_var()
    $coll = $_GET['col'];
//   $_SESSION['collection'] = $sequence;

    $sql = "SELECT * FROM collections WHERE name='$coll'";
    $result = $mysqli->query($sql)
        or myError(ERR_COLLECT_LIST, $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $colId = $record['id'];
    $colName = $record['name'];
    $sequence = $record['sequence'];
    $_SESSION['collection'] = $colId;          // Store for New Art
    
    $title = "Art by $colName at " . ARTIST;
    $uselowprice = $record['uselowprice'];
    showTop("Online art by $colName at " . ARTIST, $title);

    $dta["colImage"] = $impath . '/' . $record['image'];
    $dta['colAlt'] = $title;
    $dta['colText'] = $record['text'];
    mysqli_free_result($result);

    if ($sequence == 0) {
        $sql = "SELECT * FROM paintings WHERE recent=1 ORDER BY dateset DESC";
    }
    else {
        $sql = "SELECT l.*, p.* FROM links l "
            . "JOIN paintings p ON p.id = l.picture "
            . "WHERE l.collection = $colId AND p.deleted=0 "
            . "ORDER BY p.seq";
    }

    $result = $mysqli->query($sql)
        or myError(ERR_COLLECT_PICTURES, $mysqli->error);

    $list = array();
    while ($pic = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $image = showOnePicture($pic, $colName, $uselowprice);
        $image['collection'] = $sequence;
        array_push($list, $image);
    }
    mysqli_free_result($result);

    $dta["list"] = $list;
    $dta['footer'] = footer();
    showView("collections.html", $dta);

// ----------------------------------------------
//  Set up the details for a picture in the
//  main panel:
//          path to image file
//          format the price
//          "Buy" button or "sold" 
//
//  Parameter	Array of date for the painting
//
//  Returns	Modified array, for the view
// ----------------------------------------------
function showOnePicture ($pic, $colName, $uselowprice)
{
    global $impath;

    $picName = $pic['name'];
    $altText = "Art work $picName by $colName";
    $pic["image"] = $impath . '/small/' . $pic['image'];    // Path to image file
    $pic['alttext'] = $altText;

    if ($uselowprice)
        $pic["price"] = '&pound;' . sprintf('%.2f', $pic['priceebay'] / 100.0);
    else
        $pic["price"] = '&pound;' . sprintf('%.2f', $pic['priceweb'] / 100.0);

    $dsold = $pic['datesold'];
    $id = $pic['id'];

    if ($pic['away'] != null) {
        $pic["buy"] = "<p>" . AWAY . dispDate($pic['away']) . "<br><br>";;
    }
    else {
        if ($pic['quantity'] > 0)
            $pic["buy"] = "<div class='buyButton'>"
                . "<button onclick='buy($id)'>Buy this work...</button></div>";
        else
            $pic["buy"] = "<div style='color:red;font-size:120%;height:30px;'>"
                . "Sold</div>";
    }
	return $pic;
}

