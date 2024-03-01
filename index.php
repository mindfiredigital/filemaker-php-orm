<?php

require 'vendor/autoload.php';



//Below Code is to test the methods from filemakerDataAPI file.

use FilemakerPhpOrm\Filemaker\FileMakerDataAPI;

$class = new \FilemakerPhpOrm\Filemaker\FileMakerDataAPIConnect();
echo 'here';
$test = new FileMakerDataAPI();
echo $test->getToken();
echo $_ENV['FM_HOST'];