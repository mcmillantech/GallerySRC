<?php
// --------------------------------------
//	Braintree bootstrap file.
// --------------------------------------

    $bt = $config['braintree'];
    require_once $bt;

// Sandbox keys
if(PP_TEST == 1) {
    echo " Test mode<br>";

