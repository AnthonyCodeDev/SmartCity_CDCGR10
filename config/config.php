<?php

$productionMode = false;

if (!defined('BASE_URL')) {
    
    // DEVELOPPEMENT: /SmartCity_CDCGR10/
    // SERVER: /

    if ($productionMode == true) {
        // PROD MODE
        define('TOTAL_URL', "http://192.168.20.1".BASE_URL);
        define('BASE_URL', '/');
    } else {
        // DEV MODE
        define('BASE_URL', '/SmartCity_CDCGR10/');
        define('TOTAL_URL', "http://localhost".BASE_URL);
    }
}
