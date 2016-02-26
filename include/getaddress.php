<?php
// show errors in the browser
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$postCode = $_GET['postcode'];
$url = 'https://api.getAddress.io/v2/uk/'.$postCode.'?api-key=7EUijLFMfUudP-7YyMK9wA3244';
$ch = curl_init($url);
// curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
// curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: www.paypal.com'));
// In wamp like environment where the root authority certificate doesn't comes in the bundle, you need
// to download 'cacert.pem' from 'http://curl.haxx.se/docs/caextract.html' and set the directory path 
// of the certificate as shown below.
// curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
$res = curl_exec($ch);
curl_close($ch);
$arrAddress = json_decode($res, true);
$arrAddress = $arrAddress['Addresses'];
echo json_encode($arrAddress);
?>