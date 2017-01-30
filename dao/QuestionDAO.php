<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tarun
 * Date: 22/07/12
 * Time: 1:58 AM
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

class QuestionDAO {

	public static function findByPrimaryKey($questionId){
		checkNotEmpty($questionId);
		$question = null;

		$result = DataAccess::execute(SQLQuery::$QUESTION_FIND_BY_PRIMARY_KEY, array($questionId));

		if(isset($result)){
			$choices = array();
			foreach($result as $record){
				$questionType = $record['QUESTION_TYPE'];

				if(!isset($question)){
					if($questionType == AbstractQuestion::$QUESTION_TYPE_OBJECTIVE){
						$question = new ObjectiveQuestion();
					} else if($questionType == AbstractQuestion::$QUESTION_TYPE_SUBJECTIVE){
						$question = new SubjectiveQuestion();
					}else {
						throw new Exception('Question Type Unknown from database');
					}

					$question->setInterviewId($record['INTERVIEW_ID']);
					$question->setStageNo($record['STAGE_NO']);
					$question->setQuestionId($record['QUESTION_ID']);
					$question->setQuestionText($record['DESCRIPTION']);
					$question->setAssessmentCriteria($record['ASSESSMENT_CRITERIA']);
					$question->setTimeLimit($record['TIME_LIMIT']);
				}

				if($questionType == AbstractQuestion::$QUESTION_TYPE_OBJECTIVE){
					$choice = new Choice(
						$record['CHOICE_ID'],
						$record['CHOICE_DESCRIPTION'],
						$record['CORRECT_CHOICE']
						);

					array_push($choices, $choice);
				}

			}

			if(count($choices)>0){
				$question->setChoices($choices);
			}
		}

		return $question;

	}

	// To Implement that..
	private static function convertTimeFormatToMinutes($timeLimitInTime){
		checkNotEmpty($timeLimitInTime);

		if(!is_int($timeLimitInTime)){
			throw new Exception("Time limit not defined as a number");
		}

		$timeLimit = $timeLimitInTime;
		if($timeLimitInTime < 60){
			$timeLimit = $timeLimitInTime*100;
		} else {
			$hours = floor($timeLimitInTime/60);
			$minutes = $timeLimitInTime % 60;
			$timeLimit = $hours.$minutes.'00';
		}
		return $timeLimit;
	}

}
