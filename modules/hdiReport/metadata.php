<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.0';
 
/**
 * 
 * Changelog:
* 13.4.2013 - Josef Andreas Puckl (info@ecomstyle.de) - Moved all files to modules-folder
* Module information
 */
$aModule = array(
    'id'           => 'hdiReport',
    'title'        => 'HDI Report',
    'description'  => 'Module for sale statistics.',
    'version'      => '0.9.4',
    'author'       => 'Rafael Dabrowski | HEINER DIRECT GmbH & Co KG',
    'url'          => 'http://www.heiner-direct.com',
    'email'        => 'info@heiner-direct.com',
    'extend'       => array(
    ),
    'files' => array(
        'hdi_report'                        => 'hdiReport/admin/hdi_report.php',
    ),
    'blocks' => array(
    ),
   'settings' => array(
    ),
    'templates' => array(
    	'hdiReport_main.tpl' => 'hdiReport/out/admin/tpl/hdiReport_main.tpl',
    	)
);