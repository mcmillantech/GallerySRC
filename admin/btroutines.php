<?php

                                    // Prepend parent dir to Btaintree path
                                    // (Config holds relative to server root)
    $config['braintree'] = "../" . $config['braintree'];
    require_once "../bootstrap.php";

    $gateway = new Braintree_Gateway(
        [
            'environment' => $environment,
            'merchantId' => $merchantId,
            'publicKey' => $publicKey,
            'privateKey' => $privateKey
        ]
    );

// ----------------------------------------
//  Process Braintree payment
//
//  Parameters  Braintree gateway
//              fee to be taken
//  Returns     Transaction reference
// ----------------------------------------
function takePayment($gateway, $fee) {

    if (RERUN) {                                // Debug mode
        return ("Test artist pay");
    }

    // Instantiate a Braintree Gateway 
    $nonceFromTheClient = $_POST["nonce"];
//    echo "$nonceFromTheClient<br>";
                                                // Then, create a transaction:
    $result = $gateway->transaction()->sale([
        'amount' => $fee,
        'paymentMethodNonce' => "$nonceFromTheClient",
        'options' => [ 'submitForSettlement' => true]
    ]);

    if ($result->success) {
        $transaction = $result->transaction;
        $transRef = $transaction->id;
        return $transRef;
    } else if ($result->transaction) {
        doFailure($result->transaction->processorResponseText);
    } else {
        $msg = $result->message;
        doFailure($msg);
    }
}
