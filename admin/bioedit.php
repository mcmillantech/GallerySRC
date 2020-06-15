<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	admin/bioedit.php
//
//  Author	John McMillan, McMillan Technolo0gy
// ------------------------------------------------------
    session_start();
    require_once "../common.php";

    $config = setConfig();				// Connect to database
    $mysqli = dbConnect($config);
    $cked = $config['ckeditor'];
	
?>
<!DOCTYPE html>

<html lang="en-GB">
<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Art site Admin</title>
<link type="text/css" rel="stylesheet" href="../Gallery.css">
<link type="text/css" rel="stylesheet" href="../custom.css">
<script src="../Cunha.js"></script>
<?php
    echo "<script src='$cked'></script>";

    echo "</head>";
    echo "<body>";
    
    include "adminmenus.php";
    echo "<h2>Edit artist bio</h2>";

    $col = $_SESSION['loggedColl'];
    $sql = "SELECT text FROM collections WHERE id=$col";
    $result = $mysqli->query($sql)
        or die("Text table error " . $mysqli->error());
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);

    $html = $record['text'];
//    echo $html;

    echo "<div style='margin-left:50px;width:700px;'>";
    echo "<form id='edForm' action='biopost.php?col=$col' method='post'>";
    echo "<div>";
    echo "\n<textarea name='htmltext' id='editTxt' rows='25' cols='40'>$html</textarea>";
    ?>
    <script>
      CKEDITOR.replace( 'htmltext' );
      </script>
    </div>
    <br><br>
    <button type='submit'>Post</button>
    </form>
    </div>
</body>
</html>


