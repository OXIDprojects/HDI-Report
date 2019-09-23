<?php

/**
 * Changelog:
 * 13.04.2013 - Josef A. Puckl | eComStyle.de - Moved all files to modules-folder
 * 17.10.2014 - Josef A. Puckl | eComStyle.de - Compatibility to 4.9
 * 14.03.2018 - Josef A. Puckl | eComStyle.de - Version 4 OXID eShop 6
 * 23.09.2019 - Josef A. Puckl | eComStyle.de - Moduleinstellung: Bestellordner nicht auswerten.
 */

$sMetadataVersion = '2.0';
$aModule          = [
    'id'            => 'hdiReport',
    'title'         => 'HDI Report',
    'description'   => 'Module for sale statistics.',
    'version'       => '2.0.2',
    'author'        => 'Rafael Dabrowski | HEINER DIRECT GmbH & Co KG',
    'url'           => 'http://www.heiner-direct.com',
    'email'         => 'info@heiner-direct.com',
    'extend'        => [
    ],
    'controllers' => [
        'hdi_report' => \OxidCommunity\hdiReport\Controller\Admin\hdiReport::class,
    ],
    'templates' => [
        'hdireport_main.tpl' => 'oxcom/hdiReport/views/admin/tpl/hdireport_main.tpl',
    ],
    'settings' => [
        ['group' => 'hdi_main', 'name' => 'hdi_blockfolders', 'type' => 'arr', 'value' => ["ORDERFOLDER_PROBLEMS"]],
    ],

];
