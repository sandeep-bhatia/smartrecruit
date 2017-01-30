<?php
include_once '../dao/QuestionBankDAO.php';
include_once '../objects/ObjectiveQuestion.php';
include_once '../objects/Choice.php';
include_once '../include/common.php';
session_start();
?>
<html>
<head>
    <title>Question Bank</title>

    <script type="text/javascript" language="JavaScript">

        function add() {
            var count=0;
            var questionText="";
            var questionType="";
            var timeLimit = "";
            var choices = "";
            var inputs = document.getElementsByTagName("input"); //or document.forms[0].elements;
            var cbs = []; //will contain all checkboxes
            var checked = ""; //will contain all checked checkboxes
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type == "checkbox") {

                    cbs.push(inputs[i]);
                    if (inputs[i].checked) {

                        var checkId = inputs[i].getAttribute("id");
                        var questionTxt = "qtext" + checkId;
                        var questionTp = "qtype" + checkId;
                        var timeL = "TimeLimit" + checkId;
                        var ch = "Choices" + checkId;

                        questionText += document.getElementById(questionTxt).innerHTML + "~";
                        questionType += document.getElementById(questionTp).innerHTML + "~";
//                        alert("Question Type is:"+questionType);
                        timeLimit += document.getElementById(timeL).value + "~";
//                        alert("Time Limit is:"+timeLimit);
                        choices += document.getElementById(ch).value + "~";
                        alert("Choices: " + choices);

                    } else {
                        continue;
                    }

                }
            }

            var hiddenTxt = document.createElement("input");
            hiddenTxt.setAttribute("type","hidden");
            hiddenTxt.setAttribute("value",questionText);
            hiddenTxt.setAttribute("name","QuestionText");
            document.forms[0].appendChild(hiddenTxt);

            var hiddenType = document.createElement("input");
            hiddenType.setAttribute("type","hidden");
            hiddenType.setAttribute("value",questionType);
            hiddenType.setAttribute("name","QuestionType");
            document.forms[0].appendChild(hiddenType);

            var hiddenTimeL = document.createElement("input");
            hiddenTimeL.setAttribute("type","hidden");
            hiddenTimeL.setAttribute("value",timeLimit);
            hiddenTimeL.setAttribute("name","TimeLimit");
            document.forms[0].appendChild(hiddenTimeL);

            var hiddenChoices = document.createElement("input");
            hiddenChoices.setAttribute("type","hidden");
            hiddenChoices.setAttribute("value",choices);
            hiddenChoices.setAttribute("name","Choices");
            document.forms[0].appendChild(hiddenChoices);

            document.forms[0].submit();
        }

    </script>
</head>
<body>
<?php
$recruiterId = $_SESSION['recruiterID'];
logToFile("Recruiter Id in questionbank is: ".$recruiterId);
$questionBank = QuestionBankDAO::findAllQuestionsInBank($recruiterId);
?>
<form action="questiontabs.php" method="post">
    <input type="hidden" name="JOB_ID" value="<?php echo $_REQUEST['JOB_ID']?>" />
    <input type="hidden" name="JOB_TITLE" value="<?php echo $_REQUEST['JOB_TITLE']?>" />
<table border="1" cols="3">
    <tr>
        <th></th>
        <th>Question Text</th>
        <th>Question Type</th>
    </tr>
    <tr>
        <td colspan="3"><input type=submit value="SUBMIT" onclick="javascript:add()" /></td>
    </tr>
    <?php
        $qBankCounter = 1;
        $choicesJSONArray = array();
        foreach($questionBank as $question) {
            $choicesJSONArray = array();
            $divId = "div".$qBankCounter;
            $checkId = "check".$qBankCounter;
            $qTextId = "qtext".$qBankCounter;
            $qTypeId = "qtype".$qBankCounter;
            $selectName = "select".$qBankCounter;
            $timeLimitName = "TimeLimit".$qBankCounter;
            $choicesId = "Choices".$qBankCounter;

            $choicesArray = $question->getChoices();
            foreach($choicesArray as $choice) {
                array_push($choicesJSONArray,$choice->getId(),$choice->getDescription(),$choice->getCorrectChoice());
            }

            $jsonString = json_encode($choicesJSONArray);
            logToFile("JsonString is:".$jsonString);
    ?>
    <tr>
            <td id="<?php echo $checkId?>"><input type="checkbox" name="<?php echo $selectName?>" id="<?php echo $qBankCounter?>" /></td>
            <td id="<?php echo $qTextId?>"><?php echo $question->getQuestionText()?></td>
            <td id="<?php echo $qTypeId?>"><?php echo $question->getQuestionType()?></td>

        <input type='hidden' id="<?php echo $timeLimitName?>" value="<?php echo $question->getTimeLimit() ?>" />
        <input type='hidden' id="<?php echo $choicesId ?>" value='<?php echo $jsonString?>' />
    </tr>

<?php
         $qBankCounter++;
}
?>


</table>
</form>
</body>
</html>