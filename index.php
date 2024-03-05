<?php

require 'vendor/autoload.php';



//Below Code is to test the methods from filemakerDataAPI file.

use FilemakerPhpOrm\Filemaker\FileMakerDataAPI;

$class = new \FilemakerPhpOrm\Filemaker\FileMakerDataAPIConnect();
$filemakerdataapi = new FileMakerDataAPI();


//Testing code for update.

$neededDocData = array(
    'nameLast' => 'mindfire',
    'nameFirst' => 'testing'
);

$recordID = 516 ; 

$resultObj = $filemakerdataapi
            ->setLayout('ens_MUS__Musicians_mbr')
            ->setRecordId($recordID)
            ->setFieldData($neededDocData)
            ->update();
print_r($resultObj);