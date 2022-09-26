<?php

/**
 * Used for PHP built-in server
 * NOTE: For testing only!
 *
 * php -S 127.0.0.1:7575 serve.php if using VPN
 */

chdir(__dir__);
define('SERVER_ROOT',__dir__);
require __dir__.'/src/Dev/Server.php';

ini_set('error_reporting','E_ALL');
ini_set( 'display_errors','1');
error_reporting(E_ALL ^ E_STRICT);

$server = new \Dev\Server('/public');
$server->serve();