<!DOCTYPE html>
<html>
<head>
    <style type="text/css">

        table.gridtable {
            font-family: verdana, arial, sans-serif;
            font-size: 11px;
            color: #333333;
            border-width: 1px;
            border-color: #666666;
            border-collapse: collapse;
        }

        table.gridtable th {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #666666;
            background-color: #dedede;
            font-size: 15px;
            font-weight: bold;
        }

        table.gridtable td {
            border-width: 1px;
            padding: 8px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
        }

        #tabs_wrapper {
            width: 422px;
        }
        #tabs_container {
            border-bottom: 1px solid #ccc;
        }
        #tabs {
            list-style: none;
            padding: 5px 0 4px 0;
            margin: 0 0 0 10px;
            font: 0.75em arial;
        }
        #tabs li {
            display: inline;
        }
        #tabs li a {
            border: 1px solid #ccc;
            padding: 4px 6px;
            text-decoration: none;
            background-color: #eeeeee;
            border-bottom: none;
            outline: none;
            border-radius: 5px 5px 0 0;
            -moz-border-radius: 5px 5px 0 0;
            -webkit-border-top-left-radius: 5px;
            -webkit-border-top-right-radius: 5px;
        }
        #tabs li a:hover {
            background-color: #dddddd;
            padding: 4px 6px;
        }
        #tabs li.active a {
            border-bottom: 1px solid #fff;
            background-color: #fff;
            padding: 4px 6px 5px 6px;
            border-bottom: none;
        }
        #tabs li.active a:hover {
            background-color: #eeeeee;
            padding: 4px 6px 5px 6px;
            border-bottom: none;
        }

        #tabs_content_container {
            border: 1px solid #ccc;
            border-top: none;
            padding: 10px;
            width: 400px;
        }
        .tab_content {
            display: none;
        }
    </style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
    <script src="../javascript/question.js"></script>
    <?php

    include_once '../include/common.php';

    $questionText = $_POST['QuestionText'];
    $questionType = $_POST['QuestionType'];
    $timeLimit = $_POST['TimeLimit'];
    $choices = $_POST['Choices'];
    $assCriteria = $_POST['AssessmentCriteria'];

    $questionTextArray = explode("~",$questionText);
    $questionTypeArray = explode("~",$questionType);
    $timeLimitArray = explode("~",$timeLimit);
    $choicesArray = explode("~",$choices);
    $assCriteriaArray = explode("~",$assCriteria);


    ?>
    <script>
        $(document).ready(function(){
            //  When user clicks on tab, this code will be executed
            $("#tabs li").click(function() {
                //  First remove class "active" from currently active tab
                $("#tabs li").removeClass('active');

                //  Now add class "active" to the selected/clicked tab
                $(this).addClass("active");

                //  Hide all tab content
                $(".tab_content").hide();

                //  Here we get the href value of the selected tab
                var selected_tab = $(this).find("a").attr("href");

                //  Show the selected tab content
                $(selected_tab).fadeIn();

                //  At the end, we add return false so that the click on the link is not executed
                return false;
            });
        });


    </script>
    <script language="javascript">

        var jobId = encodeURIComponent("<?php echo $_REQUEST['JOB_ID']; ?>");
        var jobTitle = encodeURIComponent("<?php echo $_REQUEST['JOB_TITLE']; ?>");

    </script>
</head>
<body style="font-size:62.5%;">


<div id="tabs_container">
    <ul id="tabs">
        <li><a href="#ObjectiveTab">Objective</a></li>
        <li><a href="#Written">Written</a></li>
        <li><a href="#VideoTab">Video</a></li>
    </ul>
