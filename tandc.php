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

    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);
    $title = "New Art for You terms and conditions";
    showTop($title, $title);
    
    $content = "docs/T&C.html";
    $hFile = fopen($content, "r");
    $txt = fread($hFile, filesize($content));
    fclose($hFile);
    echo $txt;
    
    echo footer();
    ?>
