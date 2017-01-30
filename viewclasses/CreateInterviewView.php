<?php
include_once '../include/common.php';
include_once '../dao/InterviewDAO.php';
include_once '../interfaces/IQuestion.php';
include_once '../objects/Choice.php';
include_once '../objects/ObjectiveQuestion';
include_once '../objects/SubjectiveQuestion';

session_start();
class InterviewView {
    private $jobId;
    private $stageNumber;
    private $jobTitle;

    public function setJobId($jobId)
    {
        $this->jobId = $jobId;
    }

    public function getJobId()
    {
        return $this->jobId;
    }

    public function setJobTitle($jobTitle)
    {
        $this->jobTitle = $jobTitle;
    }

    public function getJobTitle()
    {
        return $this->jobTitle;
    }

    public function setStageNumber($stageNumber)
    {
        $this->stageNumber = $stageNumber;
    }

    public function getStageNumber()
    {
        return $this->stageNumber;
    }

    function __construct() {
        $this->jobId = $_REQUEST['jobId'];
        $this->jobTitle = $_REQUEST['jobTitle'];
        //TODO stage number should come from createinterview.php
        $this->stageNumber = "1";

        logToFile("Job Id:" . $this->jobId);
        logToFile("Job Title: ".$this->jobTitle);
    }
}

    $interviewView = new InterviewView();
    $interviewId = InterviewDAO::createInterview($interviewView->getJobId());
    $jsonString = $_REQUEST['QuestionObjects'];

    $jsonString = str_replace("\\","",$jsonString);
    $questionObjectsArray = json_decode($jsonString,true);;


    if(!isset($questionObjectsArray)) {
        echo "Json Decode is null";
    }
    $arrayOfNewQuestions = array();
    $arrayOfUpdatedQuestions = array();

    foreach($questionObjectsArray as $question){
        if($question["questionType"] == "objective") {
            $isSaved = $question["isSaved"];
            if($isSaved == "true") {
                continue;
            }
            $objectiveQuestion = new ObjectiveQuestion();

            if($isSaved == "false") {
                $questionId = $question["questionId"];
                $objectiveQuestion->setQuestionId($questionId);
            }

            $objectiveQuestion->setQuestionText($question["questionText"]);
            $objectiveQuestion->setAssessmentCriteria($question["assCriteria"]);
            $objectiveQuestion->setTimeLimit((int)$question['timeLimit']);
            $objectiveQuestion->setAddToQuestionBank($question['addToQuestionBank']);
            logToFile("Question Text is:".$question["questionText"] );
            logToFile("Assessment Criteria is:".$question["assCriteria"]);

            $choices = array($question['choice1'],$question['choice2'],$question['choice3'],$question['choice4']);
            $correctChoices = $question['choices'];

            $correctChoices = substr($correctChoices, 0, -1);
            $correctChoiceArray = explode("|",$correctChoices);
            //    print_r($correctChoiceArray);
            $choiceObjArray = array();
            for($i=0;$i<4;$i++) {
                $choice = $choices[$i];

                $correctChoice = $correctChoiceArray[$i];
                $choiceObj = new Choice($i,$choice,$correctChoice);
                $choiceObjArray[$i] = $choiceObj;
                logToFile("Choice :" . $choice);
                logToFile("Is Correct Choice :".$correctChoice);

            }
            $objectiveQuestion->setChoices($choiceObjArray);
            if($isSaved == "false") {
                array_push($arrayOfUpdatedQuestions,$objectiveQuestion);
            } elseif($isSaved == "new") {
                array_push($arrayOfNewQuestions,$objectiveQuestion);
            }

        }
        elseif($question["questionType"] == "video") {


            $isSaved = $question["isSaved"];
            if($isSaved == "true") {
                continue;
            }
            $subjectiveQuestion = new SubjectiveQuestion();

            if($isSaved == "false") {
                $questionId = $question["questionId"];
                $subjectiveQuestion->setQuestionId($questionId);
            }

            $subjectiveQuestion->setQuestionText($question["questionText"]);
            $subjectiveQuestion->setAssessmentCriteria($question["assCriteria"]);
            $subjectiveQuestion->setTimeLimit((int)$question['timeLimit']);
            $subjectiveQuestion->setAddToQuestionBank($question['addToQuestionBank']);
            $subjectiveQuestion->setAnswerType("V");
            logToFile("Question Text is:".$question["questionText"] );
            logToFile("Assessment Criteria is:".$question["assCriteria"]);


            if($isSaved == "false") {
                array_push($arrayOfUpdatedQuestions,$subjectiveQuestion);
            } elseif($isSaved == "new") {
                array_push($arrayOfNewQuestions,$subjectiveQuestion);
            }

        }

    }
    $recruiterId = $_SESSION['recruiterID'];
    $questionIDArray ="";
    $questionIDArray = InterviewDAO::addMultipleQuestion($recruiterId,$interviewId,$interviewView->getStageNumber(),$arrayOfNewQuestions);
    InterviewDAO::updateMultipleQuestion($arrayOfUpdatedQuestions);
    $tempQid = $_REQUEST["QIDS"];
    if($tempQid == null || $tempQid=="" || $tempQid=="undefined") {
        $qId = "";
    } else {
        $qId = $tempQid;
    }

    foreach($questionIDArray as $id) {
        $qId = $qId. $id . "|";
    }
logToFile("questionId array is:".$qId);
    echo $qId;



