<?php

namespace FilemakerPhpOrm\Filemaker;


/**
+--------------------------------------------------------------------
| File    : FileMakerDataAPI.php
| Path    : /src/Filemaker/FileMakerDataAPI.php
| Purpose : Contains all functions for accessing views related to customers.
| Created : 08-Dec-2023
| Author  :  Mindfire Solutions.
| Comments :
+--------------------------------------------------------------------
 */
// !defined('BASEPATH') ? exit('No direct script access allowed') : '';
/**
 * Used to perform all FileMaker CRUD operations.
 * @see FileMakerDataAPIConnect.
 */
class FileMakerDataAPI extends FileMakerDataAPIConnect
{
	/**
	 * @vara Object  - Contains filemaker connect instance
	 */
	private $filemakerdataapiconnect;
	private $layout;
	private $recordId;
	private $script;
	private $scriptParameter;

	/**
	 * Used to initialize libraries, models etc.
	 *
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->filemakerdataapiconnect = new FileMakerDataAPIConnect();
	}
	
	public function logInDatabase()
	{
		return $this->filemakerdataapiconnect->loginFMDatabase();
	}
	public function getToken()
	{
		// session_start();

		if (!isset($_SESSION['fmtoken'])) {
			$loginSession = $this->logInDatabase(); // Assuming $this->logInDatabase() is defined elsewhere
			$loginSession = json_decode($loginSession, true);
			$userSessionData = array(
				'fmtoken' => $loginSession['response']['token'],
				'loggedIn' => true,
			);
			foreach ($userSessionData as $key => $value) {
				$_SESSION[$key] = $value;
			}
		}

		$this->token = $_SESSION['fmtoken'] ?? null;
		

		return $this->token;
	}


	public function removeToken()
	{
		$userSessionData = array(
			'fmtoken' => '',
			'loggedIn' => false,
		);
		foreach ($userSessionData as $key => $value) {
			$_SESSION[$key] = $value;
		}

	}

	/**
	 * Used to set layout of model.
	 *
	 * @param String  $layout - Name of layout
	 * @return Object - FM wrapper Object.
	 */

	 
	public function setLayout($layout = '')
	{
		$this->layout = $layout;
		return $this;
	}

	public function setRecordId($recordId = '')
	{
		$this->recordId = $recordId;
		return $this;
	}

	public function setScript($script = '')
	{
		$this->script = $script;
		return $this;
	}

	public function setScriptParameter($scriptParameter = '')
	{
		$this->scriptParameter = $scriptParameter;
		return $this;
	}

	public function setFieldData($fieldData = array())
	{
		$this->fieldData = $fieldData;
		return $this;
	}

	/**
	 * Used to set fields that are required for and conditionals.
	 *
	 * @param Array $andWheres - Fields for AND  conditionals.
	 * @return Object - Current FM wrapper Object.
	 */


	public function andWheres($andWheres = array(), $search = false)
	{
		if (!empty($andWheres) && $search == false) {
			$this->andWheres = array($andWheres);
		} else if (!empty($andWheres) && $search == true) {
			$this->andWheres = $andWheres;
		}
		return $this;
	}

	public function limit($offset, $limit)
	{
		$this->offset = $offset == 0 ? 1 : $offset;
		$this->limit = $limit == 0 ? 10 : $limit;
		return $this;
	}

	public function orderBy($sortBy, $sortOrder)
	{
		$this->sort = array(
			"fieldName" => $sortBy,
			"sortOrder" => $sortOrder == 'asc' ? 'ascend' : 'descend',
		);
		return $this;
	}

	public function create()
	{
		$data = array('fieldData' => $this->fieldData);
		$data = json_encode($data);

		$layout = $this->layout;
		$endpoint = 'layouts/' . $layout . '/records';
		$token = $this->getToken();
		$getResponse = $this->filemakerdataapiconnect->postRequest($endpoint, $token, $data);
		$getResponse = json_decode($getResponse, true);
		$messageCode = $getResponse['messages'][0]['code'];
		if ($messageCode === '952') {
			$this->removeToken();
			$token = $this->getToken();
			$getResponse = $this->filemakerdataapiconnect->postRequest($endpoint, $token, $data);
			$getResponse = json_decode($getResponse, true);
		}
		return $getResponse;
	}

	/**
	 * It executes required command and returns the final formatted result.
	 *
	 * @param Array $fields - Fields which needs to be included in result.
	 * @return Array  - FM result with FM field value pairs.
	 */
	public function get()
	{
		$data = array(
			'query' => $this->andWheres,
			'limit' => $this->limit,
			'offset' => $this->offset,
			'sort' => array($this->sort)
		);
		$data = json_encode($data);
		$layout = $this->layout;
		
		$endpoint = 'layouts/' . $layout . '/_find';
		$token = $this->getToken();


		$getResponse = $this->filemakerdataapiconnect->postRequest($endpoint, $token, $data);
		$getResponse = json_decode($getResponse, true);

		$messageCode = $getResponse['messages'][0]['code'];
		if ($messageCode === '952') {
			$this->removeToken();
			$token = $this->getToken();
			$getResponse = $this->filemakerdataapiconnect->postRequest($endpoint, $token, $data);
			$getResponse = json_decode($getResponse, true);
		}
		return $getResponse;
	}

