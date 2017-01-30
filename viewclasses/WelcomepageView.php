<?php
include_once('../objects/Authentication.php');
include_once('../objects/Recruiter.php');
include_once('../dao/RecruiterDAO.php');
//include_once('../objects/Job.php');
include_once('../dao/JobDAO.php');
include_once('LinkedInFetch.php');
include_once('../include/common.php');

session_start();

class WelcomepageView {

    public function initialize(){

        $linkedInFetch = new LinkedInFetch();
        $jsonLoggedInUser = $linkedInFetch->fetch($this->createLinkedInUserFetchUrl());
        $recruiterId = '';

        if(!isset($jsonLoggedInUser) || $jsonLoggedInUser == ''){
            logToFile('User could not be retrieved from LinkedIn');
            throw new Exception('User could not be retrieved from LinkedIn');
        } else {
            $jsonIterator = json_decode($jsonLoggedInUser, TRUE);
            $firstName = $jsonIterator['firstName'];
            $lastName = $jsonIterator['lastName'];
            $linkedInID = $jsonIterator['id'];
            $positions = $jsonIterator['positions'];


            $count = 0;
            foreach ($positions as $pos) {

                foreach ($pos as $val) {
                    if($val['isCurrent'] == true || $val['isCurrent'] == 1 ) {
                        $linkedInPresentCompanyName[$count] = $val['company']['name'];
                        $linkedInPresentCompanyId[$count]= $val['company']['id'];
                        logToFile('Company Id :'. $linkedInPresentCompanyId[$count]);
                        logToFile('Company Name :'. $linkedInPresentCompanyName[$count]);
                        $count++;
                    }
                }

            }
            logToFile('Calling findByLinkedInID in WelcomepageView.php');
            $recruiter = RecruiterDAO::findByLinkedInId($linkedInID);
           logToFile('Called findByLinkedInID in WelcomepageView.php');
            //check if logged in person exists in database
            if(!isset($recruiter)) {
            //if logged in person doesn't exist in db, then create a new Recruiter object and insert into db
                $recruiter = new Recruiter();
                logToFile('Logged in user is ' . $firstName . ' '. $lastName. ' with id : '. $linkedInID);
                $recruiter->setFirstName($firstName);
                $recruiter->setLastName($lastName);
                $recruiter->setLinkedInID($linkedInID);
/*                if(isset($linkedInPresentCompanyId) && isset($linkedInPresentCompanyName)) {
                    $recruiter->setCompanyId($linkedInPresentCompanyId);
                    $recruiter->setCompanyName($linkedInPresentCompanyName);
                }
*/
               logToFile('Going to insert Recruiter object in db');

               RecruiterDAO::insertObject($recruiter);

               logToFile('Recruiter object inserted to db');
               $recruiter = RecruiterDAO::findByLinkedInId($linkedInID);
               $recruiterId = $recruiter->getRecruiterID();
            } else {
                logToFile('Inside else, recruiter not found in db');
                $recruiterId = $recruiter->getRecruiterID();
                logToFile('Recruiter ID from DATABASE is :'. $recruiterId);
                logToFile('Recruiter FirstName from DATABASE is :'. $recruiter->getFirstName());
                logToFile('Recruiter LastName from DATABASE is :'. $recruiter->getLastName());

            }
//            $_SESSION['_linkedInUserObject'] = $recruiter;
            $_SESSION['_linkedInUserID'] = $linkedInID;
            $_SESSION['recruiterID'] = $recruiterId;
            $jobArray=$this->findJobsbyRecruiterId($recruiterId);
/*            logToFile('----Job Details ---');
            logToFile('Company Id' . $job->getCompanyId());
            logToFile('Job Description: ' . $job->getDescription());
*/
            return $jobArray;
        }

    }

    function createLinkedInUserFetchUrl(){
        $profile_fields =  ':(id,first-name,last-name,positions:(is-current,company:(id,name)))';
        $profile_url = 'http://api.linkedin.com/v1/people/~'.$profile_fields;
        return $profile_url;
    }

    function findJobsbyRecruiterId($recruiterId) {
        $jobArray = JobDAO::findJobByRecruiterId($recruiterId);
        return $jobArray;
    }
/*
    function createJobsPostedByUserUrl(){
        $profile_fields =  "(job-poster:(id,first-name,last-name,headline))";
        $profile_url = "http://api.linkedin.com/v1/job-search:".$profile_fields;
        return $profile_url;


        //http://api.linkedin.com/v1/job-search:(jobs:(id,customer-job-code,active,posting-date,expiration-date,posting-timestamp,expiration-timestamp,company:(id,name),position:(title,location,job-functions,industries,job-type,experience-level),skills-and-experience,description-snippet,description,salary,job-poster:(id,first-name,last-name,headline),referral-bonus,site-job-url,location-description))?distance=10&job-title=product&facets=company,location&facet=industry,6&facet=company,1288&sort=DA

    }*/
}
