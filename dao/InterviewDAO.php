<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 08/07/12
 * Time: 11:27 PM
 * To change this template use File | Settings | File Templates.
 */

include_once('../include/common.php');
include_once('../sql/SQLQuery.php');
include_once('DataAccess.php');
include_once('../objects/Interview.php');
include_once('../interfaces/IQuestion.php');
include_once('../objects/SubjectiveQuestion.php');
include_once('../objects/ObjectiveQuestion.php');
include_once('../objects/Choice.php');


class InterviewDAO {

	public static function createInterview($jobId){
		checkNotEmpty($jobId);

		$interviewId = DataAccess::insert(SQLQuery::$INTERVIEW_CREATE_INTERVIEW, array($jobId), null);
		if(!isset($interviewId)){
			throw new Exception('Interview creation failed, JobId : '.$jobId);
		}
		return $interviewId;

	}

	public static function addMultipleQuestion($recruiterId, $interviewId, $stageNo, $questions){
		$connection = ConnectionFactory::createNewConnection();
		$connection->beginTransaction();
		$insertedQuestionIds = array();
		foreach($questions as $question) {
			$insertedQues = InterviewDAO::addSingleQuestion($recruiterId,$interviewId, $stageNo,$question,$connection);
			array_push($insertedQuestionIds,$insertedQues);
		}
		$connection->commit();
		return $insertedQuestionIds;
	}

	public static function addSingleQuestion($recruiterId, $interviewId, $stageNo, IQuestion $question, $connection){
		checkNotEmpty($recruiterId);
		checkNotEmpty($interviewId);
		checkNotEmpty($stageNo);
		checkNotEmpty($question);
		checkNotEmpty($question->getQuestionText());
		checkNotEmpty($question->getQuestionType());
		checkNotEmpty($question->getAnswerType());
		checkNotEmpty($question->getTimeLimit());

		$localTrnx = false;
		if(!isset($connection)){
			$localTrnx = true;
			$connection = ConnectionFactory::createNewConnection();
			$connection->beginTransaction();
		}

		$timeLimitInMinutes = $question->getTimeLimit();
		$timeLimit = self::convertTimeToTimeFormat($timeLimitInMinutes);

		$questionId = DataAccess::insert(SQLQuery::$QUESTION_CREATE_QUESTION,
			array($interviewId, $stageNo, $question->getQuestionText(),
				$question->getQuestionType(), $question->getAnswerType(),
				$question->getAssessmentCriteria(), $timeLimit),$connection);

		if(!isset($questionId)){
			throw new Exception('Question creation failed, InterviewId :  '.$interviewId);
			$connection->rollBack();
		}

		if($question instanceof ObjectiveQuestion){
//			print('its objective questions');
			checkNotEmpty($question->getChoices());

				$choices = $question->getChoices();
				$correctChoiceFound = false;

				foreach($choices as $choice) {
//					print($choice->getId());

					$correctChoice = $choice->getCorrectChoice();
					if (isset($correctChoice)){
						if($correctChoice != 0 && $correctChoice != 1){
							throw new Exception('Correct Choice of Objective Questions not set correctly. Should be either 0 or 1');
							$connection->rollBack();
						}

						if($correctChoice == 1){
							$correctChoiceFound = true;
						}

					}

					DataAccess::insert(SQLQuery::$OBJECTIVE_CHOICES_CREATE,
						array($questionId, $choice->getId(), $choice->getDescription(),
							$correctChoice),$connection);

				}

				if(!$correctChoiceFound){
					throw new Exception('No correct option set in Objective type question');
					$connection->rollBack();
				}
			}

		// Add To Question Bank
		$addToQuestionBank = $question->addToQuestionBank();

		if($addToQuestionBank != 0 && $addToQuestionBank != 1){
			throw new Exception('Add To Question Bank not set correctly. Should be either 0 or 1');
			$connection->rollBack();
		}

		if($addToQuestionBank ==1){
			DataAccess::insert(SQLQuery::$QUESTION_BANK_CREATE,
				array($recruiterId, $questionId),$connection);
		}

		if($localTrnx){
			$connection->commit();
		}

		return $questionId;

	}


