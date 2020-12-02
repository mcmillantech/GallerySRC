<!DOCTYPE html>
<html>
<body>
<?php

session_start();
session_unset();
session_destroy();
echo "<p>You have been logged out</p>";

echo "<p><button type='button' onClick='window.location=\"index.php\" '>Home</button></p>";
?>

</body>
</html>

