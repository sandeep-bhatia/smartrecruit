<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 22/07/12
 * Time: 1:59 AM
 * To change this template use File | Settings | File Templates.
 */

include_once('../include/common.php');
include_once('../sql/SQLQuery.php');
include_once('DataAccess.php');
include_once('../dao/QuestionDAO.php');

class QuestionBankDAO {

	public static function findAllQuestionsInBank($recruiterId){
		$allQuestions = array();
		$result = DataAccess::execute(SQLQuery::$QUESTION_BANK_FIND_QUESTIONS_IN_BANK, array($recruiterId));

		if(isset($result)){
			foreach($result as $record){
				$questionId = $record['QUESTION_ID'];

				if(isset($questionId)){
					$question = QuestionDAO::findByPrimaryKey($questionId);
					if(isset($question)){
						array_push($allQuestions,$question);
					}
				}

			}
		}

		return $allQuestions;

	}

}

/*
 *
 *       checkNotEmpty($recruiterId);
        logToFile('Recruiter Job Id is: ' . $recruiterId);
        try {
            $result = DataAccess::execute(SQLQuery::$JOB_FIND_BY_RECRUITER_ID, array($recruiterId));
        } catch (PDOException $e) {
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

 *
 *
 * */