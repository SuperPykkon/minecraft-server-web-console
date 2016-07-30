<?php

error_reporting(0);
require("config/config.php");
$timeout = 6;

$fsock = fsockopen(SERVER_IP, SERVER_PORT, $errno, $errstr, $timeout);
if(!$fsock) {
    echo "offline";
} else {
    echo "online";
}

?>