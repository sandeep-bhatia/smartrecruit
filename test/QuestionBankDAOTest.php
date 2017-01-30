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
include_once('../dao/QuestionBankDAO.php');

class QuestionBankDAOTest {

	public static function testFindAllQuestionsInBank($recruiterId){

		$allQuestions = QuestionBankDAO::findAllQuestionsInBank($recruiterId);

		foreach($allQuestions as $question){
			print('Interview Id:'.$question->getInterviewId());
			print('Stage:'.$question->getStageNo());
			print('Question Id:'.$question->getQuestionId());
			print('Description:'.$question->getQuestionText());
			print('Assessment Criteria:'.$question->getAssessmentCriteria());
			print('Time:'.$question->getTimeLimit());
			print('QuestionType:'.$question->getQuestionType());
			print('IsObjective:'.$question->isObjective());

			if($question->isObjective()){
				$choices = $question->getChoices();
				print('Choices:'.count($choices));

				foreach($choices as $choice){
					print('1111');
					print('Choice ID : '.$choice->getId());
					print('Choice Description : '.$choice->getDescription());
					print('Correct Choice : '.$choice->getCorrectChoice());
				}
			}
		}
	}

}

QuestionBankDAOTest::testFindAllQuestionsInBank('4');
