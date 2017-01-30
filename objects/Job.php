<?php
include_once("../include/common.php");
include_once("../interfaces/IJob.php");
class Job implements IJob{

    private $jobId;
    private $recruiterId;
    private $companyId;
    private $linkedInJobId;
    private $title;
    private $location;
    private $description;
    private $isActive;
    private $postingDate;
    private $expirationDate;
    private $postedByLinkedInId;


	public function setDescription($description) {
		checkNotEmpty($description);
		$this->description = $description;
	}

	public function getDescription() {
		return $this->description;
	}

	public function setExpirationDate($expirationDate) {
		checkNotEmpty($expirationDate);
		$this->expirationDate = $expirationDate;
	}

	public function getExpirationDate() {
		return $this->expirationDate;
	}

	public function setIsActive($isActive) {
		checkNotEmpty($isActive);
		$this->isActive = $isActive;
	}

	public function getIsActive() {
		return $this->isActive;
	}

	public function setJobId($jobId) {
		checkNotEmpty($jobId);
		$this->jobId = $jobId;
	}

	public function getJobId() {
		return $this->jobId;
	}

	public function setLocation($location) {
		checkNotEmpty($location);
		$this->location = $location;
	}

	public function getLocation() {
		return $this->location;
	}

	public function setPostedByLinkedInId($postedByLinkedInId) {
		checkNotEmpty($postedByLinkedInId);
		$this->postedByLinkedInId = $postedByLinkedInId;
	}

	public function getPostedByLinkedInId() {
		return $this->postedByLinkedInId;
	}

	public function setPostingDate($postingDate) {
		checkNotEmpty($postingDate);
		$this->postingDate = $postingDate;
	}

	public function getPostingDate() {
		return $this->postingDate;
	}

	public function setTitle($title) {
		checkNotEmpty($title);
		$this->title = $title;
	}

	public function getTitle() {
		return $this->title;
	}

	public function setCompanyId($companyId) {
		checkNotEmpty($companyId);
		$this->companyId = $companyId;
	}

	public function getCompanyId() {
		return $this->companyId;
	}

	public function setLinkedInJobId($linkedInJobId) {
		checkNotEmpty($linkedInJobId);
		$this->linkedInJobId = $linkedInJobId;
	}

	public function getLinkedInJobId() {
		return $this->linkedInJobId;
	}

	public function setRecruiterId($recruiterId) {
		checkNotEmpty($recruiterId);
		$this->recruiterId = $recruiterId;
	}

	public function getRecruiterId() {
		return $this->recruiterId;
	}


}
