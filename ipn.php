<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('classes/log.php');
// include_once('../lib/ipn.php');

require_once 'vendor/autoload.php';
use Netopia\Payment2\IPN;
/**
 * Load .env 
 * Read Base root , ... from .env
 * The  env var using in UI ,..
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Log
log::setRealTimeLog(array("IPN"    =>  "IPN Is hitting. ".time()));
log::logHeader();

/**
 * get defined keys
 */
$ntpIpn = new IPN();

$ntpIpn->activeKey         = $_ENV['NETOPIA_SIGNATURE'];        // activeKey or posSignature
$ntpIpn->posSignatureSet[] = $_ENV['NETOPIA_SIGNATURE'];        // The active key should be in posSignatureSet as well
$ntpIpn->posSignatureSet[] = 'EEEE-AAAA-BBBB-CCCC-DDDD';
$ntpIpn->posSignatureSet[] = 'FFFF-DDDD-AAAA-BBBB-CCCC'; 
$ntpIpn->posSignatureSet[] = 'DDDD-FFFF-EEEE-AAAA-BBBB'; 
$ntpIpn->posSignatureSet[] = 'FFFF-GGGG-HHHH-EEEE-AAAA';
$ntpIpn->hashMethod        = 'SHA512';
$ntpIpn->alg               = 'RS512';

$ntpIpn->publicKeyStr = $_ENV['NETOPIA_PUBLIC_KEY'];

$ipnResponse = $ntpIpn->verifyIPN();

// Log
log::setRealTimeLog(array("IPN_RESPUNSE"    =>  json_encode($ipnResponse)));

/**
 * IPN Output
 */
echo json_encode($ipnResponse);