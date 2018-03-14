<?php

/**
 * Changelog:
 * 13.04.2013 - Josef A. Puckl (info@ecomstyle.de) - Moved all files to modules-folder
 * 17.10.2014 - Josef A. Puckl (info@ecomstyle.de) - Compatibility to 4.9
 * 14.03.2018 - Josef A. Puckl (info@ecomstyle.de) - Version 4 OXID eShop 6
 */

$sMetadataVersion = '2.0';
$aModule = array(
    'id'            => 'hdiReport',
    'title'         => 'HDI Report',
    'description'   => 'Module for sale statistics.',
    'version'       => '2.0',
    'author'        => 'Rafael Dabrowski | HEINER DIRECT GmbH & Co KG',
    'url'           => 'http://www.heiner-direct.com',
    'email'         => 'info@heiner-direct.com',
    'extend'        => array(
    ),
    'controllers' => array(
        'hdi_report' => \OxidCommunity\hdiReport\Controller\Admin\hdiReport::class,
    ),
    'templates' => array(
        'hdiReport_main.tpl' => 'oxcom/hdiReport/views/admin/tpl/hdiReport_main.tpl',
    )
);