	public static function updateMultipleQuestion($questions){
		$connection = ConnectionFactory::createNewConnection();
		$connection->beginTransaction();
		foreach($questions as $question) {
			InterviewDAO::updateSingleQuestion($question,$connection);
		}
		$connection->commit();
	}


	public static function updateSingleQuestion(IQuestion $question, $connection){
		checkNotEmpty($question);
//		print('Question id :'.$question->getQuestionId());
		checkNotEmpty($question->getQuestionId());
		checkNotEmpty($question->getQuestionText());
		checkNotEmpty($question->getTimeLimit());

		$localTrnx = false;
		if(!isset($connection)){
			$localTrnx = true;
			$connection = ConnectionFactory::createNewConnection();
			$connection->beginTransaction();
		}

		$timeLimitInMinutes = $question->getTimeLimit();
		$timeLimit = self::convertTimeToTimeFormat($timeLimitInMinutes);

		DataAccess::execute(SQLQuery::$QUESTION_UPDATE_QUESTION,
			array($question->getQuestionText(), $question->getAssessmentCriteria(),
				$timeLimit, $question->getQuestionId()),$connection);


		if($question instanceof ObjectiveQuestion){
			checkNotEmpty($question->getChoices());

			$choices = $question->getChoices();
			$correctChoiceFound = false;

			foreach($choices as $choice) {
				checkNotEmpty($choice->getDescription());

				$correctChoice = $choice->getCorrectChoice();
				if (isset($correctChoice)){
					if($correctChoice != 0 && $correctChoice != 1){
						throw new Exception('Correct Choice of Objective Questions not set correctly. Should be either 0 or 1');
						$connection->rollBack();
					}

					if($correctChoice == 1){
						$correctChoiceFound = true;
					}

				}

				DataAccess::execute(SQLQuery::$OBJECTIVE_CHOICES_UPDATE,
					array($choice->getDescription(),
						$correctChoice, $question->getQuestionId(), $choice->getId()),$connection);

			}

			if(!$correctChoiceFound){
				throw new Exception('No correct option set in Objective type question');
				$connection->rollBack();
			}
		}

		if($localTrnx){
			$connection->commit();
		}

	}




	// currently hardcoded for view to proceed
	public static function findAllQuestionsForInterview($interviewId){
		checkNotEmpty($interviewId);

		$question = new SubjectiveQuestion();
		$question->setQuestionText('what is objective C');
		$question->setAnswerType('W');
		$question->setAssessmentCriteria('C language understanding');
		$question->setTimeLimit(134);

		$objQuestion = new ObjectiveQuestion();
		$objQuestion->setQuestionText('What is DOM');
		$objQuestion->setAssessmentCriteria('html & javascript language skills');
		$objQuestion->setTimeLimit(70);
		$objQuestion->setChoices(array(new Choice(1,'Its Programming Language',1),new Choice(2,'Its computer',0),
			new Choice(3,'mouse',0),new Choice(4,'Used in browsers',1)));

		return array($question,$objQuestion);

	}

	private static function convertTimeToTimeFormat($timeLimitInMinutes){
		checkNotEmpty($timeLimitInMinutes);

		if(!is_int($timeLimitInMinutes)){
			throw new Exception("Time limit not defined as a number");
		}

		$timeLimit = $timeLimitInMinutes;
		if($timeLimitInMinutes < 60){
			$timeLimit = $timeLimitInMinutes*100;
		} else {
			$hours = floor($timeLimitInMinutes/60);
			$minutes = $timeLimitInMinutes % 60;
			$timeLimit = $hours.$minutes.'00';
		}
		return $timeLimit;
	}

}
