<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 08/07/12
 * Time: 11:27 PM
 * To change this template use File | Settings | File Templates.
 */
include_once('../interfaces/IInterview.php');

class Interview implements IInterview{

	private $noOfStages;

	private $stageQuestionMap;

	// Should return collection of Question objects within that stage
	public function getQuestionsForStage($stageNo){
		return $this->stageQuestionMap[$stageNo];
	}

	public function addQuestionsToStage($stageNo, $questions){
		$this->stageQuestionMap[$stageNo] = $questions;
	}

	public function setNoOfStages($noOfStages) {
		$this->noOfStages = $noOfStages;
	}

	public function getNoOfStages() {
		return $this->noOfStages;
	}

	// Check later if one questions need to be added . Not required now!

}
