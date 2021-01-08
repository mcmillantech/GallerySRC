<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	filter.php
//		Show paintings by filter
//
//  Author	John McMillan
//  Copyright   McMillan Technology 2019
// ------------------------------------------------------
    session_start();
    require_once "common.php";
    require "top2.php";

    $mysqli = dbConnect($config);
    $dta = array();
//echo "Filter<br>";
//    print_r($_POST);
    $sql = buildSQL();

    $result = $mysqli->query($sql)
        or myError(ERR_COLLECT_LIST, $mysqli->error);
/*    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $colId = $record['id'];
    $colName = $record['name'];
//    $sequence = $record['sequence']; */
    $title = "Online art from New Art for You";
    showTop($title, $title, '');
    $list = array();

    while ($pic = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $image = showOnePicture($pic);
        array_push($list, $image);
    }
    mysqli_free_result($result);

    $dta["list"] = $list;
    $dta['footer'] = footer();
    showView("filter.html", $dta);

// -------------------------------------
// -------------------------------------
function showOnePicture($pic)
{
    global $impath;

    $picName = $pic['name'];
    $altText = "Art work $picName";
    $pic["image"] = $impath . '/small/' . $pic['image'];    // Path to image file
    $pic['alttext'] = $altText;
    $pic["price"] = '&pound;' . sprintf('%.2f', $pic['priceweb'] / 100.0);
    $dsold = $pic['datesold'];
    $id = $pic['id'];

    if ($pic['quantity'] > 0)
        $pic["buy"] = "<div class='buyButton'>"
            . "<button onclick='buy($id)'>Buy this work...</button></div>";
    else
        $pic["buy"] = "<div style='color:red;font-size:120%;height:30px;'>"
            . "Sold</div>";
    return $pic;
}

// -------------------------------------
// Build the SQL string
// 
// -------------------------------------
function buildSQL()
{
                        // Fetch data from the filter bar
    $price = $_POST['selectPrice'];
    $colour = $_POST['selectColour'];
    $subject = $_POST['selectSubject'];
    $size = $_POST['selectSize'];
                        // SQL without any filters
    $where = '';
    $sql = "SELECT * FROM paintings WHERE 1";
    if ($price <> 'any') {
        list($low, $high)= explode(' ', $price);
        $low *= 100;
        $high *= 100;
        $where .= " AND priceweb >= $low AND priceweb <= $high";
    }
    if ($colour <> 'any') {
        $where .= " AND colour='$colour'";
    }
    if ($subject <> 'any') {
        $where .= " And subject='$subject'";
    }
    if ($price <> 'any') {      // Sort by price if present
        $where .= " ORDER BY priceweb";
    }
    $sql .= $where;
    
    return $sql;
}
    ?>
