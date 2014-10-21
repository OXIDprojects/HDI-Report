<?php
/**
 * Changelog:
 * 13.04.2013 - Josef Andreas Puckl (info@ecomstyle.de) - Moved all files to modules-folder
 * 17.10.2014 - Josef Andreas Puckl (info@ecomstyle.de) - Compatibility to 4.9
 */
$sMetadataVersion = '1.1';
$aModule = array(
    'id'            => 'hdiReport',
    'title'         => 'HDI Report',
    'description'   => 'Module for sale statistics.',
    'version'       => '0.9.6',
    'author'        => 'Rafael Dabrowski | HEINER DIRECT GmbH & Co KG',
    'url'           => 'http://www.heiner-direct.com',
    'email'         => 'info@heiner-direct.com',
    'extend'        => array(
    ),
    'files' => array(
        'hdi_report'    => 'hdiReport/admin/hdi_report.php',
    ),
    'templates' => array(
        'hdiReport_main.tpl' => 'hdiReport/views/admin/tpl/hdiReport_main.tpl',
    )
);