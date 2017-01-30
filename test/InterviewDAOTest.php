<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 21/07/12
 * Time: 7:22 PM
 * To change this template use File | Settings | File Templates.
 */

include_once('../include/common.php');
include_once('../objects/SubjectiveQuestion.php');
include_once('../objects/ObjectiveQuestion.php');
include_once('../objects/Choice.php');
include_once('../dao/InterviewDAO.php');

class InterviewDAOTest {

	public static function testAddMultipleQuestions(){

		$question = new SubjectiveQuestion();
		$question->setQuestionText('what is objective Java');
		$question->setAnswerType('W');
		$question->setAssessmentCriteria('Upto to the level skills in languages');
		$question->setTimeLimit(134);
		$question->setAddToQuestionBank(1);

		$objQuestion = new ObjectiveQuestion();
		$objQuestion->setQuestionText('what is AJAX');
		$objQuestion->setAssessmentCriteria('Upto to the level skills in languages');
		$objQuestion->setTimeLimit(70);
		$objQuestion->setAddToQuestionBank(1);
		$objQuestion->setChoices(array(new Choice(1,'Its Language',1),new Choice(2,'Its computer',1),
			new Choice(3,'mouse',0),new Choice(4,'desktop',1)));

		$insertedQuestions = InterviewDAO::addMultipleQuestion(4,6,1,array($question, $objQuestion));

		return $insertedQuestions;
	}

	public static function testUpdateMultipleQuestions(){

		$question = new SubjectiveQuestion();
		$question->setQuestionId('99');
		$question->setQuestionText('what is objective Java UPDATED');
		$question->setAnswerType('W');
		$question->setAssessmentCriteria('Upto to the level skills in languages UPDATED');
		$question->setTimeLimit(595);

		$objQuestion = new ObjectiveQuestion();
		$objQuestion->setQuestionId('100');
		$objQuestion->setQuestionText('what is AJAX UPDATED');
		$objQuestion->setAssessmentCriteria('Upto to the level skills in languages UPDATED');
		$objQuestion->setTimeLimit(570);
		$objQuestion->setChoices(array(new Choice(1,'Its Language UP',0),new Choice(2,'Its computer UP',0),
			new Choice(3,'mouse',1),new Choice(4,'desktop UP',1)));

		$insertedQuestions = InterviewDAO::updateMultipleQuestion(array($question, $objQuestion));

		return $insertedQuestions;
	}



}

//InterviewDAOTest::testUpdateMultipleQuestions();


$insertedQuestions = InterviewDAOTest::testAddMultipleQuestions();
foreach($insertedQuestions as $questionId) {
	print('InterviewId : '.$questionId);
}

