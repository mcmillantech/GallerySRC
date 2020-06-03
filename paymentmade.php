<?php
// ------------------------------------------------------
//  Project	Artist Gallery
//  File	paymentmade.php
//		Return from Braintree
//
//	Author	John McMillan, McMillan Technology
// ------------------------------------------------------
/*
function takePayment($gateway)
function doFailure($msg)
function doSuccess()
function sendCustomerEmail($message, $order)
function sendArtistEmail($order)
function buildEmail($order)
function writeOrder($order)
*/

    session_start();
    ini_set("display_errors", "1");
    error_reporting(E_ALL);
    require 'common.php';
    require_once 'admin/artgroup.php';;
    $config = setConfig();			// Connect to database
    $mysqli = dbConnect($config);

    require 'top2.php';
    showTop("Buy artwork", "Purchase artwork");
    require_once "bootstrap.php";

    const TEST = 0;				// Set to 1 to skip emails
    const RERUN = 0;				// Set to 1 to skip Braintree

    $gateway = new Braintree_Gateway(
        [
            'environment' => $environment,
            'merchantId' => $merchantId,
            'publicKey' => $publicKey,
            'privateKey' => $privateKey
        ]
    );

    if (RERUN) {
        doSuccess('981j6c7k');
        return;
    }
    else if (RERUN == 0) 
        takePayment($gateway);
/*
    if (TEST) {
        doSuccess();
        return;
    }
    else
        takePayment($gateway);
*/
    echo "<br><button onClick='window.location.assign(\"index.php\")'>Done</button>";

// ----------------------------------------
//	Process Braintree payment
//	and notify customer
//
// ----------------------------------------
function takePayment($gateway)
{					// Instantiate a Braintree Gateway 

    $nonceFromTheClient = $_POST["nonce"];
    var_dump($nonceFromTheClient);           // Then, create a transaction:;
    $result = $gateway->transaction()->sale([
        'amount' => $_POST["amount"],
        'paymentMethodNonce' => "$nonceFromTheClient",
        'options' => [ 'submitForSettlement' => true ]
    ]);

    if ($result->success) {
        $transaction = $result->transaction;
        $transRef = $transaction->id;
        doSuccess($transRef);
    } else if ($result->transaction) {
        doFailure($result->transaction->processorResponseText);
    } else {
        $msg = $result->message;
        doFailure($msg);
    }
}

// ----------------------------------------
//	Payment failure
//
//	Notify customer
// ----------------------------------------
function doFailure($msg)
{

    echo "We are sorry, payment has not been accepted:<br>";
    echo "The message was $msg<br><br>";
    $email = USER_EMAIL;

    echo "Please try again or contact " . ARTIST_FNAME . " on "
            . "<a href='$email'>$email</a>";

    $price = $_SESSION['order']['price'];
    $return = "carddetails.php?price=$price";
    echo "<br><button onClick='window.location.assign(\"$return\")'>Try again</button><br>";

}

// -----------------------------------
//  Process successful receipt
//
// -----------------------------------
function doSuccess($transRef)
{
    global $config;

    $order = $_SESSION['order'];
    $picture = $order['picture'];

                                        // Present on screen message to customer	
    echo "Thank you for your order for <i>$picture</i><br><br>";
    echo "Your reference is " . $order['ref'] . "<br><br>";
    echo "It will be shipped to "
        . $order['addr1'] . ", "
        . $order['addr2'] . ", "
        . $order['addr3'] . ", "
        . $order['addr4'] . ", "
        . $order['pcode']
        . "<br><br>";
    $cost = number_format($order['price'], 2);
    echo "Price of painting &pound;$cost<br>";
    $cost = number_format($order['shipping'], 2);
    echo "Shipping cost &pound;$cost<br>";

                    // -----  Generate email to client
    $htmlText = buildEmail($order);
    sendCustomerEmail($htmlText, $order);
    sendArtistEmail($order);

    $cost = number_format($order['price'], 2);
    echo "<p>We have taken &pound;$cost from your card</p>";
    echo "<p>We have sent a confimation and receipt to " . $order['email'];
    echo "\n";

    writeOrder($order, $transRef);
}

// ----------------------------------------
//	sendCustomerEmail
//
//	Send the confirmation email
//
//	Parameters	HTML of message body
//				Address of recipient
// ----------------------------------------
function sendCustomerEmail($message, $order)
{
    $from = ARTIST;
    $replyTo = USER_EMAIL;

    $subject = 'Thank you for ordering art';
    $to = $order['email'];
    $name = $order['name'];
    $ref = $order['ref'];

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
    $headers .= "From: $from\r\n"
            . "CC: " . USER_EMAIL . "\r\n" 
        . "Reply-To: $replyTo\r\n" 
        . "X-Mailer: PHP/" . phpversion();

    if (TEST == 0) {
        $reply = mail($to, $subject, $message, $headers);
        if (!$reply)
            myError(ERR_PM_CUSTOMER_EMAIL, "Email failed to send to $to<br>");
    }
    else {
        echo $message;
        echo "---- Customer mail -----";
    }

}

