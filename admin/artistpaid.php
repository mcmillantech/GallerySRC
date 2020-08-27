<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	artistpaid.php
//		Return from Braintree
//
//  Author	John McMillan, McMillan Technology
// ------------------------------------------------------

    session_start();
    ini_set("display_errors", "1");
    error_reporting(E_ALL);
    require '../common.php';
    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);

    const TEST = 0;				// Set to 1 to skip emails
    const RERUN = 1;				// Set to 1 to skip Braintree
    require 'btroutines.php';
    
    $mode = $_GET['mode'];
    
    $fee = $_POST['fee'];
    $transRef = takePayment($gateway, $fee);
    switch($mode) {
        case "invoice":
                    // If payment fails, the script exits, so it's OK now
            echo "<p>Thank you. Your payment has been accepted</p>";
            payInvoice($transRef, $fee);
            break;
        case "number":
            $userId = $_SESSION['userId'];
            $nworks = $_GET['n'];
            postWorksChange($userId, $nworks, $transRef, $fee);
            echo "Thanks. We have updated your account<br><br>";
            break;
    }
    
    echo "<br><button onClick='window.location.assign(\"index.php\")'>Done</button>";

// ----------------------------------------
// Post the works change to the user table
// and write the order
// 
// ----------------------------------------
function postWorksChange($userId, $nworks2, $transRef, $fee)
{
    global $mysqli;
    
    $sql = "UPDATE users SET nworks=$nworks2 WHERE id=$userId";
    $errTxt = "There was a problem updating your record: $sql "
        . "Please pass this message to admin";
    $mysqli->query ($sql)
            or die($errTxt);

    $user = readUserRecord();
    writeOrder($user, $transRef, $fee);

}

// ----------------------------------------
//  Payment failure
//
//  Notify the customer
// ----------------------------------------
function doFailure($msg)
{
    echo "We are sorry, payment has not been accepted:<br>";
    echo "The message was $msg<br><br>";
    exit();
}

// -----------------------------------
//  Process successful invoice receipt
//
//  Update the user status and end date
// -----------------------------------
function payInvoice($transRef, $fee)
{

    $user = readUserRecord();
    updateUser($user);
    writeOrder($user, $transRef, $fee);
//    supportEmail($userName, $transRef);
}

// -------------------------------------
// Write the receipt to the order table
// 
// -------------------------------------
function writeOrder($user, $transRef, $fee)
{
    global $mysqli;
    
    $ref = getNextOrder();
    $userName = $user['fullname'];
    
    $dtSQL = date('Y-m-d');		// Makes today's date for SQL insertion
    $sql = "INSERT INTO orders (ref, name, user, addr1, postcode, status, "
            . "price, date, transref) "
        . "VALUES ($ref, '$userName', 99, 'artist', 'artist', 1, $fee, "
        . "'$dtSQL', '$transRef')";
    $mysqli->query($sql)
        or die ("Error writing order $sql");
}

// ----------------------------------------------
//  Update the user table for renewal
//
// ----------------------------------------------
function updateUser($user)
{
    global $mysqli;

    $userId = $_SESSION['userId'];
    $status = 1;
    
    $endDate = $user['enddate'];        // Update the end date
    $endMonth = substr($endDate, 5,2);
    $endYear = substr($endDate, 0, 4);
    $endMonth +=3;
    if ($endMonth > 12) {
        $endMonth -= 12;
        $endYear += 1;
    }
    
    $newEnd = sprintf("%4d-%02d", $endYear, $endMonth);
    echo "New $newEnd<br>";
    
    $sql = "UPDATE users SET status=1, enddate='$newEnd' WHERE id=$userId";
    $errTxt = "There was a problem updating your record: $sql "
        . "Please pass this message to admin";
    $mysqli->query ($sql)
            or die($errTxt);
}

// -------------------------------------
// Look up the user record
// 
// Returns the table record
// -------------------------------------
function readUserrecord()
{
    global $mysqli;

    $userId = $_SESSION['userId'];
    $sql = "SELECT * FROM users where id = $userId";
    $result = $mysqli->query($sql);
    if ($result=== FALSE)
        echo "There has been an error: $sql. Please pass this message to admin";
    $record = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    return $record;
}

// ----------------------------------------------
//  Generate next order number from system table
//
// ----------------------------------------------
function getNextOrder()
{
    global $mysqli;

    $result = $mysqli->query("SELECT * FROM system")
            or die ("System read error");
    $record = $result->fetch_array(MYSQLI_ASSOC);
    $ordId = $record['nextorder'] + 1;
    $sql = "UPDATE system SET nextorder = $ordId";
    $mysqli->query($sql);

    return $ordId;
}

/*
// ----------------------------------------
//  send email to site owner
//
//  Parameters	Artist name
// ----------------------------------------
function supportEmail($userName, $transRef)
{
    global $ref;
    
    $from = ARTIST;
    $replyTo = USER_EMAIL;
    $to = USER_EMAIL;

    $subject = 'Payment received from artist';
    $headers = "From: " . USER_EMAIL . "\r\n" .
        "Reply-To: " . USER_EMAIL . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: $from\r\n"
        . "Reply-To: $replyTo\r\n" 
        . "X-Mailer: PHP/" . phpversion();

    $message = "Hi\r\n\r\n"
        . "Payment has been received from $userName. "
        . "\r\nOrder reference is $ref"
        . "\r\nTransaction is $transRef";

    if (TEST == 0) {
        $reply = mail($to, $subject, $message, $headers);
        if (!$reply)
            myError(ERR_PM_CUSTOMER_EMAIL, "Email failed to send to $to<br>");
        echo "Mail sent to admin";
    }
    else {
        echo $message;
        echo "---- Customer mail -----";
    }

}
*/

?>
</body>
</html>

