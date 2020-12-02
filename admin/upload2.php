<?php
// ------------------------------------------------------
//  Project	OnLine Gallery
//  File	upload2.php
//		Upload CSV file
//
//  GaAuthor	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
uploadFile()
process($file)
processLine($line)
SQLDate($dt)
makeLinks($picId, $line)
makeCollectionsArray()
*/
	require_once "../common.php";
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Upload spreadsheet</title>
<link type="text/css" rel="stylesheet" href="Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
</head>

<body>
<h3>Gallery Web Site: Import</h3>

<?php

	$config = setConfig();					// Connect to database
	$dbCon = dbConnect($config);

	$file = uploadFile();
	echo "$file uploaded OK";
	$colls = makeCollectionsArray();		// A global array of the collections

	process ($file);

// ---------------------------------------
//	Upload the file
//
//	Requires posting from html input file
// ---------------------------------------
function uploadFile()
{
	$target_dir = "../data/";
	$target_file = $target_dir . basename($_FILES["csvFile"]["name"]);
	$fileType = pathinfo($target_file,PATHINFO_EXTENSION);

	if (strtolower($fileType) != 'csv')
		die ("File must be a CSV type");
   if (!move_uploaded_file($_FILES["csvFile"]["tmp_name"], $target_file)) 
        die ("There was an error uploading your file");

	return $target_file;
}

// ---------------------------------------
//	All being well, process the CSV file
//
// ---------------------------------------
function process($file)
{
	global $dbCon;

	$hFile = fopen($file, 'r')
		or die ("Failed to open the file");
									// Clear out the current set
	$sqlCk = "DELETE FROM paintings";
	mysqli_query($dbCon, $sqlCk)
		or die ("Error clearing old catalogue" . mysqli_error($dbCon));

	$sqlCk = "DELETE FROM links";
	mysqli_query($dbCon, $sqlCk)
		or die ("Error clearing old links" . mysqli_error($dbCon));

	$line = fgetcsv($hFile);					// Read the headings
	$ct = 0;
	while ($line = fgetcsv($hFile))
	{
		if ($line[0] != '')
			processLine($line);
//		if ($ct++ > 5) break;					// For debuggin
	}
	fclose ($hFile);
}

// ---------------------------------------
//	Process each row of spreadsheet
//
//	Extract the columns, build the SQL and
//	insert the record. Make the links to
//	the collections
// ---------------------------------------
function processLine($line)
{
	global $dbCon;

//	echo "<br>";
 
	$recent = ($line[3] == ''  ? 0 : 1);
	$year = $line[4];
	$name = addslashes($line[5]);
	$media = $line[6];
	$size = $line[7];
	$mount = $line[8];
	$tags = $line[9];
	$priceweb = $line[10] * 100;
	$priceebay = $line[11] * 100;
	$notes = addslashes($line[12]);
	$archive = ($line[16] == '' ? 0 : 1);
	$sold = $line[14];
	if ($sold > ' ')
		$sold = SQLDate($sold);
	else
		$sold = 'null';
	$image = addslashes($line[17]);
	$sequence = $line[18];
	if ($sequence == '')
		$sequence = 0;
	
	$sql = "INSERT INTO paintings "
		. "(recent, year, name, media, size, mount, tags, priceweb, priceebay,"
		. "notes, archive, image, seq, datesold) "
		. "VALUES "
		. "($recent, $year, '$name', '$media', '$size', '$mount', '$tags', "
		. "$priceweb, $priceebay, '$notes', $archive, '$image', $sequence, $sold)";

	mysqli_query($dbCon, $sql)
		or die ("Painting insert error " . mysqli_error($dbCon));
	$picId = mysqli_insert_id($dbCon);
	makeLinks($picId, $line);

//
}

function SQLDate($dt)
{
	$dt = str_replace('"', '', $dt);
	list($day, $mon, $year) = explode("/", $dt);
	$dtm = '"' . "$year-$mon-$day" . '"';
	return $dtm;
}


// ---------------------------------------
//	Build the links between paintings 
//	and collections
//
//	Collections are specified in
//	colums A to C of the spreadsheet
//
//	Uses global $colls - array of collections
//
//	Parameters
//		Id of painting
//		Spreadsheet row
// ---------------------------------------
function makeLinks($picId, $line)
{
	global $dbCon, $colls;

	for ($i=0; $i<3; $i++)
	{
		$sCol = $line[$i];
		if ($sCol == '')
			continue;
		$nCol = array_search($sCol, $colls, true);
		if ($nCol == false)
		{
			echo ("Invalid collection $sCol for " . $line[5]);
			return;
		}

		$sql = "INSERT INTO links (picture, collection) VALUES ($picId, $nCol)";
		mysqli_query($dbCon, $sql);
//			or die ("Error inserting link" . mysqli_error($dbCon));
	}
}

// ---------------------------------------------
//	Make an array of collections
//  Read the searches from the collections table
//	and copy into an array
//
// ---------------------------------------------
function makeCollectionsArray()
{
	global $dbCon;

	$arr = array();
	array_push($arr, 'xxx');				// Needed because array_search returns 0 for not found 
											// So create an element zero
	$sql = "SELECT * FROM collections";
	$result = mysqli_query($dbCon, $sql)
		or die ("Error reading collections " . mysqli_error($dbConn));
	while ($record = mysqli_fetch_array($result, MYSQLI_ASSOC))
		array_push($arr, $record['search']);
	mysqli_free_result($result);
	return $arr;
}		

?>
</body>
</html>
