<?php
include_once('../objects/Authentication.php');
include_once('../include/common.php');


class LinkedInFetch{

    public function fetch($fetchUrl){
        $authentication = new Authentication();
        $authentication->setAccessTokensInOauthObject();
        $oauth = $authentication->getOauth();

   //     $oauth->fetch($fetchUrl);
        $oauth->fetch($fetchUrl,null, OAUTH_HTTP_METHOD_GET, array('x-li-format' => 'json'));

        $jsonResult = $oauth->getLastResponse();
//        logToFile("JSON result is ".$jsonResult);

        return $jsonResult;
    }

}
