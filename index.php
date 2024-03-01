<?php

require 'vendor/autoload.php';



//Below Code is to test the methods from filemakerDataAPI file.

use FilemakerPhpOrm\Filemaker\FileMakerDataAPI;

$class = new \FilemakerPhpOrm\Filemaker\FileMakerDataAPIConnect();
$filemakerdataapi = new FileMakerDataAPI();


//Testing code for find.

$neededDocData = array(
    'nameLast' => 'Aten'
);
$resultObj = $filemakerdataapi
            ->setLayout('ens_MUS__Musicians_mbr')
            ->andWheres($neededDocData)
            ->limit(1, 20)
            ->orderBy('nameLast', 'Aten')
            ->get();
print_r($resultObj);