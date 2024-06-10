<?php

require_once './FileMakerDataBaseConnection.php';

/**
 * FileMaker API Client
 */
class FilemakerApiClient extends FileMakerDataBaseConnection
{
    private $host;
    private $username;
    private $password;
    private $databaseName;
    private $token;

    public function __construct($host, $username, $password, $databaseName)
    {
        
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->databaseName = $databaseName;
        $this->login();
    }
    public function getToken()
    {
        return $this->token;
    }
    public function getHost()
    {
        return $this->host;
    }
    public function getDatabase()
    {
        return $this->databaseName;
    }

    function login()
    {

        $endpoint = '/sessions';
        $requestType = 'POST';
        $database  = '/databases/' . $this->databaseName;
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->username . ":" . $this->password),
        ];
        $curlArray = $this->buildUrl($this->host, $requestType, $headers, $database, $endpoint);
        $result = $this->connection($curlArray);
        $this->token = $result['response']['token'];
        return $result;
    }
    function logout()
    {

        $endpoint = '/sessions/'.$this->token.'';
        $requestType = 'DELETE';
        $database  = '/databases/' . $this->databaseName;
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->username . ":" . $this->password),
        ];
        $curlArray = $this->buildUrl($this->host, $requestType, $headers, $database, $endpoint);
        $result = $this->connection($curlArray);
        $this->token = "";
        return $result;
    }
    function validateSession(){
        $endpoint = '/validateSession';
        $requestType = 'GET';
        
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
        ];
        $curlArray = $this->buildUrl($this->host, $requestType, $headers,$endpoint);
        $result = $this->connection($curlArray);
        return $result;
    }


    public function setLayout($layoutName)
    {
        return new FilemakerLayout($this, $layoutName);
    }

    public function buildUrl($host, $requestType, $headers, $database = null ?? '', $endpoint = null ?? '', $postfields = null ?? '')
    {
        $url = "https://" . $host . "/fmi/data/vLatest" . $database . $endpoint;
        $curlArray = array(
            'curlURL' => $url,
            'curlHeaders' => $headers,
            'curlRequest' => $requestType,
        );
        if ($postfields != '') {
            $curlArray['curlPostfields'] = json_encode($postfields);
        }
        return $curlArray;
    }
    public function getDatabases()
    {
        $database = '/databases';
        $requestType = 'GET';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($this->username . ":" . $this->password),
        ];
        $curlArray = $this->buildUrl($this->host, $requestType, $headers, $database);
        $result = $this->connection($curlArray);

        return $result['response']['databases'] ?? 'Error in receiving data';
    }
    public function getScriptNames()
    {
        $endpoint = '/scripts';
        $database = '/databases/' . $this->getDatabase();
        $requestType = 'GET';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->getToken(),
        ];
        $curlArray = $this->buildUrl($this->getHost(), $requestType, $headers, $database, $endpoint);
        $result = $this->connection($curlArray);

        if ($result['messages'][0]['code'] == "952") {
            $this->login();
            return $this->getScriptNames();
        }
        return $result['response']['scripts'] ?? 'Error in receiving data';
    }
    public function getLayoutNames()
    {
        $endpoint = '/layouts';
        $database = '/databases/' . $this->getDatabase();
        $requestType = 'GET';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->getToken(),
        ];
        $curlArray = $this->buildUrl($this->getHost(), $requestType, $headers, $database, $endpoint);
        $result = $this->connection($curlArray);

        if ($result['messages'][0]['code'] == "952") {
            $this->login();
            return $this->getLayoutNames();
        }
        return $result['response']['layouts'] ?? 'Error in receiving data';
    }
}

/**
 * FileMaker Database Table (Layout)
 */
class FilemakerLayout
{
    private $client;
    private $layoutName;

    public function __construct(FilemakerApiClient $client, $layoutName)
    {
        $this->client = $client;
        $this->layoutName = $layoutName;
    }
    public function test()
    {
        return $this->client;
    }

