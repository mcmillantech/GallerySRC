<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	information.php
//  		Information menu
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
    require_once "common.php";
    require "top2.php";

    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);
    
    $which = $_GET['which'];
    switch ($which)
    {
        case 'terms':
            $title = "New Art for You terms and conditions";
            $content = "docs/T&C.html";
            break;
        case 'firststeps':
            $title = "New Art for You: first steps";
            $content = 'docs/firststeps.html';
            break;
        default :
            exit;
    }
    
    showTop($title, $title);
    
//    $content = "docs/T&C.html";
    $hFile = fopen($content, "r");
    $txt = fread($hFile, filesize($content));
    fclose($hFile);
    echo $txt;
    
    echo footer();
    ?>
