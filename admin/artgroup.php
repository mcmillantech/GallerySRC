<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	artgroup.php
//		Utilities
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------

// ----------------------------------------------
// Make one admin drop down menu
// 
// Parameter    Target page
//              DB connection
//
//  Return      HTML for the menu
// ----------------------------------------------
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

// ----------------------------------------------
// Make SQL to list paintings 
// 
// Parameter    DB connection
// ----------------------------------------------
function picListSql($mysqli)
{
    $id = $_GET['artist'];
    
    if ($id=='all') {
        $sql = "SELECT l.*, c.id, c.name as artist, p.* FROM collections c "
            . "Join links l ON l.collection = c.id "
            . "JOIN paintings p ON p.id = l.picture "
            . "ORDER BY l.collection, p.dateset DESC";
    }
    else {
        $sql = "SELECT l.*, c.id, c.name as artist, p.* FROM collections c "
            . "Join links l ON l.collection = c.id "
            . "JOIN paintings p ON p.id = l.picture "
            . "WHERE c.id='$id' "
            . "ORDER BY p.dateset DESC";
    }

    return $sql;
}

// ----------------------------------------------
// Make SQL to list orders 
// 
// Parameter    DB connection
// ----------------------------------------------
function orderListSql($mysqli)
{
    $id = $_GET['artist'];
    
    if ($id=='all') {
        $sql = "SELECT l.collection, c.name as artist, r.*, p.name as wrk "
            . "FROM orders r "
            . "JOIN paintings p ON p.id = r.product "
            . "JOIN links l ON l.picture = r.product "
            . "JOIN collections c ON c.id=l.collection "
            . "ORDER BY date DESC";
        
    } else {
        $sql = "SELECT l.collection, c.name as artist, r.*, p.name as wrk "
            . "FROM orders r "
            . "JOIN paintings p ON p.id = r.product "
            . "JOIN links l ON l.picture = r.product "
            . "JOIN collections c ON c.id=l.collection "
            . "WHERE l.collection=$id "
            . "ORDER BY l.collection, date DESC";
    }
    
    return $sql;
}
       
/*
    session_start();
    require_once "../common.php";
    require_once "adminmenus.php";
    makeDropDown("collist");
*/
