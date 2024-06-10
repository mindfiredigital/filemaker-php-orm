<?php

require_once './FileMakerDataApi.php';
$host = '172.16.8.153';
$username = 'admin';
$password = 'mindfire';
$databasename = 'fmProject_DITTO_DATA_sam';
$FileMaker = new FileMakerApiClient($host,$username,$password,$databasename);


//GET METHOD TESTING
$layoutname ='content';
$newlayoutname='users';
$ID ='content_25';
$newID='UUID_0000006';
$IDfield='content_id';
$newIDfield='userId';


// $result = $FileMaker->getOneRecord($layoutname,$ID,$IDfield);
// $result = $FileMaker->getAllRecords($layoutname);
// $result =  $FileMaker->logout();
// $result =  $FileMaker->getToken();
// $result = $FileMaker->validateSession();
// $table1 = $FileMaker->table($layoutname);
// $databasenames = $FileMaker->getDatabases();
// $scriptnames = $FileMaker->getScriptNames();
// $fieldsData = $table1->getFieldsData();
// $layoutnames = $FileMaker->getLayoutNames();
// $table2 =$FileMaker->table($newlayoutname);

// $result1 = $table1->find($IDfield,$ID);
// $result2 = $table2->getOneRecord($newIDfield,$newID);
// $result3 = $table1->all();
// $result4 = $table2->all();


// $result = $FileMaker->getToken();
// $result = $FileMaker->getAllDatabaseNames();


//POST METHOD TESTING


// $layoutname ='studios';
// $jsonpayload = 
//         [
//         "studio_name" => "api_test",
//         "studio_id"=>"api_test"
//         ];
// $table1 = $FileMaker->table($layoutname);
// $result = $table1->createNewRecord($jsonpayload);


//PATCH METHOD TESTING


// $layoutname ='Users';
// $recordID = 6;
// $jsonpayload = 
//         [
//         "userFirstName" => "James"
//         ];
// $table1 = $FileMaker->setLayout($layoutname);
// $result = $table1->updateRecord($jsonpayload,$recordID);


// //SCRIPT RUNNING TESTING
$layoutname ='studios';
$table = $FileMaker->setLayout($layoutname);
$param = "Lionsgate";
$result = $table->runScript("api_test_script",$param);


echo "<pre>";
print_r($result);
echo "</pre>";

