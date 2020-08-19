<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	common.php
//              Common functions
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
/*
function setConfig()
function dbConnect($config)
function myError($msg)
function textToHTML ($stream)
function dispDate($dt)
function imageFit($record)
function footer()
*/

// error_reporting(E_ALL);

// ----------------------------------------------
//	Read the database access parameters from
//	the config file
//
// ----------------------------------------------
function setConfig()
{
    $hfile = fopen('config.txt', 'r');
    if (!$hfile)
        myError(ERR_COMMON_CONFIG, "Could not open config file");
    $config = array();
    while (!feof($hfile)) {
        $str = fgets($hfile);
        sscanf($str, '%s %s', $ky, $val);
        $config[$ky] = $val;
    }
    fclose ($hfile);
    $_SESSION['config'] = $config;
    return $config;
}

// ----------------------------------------------
//	Connect to the database
//
//	Parameter Configuration onject
//		(db connection values)
//
//	Returns	  MYSQL connection
// ----------------------------------------------
function dbConnect($config)
{
    $dbConnection = mysqli_connect 
        ($config['dbhost'], $config['dbuser'], $config['dbpw'], $config['dbname'])
        or myError (ERR_CONNECT, $config['dbname']);
    mysqli_select_db($dbConnection, $config['dbname']) 
        or myError(ERR_SELECT, 
            "Could not select database : " . mysqli_error($dbConnection));

    return $dbConnection;
}

// ---------------------------------------
//	Report error for this site
//
//	Parameter message id
// ---------------------------------------
function myError($errno, $msg)
{
    echo "<br><br>We are sorry, an error has occurred. Please send a "
        . "message to " . USER_EMAIL . " quoting error $errno and "
        . "the following message:";
    echo "<br><br>$msg<br>";
    die();
}

// ---------------------------------------
//	Format text into HTML
//
// ---------------------------------------
function textToHTML ($stream)
{

    $stream = str_replace("\n", "<br>", $stream);
    return $stream;
}

// -------------------------------------------
//	Convert SQL date to display format
//
//	Parameter	Date as yyyy-mm-dd
//
//	returns		Date as dd/yy/mm
// -------------------------------------------
function dispDate($dt)
{
    if ($dt == '')			// Null date
        return '';
    list($year, $mon, $day) = explode("-", $dt);
    $dtm = "$day/$mon/$year";
    return $dtm;
}

// -------------------------------------------
// Fit an image into a 3 across box
//
//	Parameter	Path to image
//
//	Returns		Display style to fit image
//
//	If aspect ration of image > that of
//	display box, fit to height, else width
// -------------------------------------------
function imageFit($image)
{
    if (is_dir($image)) {
        $style = "width:98%";
        return $style;
    }

    if (file_exists($image)) {	// Note. This may fail to trap if $image points to a directory
        $imSize = getImageSize($image);
        $imHeight = $imSize[1];
        $imWidth = $imSize[0];
    }
    else {
        $style = "width:98%";
        return $style;
    }

    $picRatio = $imHeight / $imWidth;		// Picture aspect ration
    $boxRatio = 400 / 340;			// And for the box
    if ($picRatio > $boxRatio)	
        $style = "height:400px";		// Fit to the height
    else
        $style = "width:98%;";          	// ... others at max width

    return $style;
}

// -------------------------------------------
//  Fetch and return the text of the footer
//
// -------------------------------------------
function footer()
{
    $fh = fopen('footer.html', 'r');
    $content = fread($fh, 4000);
    fclose($fh);

    return $content;
}

const PP_TEST = 0;		// In common master, Set this to 1 to use sandbox

const AWAY = "At exhibition until ";

const V_EXPIRED = "Sorry, the voucher has expired";
const V_INVALID = "Sorry, this code is not valid";
const V_VALID = "The code is valid:\n";
const V_DISCOUNT = "It gives a discount of ";
const V_FREESHIP = "There is no shipping charge";

const ERR_COMMON_CONFIG = 11;
const ERR_CONNECT1 = 12;
const ERR_SELECT = 13;
const ERR_COMMON_MENU = 14;
const ERR_COMMON_RECENT_TEXT = 15;
const ERR_COMMON_RECENT_PICTURES = 15;
const ERR_COMMON_COLLECT = 16;
const ERR_HOME_TEXT = 21;
const ERR_HOME_COLLECT = 22;
const ERR_COLLECT_LIST = 31;
const ERR_COLLECT_PICTURES = 32;
const ERR_COLLECT_ENLARGE = 39;
const ERR_ORD2_READ_PAINTINGS = 81;
const ERR_ABOUT_TEXT = 91;
const ERR_PM_WO_PREP = 101;
const ERR_PM_WO_BIND = 102;
const ERR_PM_UPDATE_PAINTINGS = 103;
const ERR_PM_INSERT_ORDER = 104;
const ERR_PM_CUSTOMER_EMAIL = 105;
const ERR_PM_ARTIST_EMAIL = 106;
const ERR_VOUCHER = 70;

const GEN_DSC = "Buy original art online from New Art for You";
const GEN_KWS = "Paintings online, buy art online UK, buy original art online UK";
const COL_DSC = "Original paintings online by ";
const COL_DSC2 = "Original artworks online from ";
const PIC_DSC = "Buy original art from ";
