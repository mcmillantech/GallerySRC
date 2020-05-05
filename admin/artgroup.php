<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	artgroup.php
//		Utilities
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------

function makeDropDown($page, $mysqli)
{
    $sql = "SELECT * FROM collections";
    $result = $mysqli->query($sql);
    $html = '';
    while ($record = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $html .= "<a href='$page.php?artist="
            . $record['id'] . "'>" . $record['name'] . "</a>\n";
    }
    $html .= "<a href='$page.php?artist=all'>All</a>\n";
    return $html;

}
/*
    session_start();
    require_once "../common.php";
    require_once "adminmenus.php";
    makeDropDown("collist");
*/
