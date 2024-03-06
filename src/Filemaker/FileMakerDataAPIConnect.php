<?php

/**
+--------------------------------------------------------------------
| File    : FileMakerDataAPIConnect.php
| Path    : /src/Filemaker/FileMakerDataAPIConnect.php
| Purpose : Contains all functions for accessing views related to customers.
| Created : 08-Dec-2023
| Author  :  Mindfire Solutions.
| Comments :
+--------------------------------------------------------------------
 */

//!defined('BASEPATH') ? exit('No direct script access allowed') : '';

/**
 * Used to perform all FileMaker CRUD operations.
 */

namespace FilemakerPhpOrm\Filemaker;

use Dotenv\Dotenv;
use CURLFile;

class FileMakerDataAPIConnect
{
    protected $baseURL;
    protected $token;
    protected $hostname;
    protected $database;
    protected $username;
    protected $password;
    protected $version;

    public function __construct()
    {
        // Load environment variables from a .env file
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $this->hostname = $_ENV['FM_HOST'];
        $this->database = $_ENV['FM_DATABASE'];
        $this->version = $_ENV['FM_VERSION'];
        $this->username = $_ENV['FM_USERNAME'];
        $this->password = $_ENV['FM_PASSWORD'];

        $this->baseURL = "https://$this->hostname/fmi/data/$this->version/databases/$this->database";
        // $this->token = $this->loginFMDatabase($databases);
    }

    public function loginFMDatabase()
    {

        //We don't need the array of databases as of now.

        
        // $databases = array(
        //     "fmDataSource" => array(
        //         array(
        //             "database" => "DATA_ContactMgt",
        //             "username" => "Developer",
        //             "password" => "adminbiz"
        //         ),
        //         array(
        //             "database" => "DATA_LineItem",
        //             "username" => "Developer",
        //             "password" => "adminbiz"
        //         )
        //     )
        // );
        
        $endpoint = "sessions";
        $uri = $this->baseURL . '/' . $endpoint;

        // $fmDataSource = ['fmDataSource' => $databases];
        // $bodyJSON = json_encode($fmDataSource);

        $authentication = $this->username . ':' . $this->password;

        $headers = [
            'Authorization: Basic ' . base64_encode($authentication),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        $curlOptions = [
            CURLOPT_URL => $uri,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_HTTPHEADER => $headers,
            // CURLOPT_POSTFIELDS => $bodyJSON,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLINFO_HEADER_OUT => true,
        ];

        curl_setopt_array($ch, $curlOptions);

        $responseCurl = curl_exec($ch);
        curl_close($ch);
        
        $response_data = json_decode($responseCurl, true);
        $fmtoken = $response_data['response']['token'];
        
        session_start();
        $_SESSION['fmtoken'] = $fmtoken;

        return $responseCurl;

    }
    public function postRequest($endpoint, $token, $data)
    {
        
        $uri = $this->baseURL . '/' . $endpoint;
          
        $ch = curl_init();
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];
        
        $curlOptions = [
            CURLOPT_URL => $uri,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $data, // Ensure $data is JSON encoded
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLINFO_HEADER_OUT => true
        ];

        curl_setopt_array($ch, $curlOptions);

        $responseCurl = curl_exec($ch);

        curl_close($ch);

        return $responseCurl;
    }


    public function patchRequest($endpoint, $token, $data)
    {
        $uri = $this->baseURL . '/' . $endpoint;

        $ch = curl_init();
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];

        $curlOptions = [
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_POSTFIELDS => $data, // Ensure $data is JSON encoded
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER => $headers,
        ];

        curl_setopt_array($ch, $curlOptions);

        $responseCurl = curl_exec($ch);

        curl_close($ch);

        return $responseCurl;
    }


    public function getRequest($endpoint, $token)
    {
        $uri = $this->baseURL . '/' . $endpoint;
        
        $ch = curl_init();
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];


        $curlOptions = [
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $headers,
        ];

        curl_setopt_array($ch, $curlOptions);

        $responseCurl = curl_exec($ch);


        curl_close($ch);


        return $responseCurl;
    }


    public function deleteRequest($endpoint, $token)
    {
        $uri = $this->baseURL . '/' . $endpoint;

        $ch = curl_init();
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];

        $curlOptions = [
            CURLOPT_URL => $uri,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => '',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0
        ];

        curl_setopt_array($ch, $curlOptions);

        $responseCurl = curl_exec($ch);

        curl_close($ch);

        return $responseCurl;
    }

    public function performScriptRequest($endpoint, $token)
    {
        $uri = $this->baseURL . '/' . $endpoint;

        $ch = curl_init();
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ];

        $curlOptions = [
            CURLOPT_URL => $uri,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => '',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0
        ];

        curl_setopt_array($ch, $curlOptions);

        $responseCurl = curl_exec($ch);

        curl_close($ch);

        return $responseCurl;
    }


    public function uploadRequest($endpoint, $token, $file)
    {
        $uri = $this->baseURL . '/' . $endpoint;

        $ch = curl_init();
        $headers = [
            'Authorization: Bearer ' . $token,
            'Content-Type: multipart/form-data',
        ];

        $postFields = ['upload' => new CURLFILE($file)];

        $curlOptions = [
            CURLOPT_URL => $uri,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0
        ];

        curl_setopt_array($ch, $curlOptions);

        $responseCurl = curl_exec($ch);

        curl_close($ch);

        return $responseCurl;
    }
}
