<?php
include_once("../include/common.php");
include_once("../include/config.php");

class Authentication{

	protected $state;
	protected $oauth;
	protected $requestToken;
	protected $requestSecretToken;
	protected $accessToken;
	protected $accessSecretToken;
	protected $verfierToken;


	function __construct() {
		logToFile('new Authentication object is being created');
		// create a new instance of the OAuth PECL extension class
		$oauth = new OAuth(CONSUMER_KEY, CONSUMER_SECRET,OAUTH_SIG_METHOD_HMACSHA1,OAUTH_AUTH_TYPE_AUTHORIZATION);
		$oauth->setNonce(rand());
		$this->setOauth($oauth);
	}

	public function initiateRequestToken(){
		logToFile('Function initiate called');
		// get our request token
		$request_token_response = $this->oauth->getRequestToken(REQUEST_TOKEN_URL);
		if($request_token_response === FALSE) {
			throw new Exception('Failed fetching request token, response was: ' . $this->oauth->getLastResponse());
		}

		$requestToken = $request_token_response['oauth_token'];
		logToFile('Request Token is :'.$requestToken);
		$requestSecretToken = $request_token_response['oauth_token_secret'];
		logToFile('Request Secret Token is :'.$requestSecretToken);
		
		$this->setRequestToken($requestToken);
		$this->setRequestSecretToken($requestSecretToken);	
			
		$this->oauth->setToken($this->getRequestToken(),$this->getRequestSecretToken());
		//Forward URL
		header("Location: " . AUTHORIZE_URL . "?oauth_token=" . $this->getRequestToken());
		exit;

	}


	public function initiateAccessToken(){
		logToFile('Inside getAccessToken method');
		
			//$this->oauthAccessVerifier = $_REQUEST['oauth_verifier'];
			
			$this->oauth->setToken($this->getRequestToken(),$this->getRequestSecretToken());
			
			$accessTokenResponse = $this->oauth->getAccessToken(ACCESS_TOKEN_URL);
			
			if($accessTokenResponse === FALSE) {
				throw new Exception('Failed fetching request token, response was: ' . $this->oauth->getLastResponse());
			}

			$accessToken = $accessTokenResponse['oauth_token'];
			logToFile('Access Token is :'.$accessToken);
			$accessSecretToken = $accessTokenResponse['oauth_token_secret'];				
			logToFile('Access Secret Token is :'.$accessSecretToken);
			$verifierToken = $_GET['oauth_verifier'];
			logToFile('Verifier Token is :'.$verifierToken);
				
			
			$this->setAccessToken($accessToken);
			$this->setAccessSecretToken($accessSecretToken);
			$this->setVerfierToken($verifierToken);
			
			$this->oauth->setToken($this->getAccessToken(),$this->getAccessSecretToken());


	}

    public function getOauth(){
		return $this->oauth;
	}
	public function setOauth($oauth_obj) {
		$this->oauth = $oauth_obj;
	}
	
	public function setRequestToken($requestToken){
		$this->requestToken = $requestToken;
		$_SESSION['_request_token'] = $requestToken;
	}

	public function setRequestSecretToken($requestSecretToken){
		$this->requestSecretToken = $requestSecretToken;
		$_SESSION['_request_secret_token'] = $requestSecretToken;
	}
	
	public function getRequestToken(){
		if(isset($_SESSION['_request_token']))
			$this->setRequestToken($_SESSION['_request_token']);
		
		if(null != $this->requestToken && "" != $this->requestToken) {
			return $this->requestToken;
		} else {
			return false;
		}
		
	}
	
	public function getRequestSecretToken(){
		if(isset($_SESSION['_request_secret_token']))
			$this->setRequestSecretToken($_SESSION['_request_secret_token']);
		
		if(null != $this->requestSecretToken && "" != $this->requestSecretToken) {
			return $this->requestSecretToken;
		} else {
			return false;
		}
		
	}
	
	public function setAccessToken($accessToken){
		$this->accessToken = $accessToken;
		$_SESSION['_access_token'] = $accessToken;
	}
	
	public function setAccessSecretToken($accessSecretToken){
		$this->accessSecretToken = $accessSecretToken;
		$_SESSION['_access_secret_token'] = $accessSecretToken;
	}
	
	public function setVerfierToken($verfierToken){
		$this->verfierToken = $verfierToken;
		$_SESSION['_verifier_token'] = $verfierToken;
	}
	
	public function getAccessToken(){
		if(isset($_SESSION['_access_token']))
			$this->setAccessToken($_SESSION['_access_token']);
	
		if(null != $this->accessToken && "" != $this->accessToken) {
			return $this->accessToken;
		} else {
			return false;
		}
	
	}
	
	public function getAccessSecretToken(){
		if(isset($_SESSION['_access_secret_token']))
			$this->setAccessSecretToken($_SESSION['_access_secret_token']);
	
		if(null != $this->accessSecretToken && "" != $this->accessSecretToken) {
			return $this->accessSecretToken;
		} else {
			return false;
		}
	
	}
	
	public function getVerfierToken(){
		if(isset($_SESSION['_verfier_token']))
			$this->setVerfierToken($_SESSION['_verfier_token']);
	
		if(null != $this->verfierToken && "" != $this->verfierToken) {
			return $this->verfierToken;
		} else {
			return false;
		}
	
	}
	
	public function setAccessTokensInOauthObject(){
		$this->oauth->setToken($this->getAccessToken(),$this->getAccessSecretToken());
	}

}
