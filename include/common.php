<?php

function checkNotEmpty($parameter){
    if(!isset($parameter) || $parameter == ''){
        throw new Exception ('Parameter passed is either null or empty');
    }
}


function logToFile($msg) {
    $isDebug = true;
    if($isDebug == true) {
        $fd = fopen('../logs/smartrecruit.log', "a");
        $str = "[" . date("Y/m/d h:i:s", mktime()) . "] " . $msg;
        fwrite($fd, $str . "\n");
        fclose($fd);
    }
}

function redirectToURL($Str_Location, $Bln_Replace = 1, $Int_HRC = NULL)
{
	if(!headers_sent())
	{
		header('location: ' . urldecode($Str_Location), $Bln_Replace, $Int_HRC);
		exit;
	}

	exit('<meta http-equiv="refresh" content="0; url=' . urldecode($Str_Location) . '"/>'); // | exit('<script>document.location.href=' . urldecode($Str_Location) . ';</script>');
	return;
}

