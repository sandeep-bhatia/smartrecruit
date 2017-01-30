<?php

class ObjectiveQuestion extends AbstractQuestion {

	private $choices;

	function __construct(){
		$this->setQuestionType(AbstractQuestion::$QUESTION_TYPE_OBJECTIVE);
		parent::setAnswerType(AbstractQuestion::$ANSWER_TYPE_OBJECTIVE);
	}

	protected function setQuestionType($questionType) {
		$this->questionType = $questionType;
	}

	public function setChoices($choices) {
		$this->choices = $choices;
	}

	public function getChoices() {
		return $this->choices;
	}

}