    public function getOneRecord($idField, $id)
    {
        $endpoint = '/layouts/' . $this->layoutName . '/_find';
        $database = '/databases/' . $this->client->getDatabase();
        $requestType = 'POST';
        $postfields = [
            'query' => [
                [
                    $idField => "==$id"
                ]
            ]
        ];
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->client->getToken(),
        ];
        $curlArray = $this->client->buildUrl($this->client->getHost(), $requestType, $headers, $database, $endpoint, $postfields);
        $result = $this->client->connection($curlArray);
        if ($result['messages'][0]['code'] == "952") {
            $this->client->login();
            return $this->getOneRecord($id, $idField);
        }

        return $result['response']['data'] ?? "Error in receiving data";
    }

    public function allRecords()
    {
        $endpoint = '/layouts/' . $this->layoutName . '/records';
        $database = '/databases/' . $this->client->getDatabase();
        $requestType = 'GET';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->client->getToken(),
        ];
        $curlArray = $this->client->buildUrl($this->client->getHost(), $requestType, $headers, $database, $endpoint);
        $result = $this->client->connection($curlArray);

        if ($result['messages'][0]['code'] == "952") {
            $this->client->login();
            return $this->allRecords();
        }
        return $result['response']['data'] ?? 'Error in receiving data';
    }
    public function getFieldsData()
    {
        $endpoint = '/layouts/' . $this->layoutName;
        $database = '/databases/' . $this->client->getDatabase();
        $requestType = 'GET';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->client->getToken(),
        ];
        $curlArray = $this->client->buildUrl($this->client->getHost(), $requestType, $headers, $database, $endpoint);
        $result = $this->client->connection($curlArray);

        if ($result['messages'][0]['code'] == "952") {
            $this->client->login();
            return $this->getFieldsData();
        }
        return $result['response']['fieldMetaData'] ?? 'Error in receiving data';
    }
    public function createNewRecord($jsonpayload,$recordID =null){

        $endpoint = '/layouts/' . $this->layoutName.'/records';
        $database = '/databases/' . $this->client->getDatabase();
        $requestType = 'POST';
        $postfields = [
            'fieldData' =>$jsonpayload
        ];
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->client->getToken(),
        ];
        $curlArray = $this->client->buildUrl($this->client->getHost(), $requestType, $headers, $database, $endpoint,$postfields);
        $result = $this->client->connection($curlArray);

        if ($result['messages'][0]['code'] == "952") {
            $this->client->login();
            return $this->createNewRecord($jsonpayload);
        }
        return $result?? 'Error in receiving data';
    }
    public function updateRecord($jsonpayload,$recordID){

        $endpoint = '/layouts/' . $this->layoutName.'/records/'.$recordID;
        $database = '/databases/' . $this->client->getDatabase();
        $requestType = 'PATCH';
        $postfields = [
            'fieldData' =>$jsonpayload
        ];
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->client->getToken(),
        ];
        $curlArray = $this->client->buildUrl($this->client->getHost(), $requestType, $headers, $database, $endpoint,$postfields);
        $result = $this->client->connection($curlArray);

        if ($result['messages'][0]['code'] == "952") {
            $this->client->login();
            return $this->createNewRecord($jsonpayload);
        }
        return $result?? 'Error in receiving data';
    }

    public function getRecordWithScript($scriptName){
        $endpoint = '/layouts/' . $this->layoutName.'/records/?script='.$scriptName;
        $database = '/databases/' . $this->client->getDatabase();
        $requestType = 'GET';
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->client->getToken(),
        ];
        $curlArray = $this->client->buildUrl($this->client->getHost(), $requestType, $headers, $database, $endpoint);
        $result = $this->client->connection($curlArray);

        if ($result['messages'][0]['code'] == "952") {
            $this->client->login();
            return $this->getRecordWithScript($scriptName);
        }
        return $result?? 'Error in receiving data';
        
    }
    public function runScript($scriptName,$scriptParam=null??""){
        $endpoint = '/layouts/' . $this->layoutName.'/script/'.$scriptName.'?script.param='.$scriptParam;
        $database = '/databases/' . $this->client->getDatabase();
        $requestType = 'GET';   
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->client->getToken(),
        ];
        $curlArray = $this->client->buildUrl($this->client->getHost(), $requestType, $headers, $database, $endpoint);
        $result = $this->client->connection($curlArray);

        if ($result['messages'][0]['code'] == "952") {
            $this->client->login();
            return $this->getRecordWithScript($scriptName);
        }
        return $curlArray?? 'Error in receiving data';
        
    }
    
}
