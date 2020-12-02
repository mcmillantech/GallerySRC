<?php
// --------------------------------------
//	Braintree bootstrap file.
// --------------------------------------

    $bt = $config['braintree'];
    require_once $bt;

// Sandbox keys
if(PP_TEST == 1) {
    echo " Test mode<br>";

	$merchantId = "nymy4h8qq7ck73sn";
	$publicKey = "7p36qchh9tgy9dg3";
	$privateKey = "59cdef79000f2acf867d2579eec8b53a";
	$token = 'sandbox_79k7qx4p_nymy4h8qq7ck73sn';
	$environment = 'sandbox';
}
// Production keys
else{
	$merchantId = "fdyvvs3rm4gs2c5n";
	$publicKey = "5p5yd83wt7mk6s4p";
	$privateKey = "b3d5d31c0006796a244a3ddef0b66ad6";
	$token = 'production_38zqhm8t_fdyvvs3rm4gs2c5n';
	$environment = 'production';

}
?>