	public function update()
	{
		$recordId = $this->recordId;
		$data = array('fieldData' => $this->fieldData);
		$data = json_encode($data);
		$layout = $this->layout;
		$endpoint = 'layouts/' . $layout . '/records'.'/'.$recordId;
		$token = $this->getToken();
		$patchResponse = $this->filemakerdataapiconnect->patchRequest($endpoint, $token, $data);
		$patchResponse = json_decode($patchResponse, true);
		$messageCode = $patchResponse['messages'][0]['code'];
		if ($messageCode === '952') {
			$this->removeToken();
			$token = $this->getToken();
			$patchResponse = $this->filemakerdataapiconnect->patchRequest($endpoint, $token, $data);
			$patchResponse = json_decode($patchResponse, true);
		}
		return $patchResponse;
	}

	public function getRecord()
	{
		$recordId = $this->recordId;
		$layout = $this->layout;
		$endpoint = 'layouts/' . $layout . '/records'.'/'.$recordId;
		$token = $this->getToken();

		$getResponse = $this->filemakerdataapiconnect->getRequest($endpoint, $token);
		var_dump($getResponse);
		$getResponse = json_decode($getResponse, true);
		// echo $getResponse;
		// die();

		$messageCode = $getResponse['messages'][0]['code'];
		if ($messageCode === '952') {
			$this->removeToken();
			$token = $this->getToken();
			$getResponse = $this->filemakerdataapiconnect->getRequest($endpoint, $token);
			$getResponse = json_decode($getResponse, true);
		}
		return $getResponse;
	}

	/**
	 * It executes required command and returns the final formatted result.
	 *
	 * @param Array $fields - Fields which needs to be included in result.
	 * @return Array  - FM result with FM field value pairs.
	 */
	public function delete()
	{
		$layout = $this->layout;
		$recordId = $this->recordId;
		$endpoint = 'layouts/' . $layout . '/records'.'/' . $recordId;
		$token = $this->getToken();
		$getResponse = $this->filemakerdataapiconnect->deleteRequest($endpoint, $token);
		$getResponse = json_decode($getResponse, true);
		$messageCode = $getResponse['messages'][0]['code'];
		if ($messageCode === '952') {
			$this->removeToken();
			$token = $this->getToken();
			$getResponse = $this->filemakerdataapiconnect->deleteRequest($endpoint, $token);
			$getResponse = json_decode($getResponse, true);
		}
		return $getResponse;
	}

	/**
	 * It executes required command and returns the final formatted result.
	 *
	 * @param Array $fields - Fields which needs to be included in result.
	 * @return Array  - FM result with FM field value pairs.
	 */
	public function performScript()
	{
		$layout = $this->layout;
		$script = $this->script;
		
		$offset =  isset($this->offset)? $this->offset : 1;
		$limit = isset($this->limit)? $this->limit: 5;

		$scriptParameter = $this->scriptParameter;
		$endpoint = 'layouts/' . $layout . '/records'. '?script=' . $script . '&script.param=' . $scriptParameter . '&_offset=' . $offset . '&_limit=' . $limit;
		$endpoint = rawurlencode($endpoint);

		$token = $this->getToken();
		$getResponse = $this->ci->filemakerdataapiconnect->performScript($endpoint, $token);
		$getResponse = json_decode($getResponse, true);
		$messageCode = $getResponse['messages'][0]['code'];
		if ($messageCode === '952') {
			$this->removeToken();
			$token = $this->getToken();
			$getResponse = $this->ci->filemakerdataapiconnect->performScript($endpoint, $token);
			$getResponse = json_decode($getResponse, true);
		}
		return $getResponse;
	}

	public function upload($file = '')
	{
		$recordId = $this->recordId;

		$layout = $this->layout;
		$endpoint = 'layouts/' . $layout . '/records'.'/'. $recordId . '/containers/Document';
		$token = $this->getToken();
		$getResponse = $this->ci->filemakerdataapiconnect->upload($endpoint, $token, $file);
		$getResponse = json_decode($getResponse, true);
		$messageCode = $getResponse['messages'][0]['code'];
		if ($messageCode === '952') {
			$this->removeToken();
			$token = $this->getToken();
			$getResponse = $this->ci->filemakerdataapiconnect->upload($endpoint, $token, $file);
			$getResponse = json_decode($getResponse, true);
		}
		return $getResponse;
	}
	
}
