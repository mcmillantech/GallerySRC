<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	about.php
//  		About the artist
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
    require_once "common.php";
    require "top2.php";

    $config = setConfig();					// Connect to database
    $mysqli = dbConnect($config);
    showTop("About " . ARTIST, ARTIST);
    $dta = array();

    $sql = "SELECT * FROM text WHERE type='abouttext'";
    $result = $mysqli->query($sql)
            or myError(ERR_ABOUT_TEXT, $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $dta['text'] = $record['text'];
    
    $sql2 = "SELECT * FROM text WHERE type='aboutimage'";
    $result = $mysqli->query($sql2)
            or myError(ERR_ABOUT_TEXT, $mysqli->error);
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $dta['image'] = $record['text'];
    
    $dta['footer'] = footer();

    showView("about.html", $dta);

?>
