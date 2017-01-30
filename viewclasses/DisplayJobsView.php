<?php
include_once 'LinkedInFetch.php';
include_once '../objects/Job.php';
include_once '../dao/JobDAO.php';
include_once '../include/common.php';

session_start();
//COMMENTS : View should again be external system agnositic we dont want linked in creeeping into our code everywhere 
//This will easy to change now and very difficult to change later
//I am not adding these comments on other files but these comments need to be unified for all files

class DisplayJobsView {

    // Not sure if we can search with job id
    public function searchJobById($jobId){
        if(!isset($jobId)) throw new Exception('JobId parameter is null');
    }

    public function searchJobsByCompanyName($companyName){
        $requestQuery = "company-name=".$companyName;

        if(!isset($companyName)) throw new Exception('Job name parameter is null');
        $url = 'http://api.linkedin.com/v1/job-search:(jobs:(id,customer-job-code,active,posting-date,expiration-date,';
        $url .= 'posting-timestamp,expiration-timestamp,company:(id,name),position:(title,location,job-functions,';
        $url .= 'industries,job-type,experience-level),skills-and-experience,description-snippet,description,salary,';
        $url.= 'job-poster:(id,first-name,last-name,headline),referral-bonus,site-job-url,location-description))?';
        $url .= $requestQuery;
        //$url .= "job-poster=AdVLY2dq9a";

        $linkedInFetch = new LinkedInFetch();
        $jsonJobsResult = $linkedInFetch->fetch($url);
//        logToFile($jsonJobsResult);

        if(!isset($jsonJobsResult) || $jsonJobsResult == ''){
            logToFile('No jobs found');
            return null;
        } else {
            $jsonIterator = json_decode($jsonJobsResult, TRUE);
            $count = 0;
            foreach ($jsonIterator as $key=>$valueArray) {

                foreach($valueArray as $value) {
                    foreach($value as $jsonJob) {
//                      if($value['jobPoster']['id'] == $_SESSION['_linkedInUserID']) {
                        if($jsonJob['jobPoster']['id'] == 'aiXHk3SUyY' || $jsonJob['jobPoster']['id'] =='bd_Luh7QaD') {
 //                       if($jsonJob['jobPoster']['id'] == 'bd_Luh7QaD') {
                            //for testing, a recruiter in LinkedIn is being used
                            $linkedInJobId = $jsonJob['id'];
                            logToFile("******* Job Number " . $count . "*******");
                            logToFile('Job Id:' . $jsonJob['id']);
                            logToFile('Job Poster is: ' . $jsonJob['jobPoster']['id']);
                            //unset($job);
                            //check if the job object exists in database or not
                            $job = JobDAO::findByLinkedInJobId($linkedInJobId);
                            if(!isset($job)) {
                                //create job object
                                logToFile('Job not found in database');
                                $job = new Job();
                                $this->populateJobObject($job, $jsonJob, $linkedInJobId);
                                logToFile("Recruiter Id: " . $job->getRecruiterId());
                                logToFile('Title: ' . $job->getTitle());
                                logToFile('Location: ' . $job->getLocation());
 //                               logToFile('Description: ' .$jsonJob['description']);
                                logToFile('IsActive: ' .$job->getIsActive());
                                logToFile('Posting Date: ' .$job->getPostingDate());
                                logToFile('Expiration Date: ' .$job->getExpirationDate());
//                                $job->setCompanyName($jsonJob['company']['name']);
                                logToFile('Company Id: ' .$job->getCompanyId());
                                logToFile('Posted By: ' . $job->getPostedByLinkedInId());
                                //going to insert job object to database
                                logToFile("LinkedIn Job Id: " . $job->getLinkedInJobId());
                                logToFile('Going to insert job object to database');

                                JobDAO::insertObject($job);

                                logToFile('Job object is inserted to database');
                                $jobsArray[$count] = $job;
                                $count++;
                            }
                            else {
                                logToFile('******Job already in database *****');
                                logToFile('Job ID in database: ' . $job->getLinkedInJobId());
                            }
                        }

                    }

                }
            return $jobsArray;
            }

        }
    }

    private function populateJobObject($job, $jsonJob, $linkedInJobId)
    {
        $job->setRecruiterId($_SESSION['recruiterID']);
        $job->setCompanyId($jsonJob['company']['id']);
        $job->setLinkedInJobId($linkedInJobId);
        $job->setTitle($jsonJob['position']['title']);
        $job->setIsActive($jsonJob['active']);
        $job->setLocation($jsonJob['position']['location']['name']);
        $job->setDescription($jsonJob['description']);
        $postingDate = $this->formatDate($jsonJob['postingDate']);
        $job->setPostingDate($postingDate);
        $expirationDate = $this->formatDate($jsonJob['expirationDate']);
        $job->setExpirationDate($expirationDate);
        $job->setPostedByLinkedInId($_SESSION['_linkedInUserID']);
    }


    function formatDate($inputDate) {
        $year = $inputDate['year'];
        $month = $inputDate['month'];
        $day = $inputDate['day'];
        return $year. '-' . $month . '-' . $day;
    }

}
