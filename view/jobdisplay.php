<?php
    include_once "../viewclasses/DisplayJobsView.php";
    include_once "../include/common.php";

    session_start();
    // THIS NEEDS TO BE FIXED
//    $jobId = $_REQUEST['JOB_ID'];

    $companyName = $_REQUEST['COMPANY_NAME'];
    //$jobTitle = $_REQUEST['JOB_TITLE'];
    //$jobLocation = $_REQUEST['JOB_LOCATION'];

    $view = new DisplayJobsView();
    $jobsResult = $view->searchJobsByCompanyName($companyName);
    if($jobsResult == null) {
        redirectToURL('controller.php?PAGE_ID=WELCOME_ERROR');
    }
    else {
        redirectToURL('controller.php?PAGE_ID=WELCOME');
    }
?>