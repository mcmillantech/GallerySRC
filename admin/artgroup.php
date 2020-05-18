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
    $id = getArtistId();
                                // First case. Superadmin is logged on
                                // , has picked all artists
    if ($id=='all') {
        $sql = "SELECT l.*, c.id, c.name as artist, p.* FROM collections c "
            . "Join links l ON l.collection = c.id "
            . "JOIN paintings p ON p.id = l.picture "
            . "ORDER BY l.collection, p.dateset DESC";
    }
    else {          // Still superadmin, bas passed artist from the menu
        $userLevel = $_SESSION['userLevel'];
        if ($userLevel == 3) {
            $where = "WHERE c.id='$id' ";
        }
        else {      // The artist is looged on
                    // Fetch the collection if from the user table
            $sql1 = "SELECT collection FROM users WHERE id=$id";
            $result = $mysqli->query($sql1);
            $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $col = $record['collection'];
            $where = "WHERE l.collection=$col ";
        }

        $sql = "SELECT l.*, c.id, c.name as artist, p.* FROM collections c "
            . "Join links l ON l.collection = c.id "
            . "JOIN paintings p ON p.id = l.picture ";
        $sql .=  $where . "ORDER BY p.dateset DESC";

    }
// echo "$sql<br>";
    return $sql;
}

// ----------------------------------------------
// Make SQL to list orders 
// 
// Parameter    DB connection
// ----------------------------------------------
function orderListSql($mysqli)
{
    $id = getArtistId();
    
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

// ----------------------------------------------
// Fetch the territory names for a (group) artist
// 
// Parameter    Artist id
// 
// Returns      Array of names
// ----------------------------------------------
function getTerritories($artist)
{
    global $mysqli;
    
    $sql = "SELECT territory1, territory2, territory3, territory4 "
//        . "FROM users WHERE id = $artist";
        . "FROM users WHERE collection = $artist";

    $result = $mysqli->query($sql)
        or die ("Error getting territories");
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    return $record;
}

// ----------------------------------------------
// Fetch the id for a group artist
// 
// From the session
// ----------------------------------------------
function getArtistId()
{
    $userLevel = $_SESSION['userLevel'];
    if ($userLevel == 3)
        $id = $_GET['artist'];
    else 
        $id = $_SESSION['artistId'];

    return $id;    
}
       