</div>
<div id="tabs_content_container">
    <div id="ObjectiveTab" class="tab_content" style="display: block;">
        <input type="button" name="addQuestion" value="Add Question" onClick="javascript:addTable('objective')"/>
        <input type="button" name="save" value="Save" onClick="javascript:submitForm('objective')"/>
        <input type="button" name="addStage" value="Next Stage"/>
        <table border="1" class="gridtable" columns="5" size="100%" id="questionTable1">
            <tbody id="ObjectiveTabBody">
            <tr><th colspan="5">Objective Questions</th></tr>
            <?php
            $objectiveCounter = 0;
            for($i=0;$i<count($questionTextArray);$i++) {
                $qText = $questionTextArray[$i];
                $qType = $questionTypeArray[$i];
                $qTimeLimit = $timeLimitArray[$i];
                logToFile("TimeLimit:".$qTimeLimit);
                logToFile("Question Type:".$qType);

                $choice = $choicesArray[$i];
                $jsonString = str_replace("\\","",$choice);
                $choicesObjectsArray = json_decode($jsonString,true);
                //        $qChoices = $choicesArray[$i];
                if(!isset($qType)) {
                    continue;
                }else if($qType =="O") {
                    $questionTxtName = "QuestionText".$objectiveCounter;
                    $timeLimitTxtName = "TimeLimit".$objectiveCounter;
                    $answerChoiceTxt = "AnswerChoice".$objectiveCounter;
                    $tableName = "InnerTable".$objectiveCounter;
                    $choice1Id = "Choice1".$objectiveCounter;
                    $choice2Id = "Choice2".$objectiveCounter;
                    $choice3Id = "Choice3".$objectiveCounter;
                    $choice4Id = "Choice4".$objectiveCounter;
                    $questionBankCheck = "QuestionBank".$objectiveCounter;

                    $objectiveCounter++;

                    $ch1 = $choicesObjectsArray[2];
                    $ch2 = $choicesObjectsArray[5];
                    $ch3 = $choicesObjectsArray[8];
                    $ch4 = $choicesObjectsArray[11];

                    if($ch1 == "1") {
                        $selected1 = "checked=checked";
                    } else {
                        $selected1 = "";
                    }
                    if($ch2 == "1") {
                        $selected2 = "checked=checked";
                    } else {
                        $selected2 = "";
                    }
                    if($ch3 == "1") {
                        $selected3 = "checked=checked";
                    } else {
                        $selected3 = "";
                    }
                    if($ch4 == "1") {
                        $selected4 = "checked=checked";
                    } else {
                        $selected4 = "";
                    }

                    ?>
                <tr><td colspan="5">
                    <table style="border:1px solid black" class="gridtable" columns="5" id="<?php echo $tableName ?>" saved="new">
                        <tr>
                            <td>
                                Question Text
                            </td>
                            <td colspan="4">
                                <textarea rows="2" cols="100" name="QuestionText" id="<?php echo $questionTxtName ?>"><?php echo $qText ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Time Limit (minutes)
                            </td>
                            <td colspan="2">
                                <input type="text" id="<?php echo $timeLimitTxtName ?>" value="<?php echo $qTimeLimit ?>" size="30">
                            </td>
                            <td>
                                Add to Question Bank
                            </td>
                            <td>
                                <input type="checkbox" id="<?php echo $questionBankCheck ?>" >
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Answer Choices
                            </td>
                            <td>
                                <input type="checkbox" name="<?php echo $answerChoiceTxt ?>" <?php echo $selected1 ?>> &nbsp;
                                <input type="text" id="<?php echo $choice1Id ?>" value="<?php echo $choicesObjectsArray[1]?>" />
                            </td>
                            <td>
                                <input type="checkbox" name="<?php echo $answerChoiceTxt ?>" <?php echo $selected2 ?>> &nbsp;
                                <input type="text" id="<?php echo $choice2Id ?>" value="<?php echo $choicesObjectsArray[4]?>"/>
                            </td>
                            <td>
                                <input type="checkbox" name="<?php echo $answerChoiceTxt ?>" <?php echo $selected3 ?>>&nbsp;
                                <input type="text" id="<?php echo $choice3Id ?>" value="<?php echo $choicesObjectsArray[7]?>"/>
                            </td>
                            <td>
                                <input type="checkbox" name="<?php echo $answerChoiceTxt ?>" <?php echo $selected4 ?>> &nbsp;
                                <input type="text" id="<?php echo $choice4Id ?>" value="<?php echo $choicesObjectsArray[10]?>"/>
                            </td>
                        </tr>

                    </table>
                </td>
                </tr>

                    <?php
                } else continue;
            }
            ?>
            </tbody>
        </table>
    </div>
    <div id="VideoTab" class="tab_content" style="display: block;">
        <input type="button" name="addVideoQuestion" value="Add Question" onClick="javascript:addTable('video')"/>
        <input type="button" name="saveVideo" value="Save" onClick="javascript:submitForm('video')"/>
        <input type="button" name="addStage" value="Next Stage"/>
        <table border="1" class="gridtable" columns="5" size="100%" id="VideoQuestionTable1">
            <tbody id="VideoTabBody">
            <tr><th colspan="5">Video Questions</th></tr>
            <?php
            $videoCounter = 0;
            for($i=0;$i<count($questionTextArray);$i++) {
                $vqText = $questionTextArray[$i];
                $vqType = $questionTypeArray[$i];
                $vqTimeLimit = $timeLimitArray[$i];
                $vqAssCriteria = $assCriteriaArray[$i];
                logToFile("TimeLimit:".$vqTimeLimit);
                logToFile("Question Type:".$vqType);

                if(!isset($vqType)) {
                    continue;
                }else if($vqType =="V") {
                    $vQuestionTxtName = "VideoQuestionText".$videoCounter;
                    $vTimeLimitTxtName = "VideoTimeLimit".$videoCounter;
                    $vAnswerChoiceTxt = "VideoAnswerChoice".$videoCounter;
                    $vTableName = "VideoInnerTable".$videoCounter;
                    $vQuestionBankCheck = "VideoQuestionBank".$videoCounter;
                    $vAssCriteriaTxtName = "VideoAssessmentCriteria".$videoCounter;

                    $videoCounter++;
                    ?>
                <tr><td colspan="5">
                    <table style="border:1px solid black" class="gridtable" columns="5" id="<?php echo $vTableName ?>" saved="new">
                        <tr>
                            <td>
                                Question Text
                            </td>
                            <td colspan="4">
                                <textarea rows="2" cols="100" name="QuestionText" id="<?php echo $vQuestionTxtName ?>"><?php echo $vqText ?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Time Limit (minutes)
                            </td>
                            <td colspan="2">
                                <input type="text" id="<?php echo $vTimeLimitTxtName ?>" value="<?php echo $vqTimeLimit ?>" size="30">
                            </td>
                            <td>
                                Add to Question Bank
                            </td>
                            <td>
                                <input type="checkbox" id="<?php echo $vQuestionBankCheck ?>" >
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Assessment Criteria
                            </td>
                            <td colspan="4">
                                <textarea rows="2" cols="100" name="VideoAssessmentCriteria" id="<?php echo $vAssCriteriaTxtName ?>"><?php echo $vqAssCriteria ?></textarea>
                            </td>
                        </tr>

                    </table>
                </td>
                </tr>

                    <?php
                } else continue;
            }
            ?>
            </tbody>
        </table>
    </div>




</div>
</body>
</html>