// ----------------------------------------
//	sendArtistEmail
//
// ----------------------------------------
function sendArtistEmail($order)
{
    $picture = $order['picture'];
    
    $subject = 'Order received for painting';
    $to = USER_EMAIL;
    $headers = "From: " . USER_EMAIL . "\r\n" .
        "Reply-To: " . USER_EMAIL . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    $name = $order['name'];
    $ref = $order['ref'];
    $cost = number_format($order['total'], 2);
                                    // I changed this for the group
                                    // I need to build into option 11
    $message = "Hi\r\n\r\n"
        . "An order has been made to newartforyou.co.uk for your work.\r\n"
        . "<b>Name of work</b>  $picture\r\n\r\n"
        . "<b>Customer name</b>  $name\r\n\r\n"
        . "The order reference is $ref\r\n\r\n"
        . "Please see the order on your dashboard: " . WEBSITE 
        . "/admin/vieworder.php?ref=$ref ";
                                // Test message
    if (TEST == 0) {
        $reply = mail($to, $subject, $message, $headers);
        if (!$reply)
            myError(ERR_PM_ARTIST_EMAIL, "Email failed to send to $to<br>");
    }
    else {
        echo "---- Artist mail -----";
        echo $message;
    }
}

// --------------------------------------------
//	Build the text of confirmation email
//
//	Parameter Order array
//
//	Returns	Array containing recipient's email
//			HTML for the message body
//
//  Fetch the booking details from the table
//	and assemble the text
//	Some details have to go into an html table	
// --------------------------------------------
function buildEmail($order)
{
    global $mysqli;

    $ref = $order['ref'];
    $picture = $order['picture'];
    $cost = number_format($order['total'], 2);

    $email = $order['email'];
    $name = $order['name'];
    $html = "<html>\n";
    $html .= "<body>\n";
    $html .= "<p>Dear $name</p>\n";
    $html .= "<p>Thank you for your order of painting "
            . "<i>$picture</i> from " . WEBSITE . ".</p>\n";

    $html .= "Your order reference is $ref<br><br>";
    if ($order['quantity'] > 1)
            $html .= $order['quantity'] . " copies<br>";
    $cost = number_format($order['price'], 2);
    $html .= "Price of painting &pound;$cost<br>";
    $cost = number_format($order['shipping'], 2);
    $html .= "Shipping cost &pound;$cost<br>";
    $cost = number_format($order['total'], 2);
    $html .= "<p>Total price received &pound;$cost</p>";
    $html .= "<p>We shall be dispatching your order shortly</p>";
    $html .= "<p>" . ARTIST . "</p>";

    $html .= "<span style='font-size:80%'>" . USER_ADDRESS . "</span></p>\n";
    $html .= "</body>\n";
    $html .= "</html>\n";

    return $html;
}

// ----------------------------------------------
//  Write record to the order table
//
//  Parameters	Order array
//		id of the order
// ----------------------------------------------
function writeOrder($order, $transRef)
{
    global $mysqli;

    $dtSQL = date('Y-m-d');		// Makes today's date for SQL insertion
    $coll = $_SESSION['collection'];
    $user = userFromCollection($coll);
    
    $sql = "INSERT INTO orders "
        . "(ref, name, addr1, addr2, addr3, addr4, postcode, email, price, "
        . "date, region, product, shippingprice, status, quantity, voucher, "
        . " discounta, discounts, user, transref) "
        . "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if (!($stmt = $mysqli->prepare($sql)))
        myError(ERR_PM_WO_PREP, 
            "Prepare failure: (" . $mysqli->errno . ") " . $mysqli->error);

    if (!$stmt->bind_param('isssssssissiiiisiiis', 
        $ref, $name, $addr1, $addr2, $addr3, $addr4, $postcode, $email, 
        $price, $date, $region, $product, $shippingprice, $status, $quantity,
        $voucher, $discounta, $discounts, $user, $transRef))
        myError(ERR_PM_WO_BIND, 
            "Bind failure: (" . $mysqli->errno . ") " . $mysqli->error);

    $ref = $order['ref'];
    $name = addslashes($order['name']);
    $addr1 = addslashes($order['addr1']);
    $addr2 = addslashes($order['addr2']);
    $addr3 =  addslashes($order['addr3']);
    $addr4 =  addslashes($order['addr4']);
    $postcode = addslashes($order['pcode']);
    $email = addslashes($order['email']);
    $price = $order['price'] * 100;
    $date = addslashes($dtSQL);
    $region = $order['region'];
    $product = $order['id'];
    $shippingprice = $order['shipping'] * 100;
    $status = 0;
    $quantity = $order['quantity'];
    $voucher = $order['voucher'];
    $discounta = $order['discounta'];
    $discounts = $order['discounts'];

    if (!$stmt->execute())
        myError(ERR_PM_INSERT_ORDER,
         "Insert failed: (" . $mysqli->errno . ") " . $mysqli->error);
    $stmt->close();

                                                         // Mark painting sold
//	if ($order['multi'])
    $sql2 = "UPDATE paintings SET quantity = quantity - $quantity, "
        . "datesold='$dtSQL' WHERE id=" . $order['id'];
    $mysqli->query ($sql2)
        or myError (ERR_PM_UPDATE_PAINTINGS, 
            "Updating painting record " . $mysqli->error);

}

?>
</body>
</html>

