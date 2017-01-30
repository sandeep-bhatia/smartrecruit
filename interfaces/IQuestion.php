<?php

interface IQuestion {

	public function getQuestionId();

	public function getQuestionType();

	public function getAssessmentCriteria();

	public function getQuestionText();

	public function getTimeLimit();

	public function getAnswerType();

	public function isObjective();

	public function isSubjective();

}
