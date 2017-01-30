<?php
include_once('../include/common.php');
$pageId = $_REQUEST['PAGE_ID'];
logToFile('PAGE_ID in request :'.$pageId);
if(!isset($pageId)){
	$pageId='';
}


switch ($pageId){
	case '':
        logToFile('First request to the site. Sending user to login page');
        redirectToURL('login.php',1,null);
        break;
     case 'AUTHENTICATION':
        logToFile('Redirecting user to Authentication page');
        redirectToURL('oauth.php?FIRST_REQUEST=1',1,null);
        break;
     case 'WELCOME':
        	logToFile('Redirecting user to Welcome page');
        	redirectToURL('welcomepage.php',1,null);
        	break;
    case 'WELCOME_ERROR':
        logToFile('Redirecting user to Welcome page');
        redirectToURL('welcomepage.php?ERROR_ID=1',1,null);
        break;
    case 'JOB_DISPLAY':
        $companyName = $_POST['COMPANY_NAME'];
        $jobTitle = $_POST['JOB_TITLE'];
        $jobLocation = $_POST['JOB_LOCATION'];

        logToFile('Redirecting user to Display Jobs page');
        redirectToURL('jobdisplay.php?COMPANY_NAME='.$companyName,1,null);
        break;
    case 'QUOTIENT_DISPLAY':
        redirectToURL('impactquotient.php',1,null);
        break;
    case 'CREATE_INTERVIEW':
        $jobId = $_REQUEST['jobId'];
        $jobTitle = $_REQUEST['jobTitle'];
        $tableStatus = $_REQUEST['tableStatus'];
        logToFile('Job Title is: '. $jobTitle);
        redirectToURL('questionbank.php?JOB_ID='.$jobId."&JOB_TITLE=".$jobTitle);

}
