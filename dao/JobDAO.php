<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 10/07/12
 * Time: 1:17 AM
 * To change this template use File | Settings | File Templates.
 */
include_once("../include/common.php");
include_once("../sql/SQLQuery.php");
include_once("DataAccess.php");
include_once("../objects/Recruiter.php");


class JobDAO {


	public static function findByPrimaryKey($jobId) {
		checkNotEmpty($jobId);
		//DataAccess::execute(SQLQuery::$RECRUITER_FIND_BY_PRIMARY_KEY, $linkedInID);
	}

	public static function findByLinkedInJobId($linkedInJobId) {
		checkNotEmpty($linkedInJobId);
        logToFile('LinkedIn Job Id is: ' . $linkedInJobId);
        try {
		    $result = DataAccess::execute(SQLQuery::$JOB_FIND_BY_LINKEDIN_JOB_ID, array($linkedInJobId));
        } catch (Exception $e) {
            logToFile('Exception in JobDAO::findByLinkedInJobId(). Exception is: '. $e->getMessage());
        }
		$job = null;
		if(isset($result)){
			$record = $result['0'];
			if($record != null) {
				$job = new Job();
				// INTERVIEW_ID & INTERVIEW_URL not yet created
				$job->setJobId($record['JOB_ID']);
				$job->setRecruiterId($record['RECRUITER_ID']);
				$job->setCompanyId($record['COMPANY_ID']);
				$job->setLinkedInJobId($record['LINKEDIN_JOB_ID']);
				$job->setTitle($record['TITLE']);
				$job->setLocation($record['LOCATION']);
				$job->setDescription($record['DESCRIPTION']);
				$job->setIsActive($record['IS_ACTIVE']);
				$job->setPostingDate($record['POSTING_DATE']);
				$job->setExpirationDate($record['EXPIRATION_DATE']);
				$job->setPostedByLinkedInId($record['POSTED_BY_LINKEDIN_ID']);
//				print($job->getJobId());
			}
		}

//		print_r("job is :".$job);
//		if(!isset($job)){print("job is not set");}
		return $job;
	}

    public static function findJobByRecruiterId($recruiterId) {
        checkNotEmpty($recruiterId);
        logToFile('Recruiter Job Id is: ' . $recruiterId);
        try {
            $result = DataAccess::execute(SQLQuery::$JOB_FIND_BY_RECRUITER_ID, array($recruiterId));
        } catch (Exception $e) {
            logToFile("Error in JobDAO::findJobByRecruiterId(). Error Message is: " . $e->getMessage());
        }
        $job = null;
        if(isset($result)){
            $count = 0;
            foreach ($result as $record) {

                if($record != null) {
                    $job = new Job();
                    // INTERVIEW_ID & INTERVIEW_URL not yet created
                    $job->setJobId($record['JOB_ID']);
                    $job->setRecruiterId($record['RECRUITER_ID']);
                    $job->setCompanyId($record['COMPANY_ID']);
                    $job->setLinkedInJobId($record['LINKEDIN_JOB_ID']);
                    $job->setTitle($record['TITLE']);
                    $job->setLocation($record['LOCATION']);
                    $job->setDescription($record['DESCRIPTION']);
                    $job->setIsActive($record['IS_ACTIVE']);
                    $job->setPostingDate($record['POSTING_DATE']);
                    $job->setExpirationDate($record['EXPIRATION_DATE']);
                    $job->setPostedByLinkedInId($record['POSTED_BY_LINKEDIN_ID']);
    //				print($job->getJobId());
                    $jobArray[$count] = $job;

                }
                $count++;
            }

        }

//		print_r("job is :".$job);
//		if(!isset($job)){print("job is not set");}
        return $jobArray;
    }


	public static function insertObject($job) {
		checkNotEmpty($job);
		checkNotEmpty($job->getRecruiterId());
		checkNotEmpty($job->getCompanyId());
		checkNotEmpty($job->getLinkedInJobId());
        checkNotEmpty($job->getTitle());
        checkNotEmpty($job->getIsActive());
        checkNotEmpty($job->getLocation());
        checkNotEmpty($job->getDescription());
        checkNotEmpty($job->getPostingDate());
        checkNotEmpty($job->getExpirationDate());
        checkNotEmpty($job->getPostedByLinkedInId());

        try {
		        DataAccess::execute(SQLQuery::$JOB_INSERT_JOB, array($job->getRecruiterId(),$job->getCompanyId(),
                    $job->getLinkedInJobId(), $job->getTitle(),$job->getIsActive(),$job->getLocation(),$job->getDescription(),
                    $job->getPostingDate(),$job->getExpirationDate(),$job->getPostedByLinkedInId()));
        } catch (Exception $e) {
                logToFile('Exception in JobDAO::insertObject(). Error Message is: ' . $e->getMessage());
        }
	}


}

