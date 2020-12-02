<?php

session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html>
<body>
    <p>You have been logged out</p>

    <p><button type='button' onClick='window.location=\"index.php\" '>Home</button></p>

</body>
</html>

