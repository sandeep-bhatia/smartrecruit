<?php
include_once('../objects/Authentication.php');
include_once('../include/common.php');
session_start();

$authentication = new Authentication();

if($_GET['FIRST_REQUEST']==1){
	logToFile('This is a first request and sending it for Request Token. Calling initiate() method');
	$authentication->initiateRequestToken();
}else{
	logToFile('Authentication object is unserialized form session');
	//$authentication = unserialize($_SESSION['authenticationObject']);
	
	$authentication->initiateAccessToken();
	
	//redirectToURL('welcomepage.php?'.'oauth_token='.$oauthAccessToken.'&oauth_verifier='.$oauthAccessVerifier.'&PAGE_ID=WELCOME_PAGE');
	redirectToURL('controller.php?PAGE_ID=WELCOME',1,null);
	
}

