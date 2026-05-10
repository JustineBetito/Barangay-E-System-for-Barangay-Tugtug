<?php
//============================================================+
// File name   : tcpdf_include.php
// Description : Search and include the TCPDF library.
//============================================================+

/**
 * Search and include the TCPDF library.
 */

// 1. Load the alternative config file from your 'config' folder
if (file_exists('config/tcpdf_config_alt.php')) {
    require_once('config/tcpdf_config_alt.php');
}

// 2. Define the exact path to the TCPDF library brain
// We are telling it to look specifically inside your 'tcpdf' folder
$tcpdf_include_dirs = array(
    realpath('tcpdf/tcpdf.php'), // This looks in htdocs/tcpdf/tcpdf.php
    realpath('../tcpdf.php'),
    '/usr/share/php/tcpdf/tcpdf.php',
    '/usr/share/tcpdf/tcpdf.php',
    '/usr/share/php-tcpdf/tcpdf.php',
    '/var/www/tcpdf/tcpdf.php',
    '/var/www/html/tcpdf/tcpdf.php',
    '/usr/local/apache2/htdocs/tcpdf/tcpdf.php'
);

// 3. Loop through the paths above until it finds the file
foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
    if (@file_exists($tcpdf_include_path)) {
        require_once($tcpdf_include_path);
        break;
    }
}

// Check if the class was actually loaded to prevent the Fatal Error
if (!class_exists('TCPDF')) {
    die('ERROR: TCPDF library not found. Please check if the "tcpdf" folder exists in htdocs.');
}

//============================================================+
// END OF FILE
//============================================================+