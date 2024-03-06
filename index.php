<?php

require 'vendor/autoload.php';



//Below Code is to test the methods from filemakerDataAPI file.

use FilemakerPhpOrm\Filemaker\FileMakerDataAPI;

$class = new \FilemakerPhpOrm\Filemaker\FileMakerDataAPIConnect();
$filemakerdataapi = new FileMakerDataAPI();


//Testing code for Upload Container Field Data.

// $neededDocData = array(
//     'nameLast' => 'mindfire',
//     'nameFirst' => 'testing'
// );

$neededDocData = 'Picture';
$recordID = 746 ; 

$resultObj = $filemakerdataapi
            ->setLayout('ens_MUS__Musicians_mbr')
            ->setRecordId($recordID)
            ->setFieldName($neededDocData)
            ->upload('C:\xampp\htdocs\Internal Project\filemaker-php-orm\Capture.PNG');
print_r($resultObj);