<?php namespace gouravbajaj0210\api;

class ApiResponse{

	private $errorCodes= [400,403,404,500];

	private $responseType= 'json';

	private $errorName='error';

	private $errorType= 'errorType';

	private $data=[];

	private $statusCode=null;

	/**
	 * Constructor function, set the configuration values
	 * from config file if they are available.
     */
	function __construct(){
		if(config('api')){
			$this->responseType= config('api.responseType');
			$this->errorName= config('api.errorName');
			$this->errorType= config('api.errorType');
		}
	}

	/**
	 * Set the data for response
	 * @param $data
	 * @return $this
     */
	public function data($data){

		$this->data= $data;
		return $this;

	}

	/**
	 * Set custom status code for response
	 * @param $statusCode
     */
	public function statusCode($statusCode){

		$this->statusCode= $statusCode;

		return $this;
	}

	/**
	 * Set response type as json
	 * @return ApiResponse
     */
	public function json(){

		return $this->setResponseType('json');

	}

	/**
	 * Set response type as xml
	 * @return ApiResponse
     */
	public function xml(){

		return $this->setResponseType('xml');

	}

	/**
	 * Set response type, json or xml
	 * @param $type
	 * @return ApiResponse
     */
	public function type($type){
		return $this->setResponseType($type);
	}

	/**
	 * Set response type
	 * @param $type
	 * @return $this
     */
	private function setResponseType($type){

		$this->responseType= $type;

		return $this;

	}


	/**
	 * Send response
	 * @param $data
	 * @param $statusCode
	 * @return \Illuminate\Http\JsonResponse
     */
	private function sendResponse($data,$statusCode){

		$this->buildResponse($data,$statusCode);

		if($this->responseType=== 'xml')
			return $this->xmlResponse();
		else
			return $this->jsonResponse();
	}

	/**
	 * Send json response
	 * @return \Illuminate\Http\JsonResponse
     */
	private function jsonResponse(){
		return response()->json($this->data,$this->statusCode);
	}

	/**
	 * Send xml response
	 * @return mixed
     */
	private function xmlResponse(){

		$xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><response/>');

		$header= [];

		foreach ($this->data as $key => $value) {
	        if (is_array($value))
	            \Response::xml($value, $this->statusCode, $header, $xml->addChild($key));
	        else
	            $xml->addChild($key, $value);
	        
    	}

    	if (empty($header))
        	$header['Content-Type'] = 'application/xml';
    
    	return \Response::make($xml->asXML(), $this->statusCode, $header);

	}


	/**
	 * Build the response
	 * @param $data
	 * @param $statusCode
     */
	private function buildResponse($data, $statusCode){

		// If the data sent is not null, update data property with new data
		if(!is_null($data))
			$this->data=$data;

		// If statusCode property is not set, then we use the property sent
		if(is_null($this->statusCode))
			$this->statusCode=$statusCode;

		$this->getErrorStatus();
	}

	/**
	 * Get the error status from status codes
	 * @return bool
     */
	private function getErrorStatus(){

		$data= $this->data;

		if(in_array($this->statusCode, $this->errorCodes)){
			$data['error']= true;

			if($this->statusCode == 500)
				$data[$this->errorType]='server';
			else
				$data[$this->errorType]='user';
		}
		else
			$data[$this->errorName]=false;

		$this->data= $data;

		return true;

	}

	/*** Success messages ***/

	/**
	 * Send success response, 200 series
	 * @param null $data
	 * @return \Illuminate\Http\JsonResponse
     */
	public function success($data=null){

		return $this->sendResponse($data,200);
	}

	/*** User error messages ***/

	/**
	 * Validation failed response
	 * @param null $data
	 * @return \Illuminate\Http\JsonResponse
     */
	public function notValid($data=null){

		return $this->sendResponse($data,400);
	}

	/**
	 * User not authorized response
	 * @param null $data
	 * @return \Illuminate\Http\JsonResponse
     */
	public function notAuthorized($data=null){

		return $this->sendResponse($data,403);
	}

	/**
	 * Not found response
	 * @param null $data
	 * @return \Illuminate\Http\JsonResponse
     */
	public function notFound($data=null){

		return $this->sendResponse($data,404);
	}

	/*** Server error messages ***/

	/**
	 * Server error, 500
	 * @param null $data
	 * @return \Illuminate\Http\JsonResponse
     */
	public function serverError($data=null){

		return $this->sendResponse($data,500);
	}


	/**
	 * Build your own custom response
	 * @param null $data
	 * @param int $statusCode
	 * @return \Illuminate\Http\JsonResponse
     */
	public function response($data=null,$statusCode=200){
		
		return $this->sendResponse($data,$statusCode);
	}



}