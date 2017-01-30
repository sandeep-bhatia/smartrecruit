<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 08/07/12
 * Time: 11:27 PM
 * To change this template use File | Settings | File Templates.
 */

include_once('../include/common.php');
include_once('../sql/SQLQuery.php');
include_once('DataAccess.php');
include_once('../objects/Recruiter.php');


class RecruiterDAO {


	public static function findByPrimaryKey($recruiterID) {
    	checkNotEmpty($recruiterID);
        try {
            DataAccess::execute(SQLQuery::$RECRUITER_FIND_BY_PRIMARY_KEY, $recruiterID);
        } catch (Exception $e) {
            logToFile('Exception in RecruiterPDO::findByPrimaryKey(). Exception is: '. $e->getMessage());
        }

	}
	
	public static function findByLinkedInId($linkedInID) {
        checkNotEmpty($linkedInID);
        try {
            $result = DataAccess::execute(SQLQuery::$RECRUITER_FIND_BY_LINKEDIN_KEY, array($linkedInID));
        } catch (Exception $e) {
            logToFile('Exception in RecruiterPDO::findByLinkedInId(). Exception is: '. $e->getMessage());
        }
        $recruiter = null;
        if(isset($result)){
            $record = $result['0'];
               if($record != null) {
                    $recruiter = new Recruiter();
                    $recruiter->setRecruiterID($record['RECRUITER_ID']);
                    $recruiter->setFirstName($record['FIRSTNAME']);
                    $recruiter->setLastName($record['LASTNAME']);
//                    print($recruiter->getFirstName());
               }
        }

        return $recruiter;
	}
	
	public static function insertObject($recruiter) {
        checkNotEmpty($recruiter);
        checkNotEmpty($recruiter->getLinkedInID());
        checkNotEmpty($recruiter->getFirstName());
        checkNotEmpty($recruiter->getLastName());

        try {
            DataAccess::execute(SQLQuery::$RECRUITER_INSERT_RECRUITER, array($recruiter->getLinkedInID(),
            $recruiter->getFirstName(), $recruiter->getLastName()));
        } catch (Exception $e) {
            logToFile('Exception in RecruiterPDO::insertObject(). Exception is: '. $e->getMessage());
        }

	}
	
}
/*
$testRecruiter = new Recruiter();
$testRecruiter->setLinkedInID("2323");
$testRecruiter->setFirstName("Fanboy");
$testRecruiter->setLastName("Hello");
RecruiterDAO::insertObject($testRecruiter);
*/

//RecruiterDAO::findByLinkedInId("111asda");