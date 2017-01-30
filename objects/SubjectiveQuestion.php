<?php

include_once('AbstractQuestion.php');
class SubjectiveQuestion extends AbstractQuestion {

	function __construct(){
		$this->setQuestionType(AbstractQuestion::$QUESTION_TYPE_SUBJECTIVE);
	}

	protected function setQuestionType($questionType) {
		$this->questionType = $questionType;
	}


	public function setAnswerType($answerType) {
		parent::setAnswerType($answerType);
	}


}
