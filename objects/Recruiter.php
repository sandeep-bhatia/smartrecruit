<?php
include_once '../interfaces/IRecruiter.php';
class Recruiter implements IRecuiter {
	private $recruiterID;
	private $linkedInID;
	private $firstName;
	private $lastName;
	private $companyId;
	private $companyName;
	private $jobs;


    public function setCompanyId($companyId)
    {
        if(!isset($companyId)) throw new Exception('Parameter is null');
        $this->companyId = $companyId;
    }

    public function getCompanyId()
    {
        return $this->companyId;
    }

    public function setCompanyName($companyName)
    {
        if(!isset($companyName)) throw new Exception('Parameter is null');
        $this->companyName = $companyName;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function setFirstName($firstName)
    {
        if(!isset($firstName)) throw new Exception('Parameter is null');
        $this->firstName = $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setJobs($jobs)
    {
        if(!isset($jobs)) throw new Exception('Parameter is null');
        $this->jobs = $jobs;
    }

    public function getJobs()
    {
        return $this->jobs;
    }

    public function setLastName($lastName)
    {
        if(!isset($lastName)) throw new Exception('Parameter is null');
        $this->lastName = $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLinkedInID($linkedInID)
    {
        if(!isset($linkedInID)) throw new Exception('Parameter is null');
        $this->linkedInID = $linkedInID;
    }

    public function getLinkedInID()
    {
        return $this->linkedInID;
    }

    public function setRecruiterID($recruiterID)
    {
        if(!isset($recruiterID)) throw new Exception('Parameter is null');
        $this->recruiterID = $recruiterID;
    }

    public function getRecruiterID()
    {
        return $this->recruiterID;
    }
}