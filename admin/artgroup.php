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
    $html .= "<a href='$page.php?artist=0'>Admin</a>\n";
    $html .= "<a href='$page.php?artist=99'>All</a>\n";
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

    if ($id==99) {
        $sql = "SELECT l.*, c.id, c.name as artist, p.* FROM collections c "
            . "Join links l ON l.collection = c.id "
            . "JOIN paintings p ON p.id = l.picture "
            . "ORDER BY l.collection, p.dateset DESC";
    }
    else {          // Superadmin, bas passed artist from the menu
        $userLevel = $_SESSION['userLevel'];
        if ($userLevel == 3) {
            $where = "WHERE c.id='$id' ";
        }
        else {      // The artist is looged on
                    // Fetch the collection id from the user table
            $col = $_SESSION['loggedColl'];
            $where = "WHERE l.collection=$col ";
        }

        $sql = "SELECT l.*, c.id, c.name as artist, p.* FROM collections c "
            . "Join links l ON l.collection = c.id "
            . "JOIN paintings p ON p.id = l.picture ";
        $sql .=  $where . "ORDER BY p.deleted, p.seq";

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
    $id = getArtistId();

    if ($id==99) {                      // Orders for all artists
        $sql = "SELECT r.ref, r.date, r.price, r.status, r.shipped, r.user, "
            . "u.fullname as artist, r.name, r.transref "
            . "FROM orders r "
            . "JOIN users u ON u.id = r.user "
            . "ORDER BY user, r.date DESC";
    } else if ($id==0) {                // Orders for admin (artist fees)
        $sql = "SELECT r.ref, r.date, r.price, r.status, r.shipped, r.user, "
            . "'admin' as artist, r.name, r.transref "
            . "FROM orders r "
            . "WHERE user=99 "
            . "ORDER BY r.date DESC";
    } else {                            // For this collection
        $sql = "SELECT r.ref, r.date, r.price, r.status, r.shipped, r.user, "
            . "name as artist, r.transref "
            . "FROM orders r "
            . "WHERE user=$id "
            . "ORDER BY r.date DESC";
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
        $id = $_SESSION['userId'];

    return $id;    
}

function userFromCollection($coll)
{
    global $mysqli;
    
    $sql = "SELECT * FROM users WHERE collection=$coll";
    $result = $mysqli->query($sql)
            or die ("Error reading users $sql");
    $record =  mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    return $record['id'];
}
