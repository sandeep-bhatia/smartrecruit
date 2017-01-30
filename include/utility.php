<?php
interface IExternalConnection {
	//Interface methods like
	//Connect
	//GetCandidateInfo
	//SetConnectionType
	//these methods do the connections and get info

	public function getProfileInformation($oauth);
	public function getConnections($resourceURL,$oauth);

}
abstract class LinkedInPerson implements IExternalConnection {
/*	private $profile_url;
	private $profile_fields;
	private $connections_url;
	
	public function __construct() {
		$this->profile_url = 'http://api.linkedin.com/v1/people/~';
		$this->profile_fields = ':(id,first-name,last-name,headline,num-connections,summary,specialties,associations,honors,interests,positions,publications,bound-account-types)';
		$this->connections_url = 'http://api.linkedin.com/v1/people/~/connections';
	}
	public function getProfileSelectorURL() {
		$profileSelectorURL = $this->profile_url . $this->profile_fields;
		return $profileSelectorURL;
	}
	public function getConnectionsURL() {
		return $this->connections_url;
	}
	
	function getProfileInformation($oauth) {
		//$api_url = urldecode($api_url);
        logToFile('Step 33333');
		$oauth->fetch($this->profile_url,null, OAUTH_HTTP_METHOD_GET);
        logToFile('Step 4444');
		$response_info = $oauth->getLastResponse();
		return $response_info;
	}
    function getConnections($resourceURL,$oauth) {
    	$resourceURL = urldecode($resourceURL);
		$oauth->fetch($resourceURL,null, OAUTH_HTTP_METHOD_GET, array('x-li-format' => 'json'));
		$responseInfo = $oauth->getLastResponse();
		return $responseInfo;
	}
*/
}
