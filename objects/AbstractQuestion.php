<?php
include_once('../interfaces/IQuestion.php');

abstract class AbstractQuestion implements IQuestion{

	public static $QUESTION_TYPE_OBJECTIVE = 'O';
	public static $QUESTION_TYPE_SUBJECTIVE = 'S';

	public static $ANSWER_TYPE_OBJECTIVE = 'O';
	public static $ANSWER_TYPE_WRITTEN = 'W';
	public static $ANSWER_TYPE_VIDEO = 'V';

	public static $questionTypes = array('O' => 'Objective' , 'S' => 'Subjective');

	public static $answerTypes = array('O' => 'Objective' , 'W' => 'Written' , 'V' => 'Video');

	protected $questionText;

	protected $questionType;

	protected $answerType;

	protected $assessmentCriteria;

	protected $timeLimit;

	protected $questionId;

	protected $addToQuestionBank;

	protected $interviewId;

	protected $stageNo;


	public function setAssessmentCriteria($assessmentCriteria) {
		$this->assessmentCriteria = $assessmentCriteria;
	}

	public function getAssessmentCriteria() {
		return $this->assessmentCriteria;
	}

	public function setQuestionText($questionText) {
		$this->questionText = $questionText;
	}

	public function getQuestionText() {
		return $this->questionText;
	}

	public function setTimeLimit($timeLimit) {
		$this->timeLimit = $timeLimit;
	}

	public function getTimeLimit() {
		return $this->timeLimit;
	}

	protected function setAnswerType($answerType) {
		$this->answerType = $answerType;
	}

	public function getAnswerType() {
		return $this->answerType;
	}



	protected abstract function setQuestionType($questionType);

	public function setQuestionId($questionId) {
		$this->questionId = $questionId;
	}

	public function getQuestionId() {
		return $this->questionId;
	}

	public function setAddToQuestionBank($addToQuestionBank) {
		$this->addToQuestionBank = $addToQuestionBank;
	}

	public function addToQuestionBank() {
		return $this->addToQuestionBank;
	}

	public function setInterviewId($interviewId) {
		$this->interviewId = $interviewId;
	}

	public function getInterviewId() {
		return $this->interviewId;
	}

	public function setStageNo($stageNo) {
		$this->stageNo = $stageNo;
	}

	public function getStageNo() {
		return $this->stageNo;
	}

	public function getQuestionType() {
		return $this->questionType;
	}

	public function isObjective() {
		$quesType = $this->getQuestionType();
		if($quesType == self::$QUESTION_TYPE_OBJECTIVE){
			return true;
		}
	}

	public function isSubjective() {
		$quesType = $this->getQuestionType();
		if($quesType == self::$QUESTION_TYPE_SUBJECTIVE){
			return true;
		}
	}


}
