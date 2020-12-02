<?php
    session_start();
// ------------------------------------------------------
//  Project	Lupe Cunha Admin
//	File	admin/index.php
//			Home page
//
//	Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
	require_once "../common.php";
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Art site Admin</title>
<link type="text/css" rel="stylesheet" href="../Cunha.css">
<script src="../Cunha.js"></script>

</head>
<body onload="adminLoad()">
<h1>Control Panel</h1>
<?php
	include "adminmenus.php";
?>
</body>
</html>
