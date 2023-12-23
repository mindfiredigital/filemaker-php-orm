<?php

namespace FilemakerPhpOrm\Filemaker;

// use Poojapatle\FilemakerPhpOrm\Filemaker\FileMakerDataAPIConnect;

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
	}

	public function removeToken()
	{
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
	}

	/**
	 * It executes required command and returns the final formatted result.
	 *
	 * @param Array $fields - Fields which needs to be included in result.
	 * @return Array  - FM result with FM field value pairs.
	 */
	public function get()
	{
	}

	public function update()
	{
	}

	public function getRecord()
	{
	}

	/**
	 * It executes required command and returns the final formatted result.
	 *
	 * @param Array $fields - Fields which needs to be included in result.
	 * @return Array  - FM result with FM field value pairs.
	 */
	public function delete()
	{
	}

	/**
	 * It executes required command and returns the final formatted result.
	 *
	 * @param Array $fields - Fields which needs to be included in result.
	 * @return Array  - FM result with FM field value pairs.
	 */
	public function performScript()
	{
	}

	public function upload($file = '')
	{
	}
}
