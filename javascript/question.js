/**
 * Created with JetBrains PhpStorm.
 * User: gauravkhullar
 * Date: 7/25/12
 * Time: 1:07 AM
 * To change this template use File | Settings | File Templates.
 */

var objectiveCounter =0;
var videoCounter =0;
var noOfObjectiveInputRows1,noOfObjectiveInputRows11,noOfVideoInputRows1,noOfVideoInputRows11,questionIds;

var txtAreaName,timeLimitName,choice1TxtName,answerTxtName,choice2TxtName,choice3TxtName,choice4TxtName;
var questionBankCheckName;
var xmlHttp = false;
try {
    xmlHttp = new ActiveXObject("Msxml12.XMLHTTP");
} catch (e) {
    try {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (e2) {
        xmlHttp = false;
    }
}
if (!xmlHttp && typeof XMLHttpRequest != 'undefined') {
    xmlHttp = new XMLHttpRequest();
}

function initiate() {
    txtAreaName = "qText";
    timeLimitName = "tLimit";
    choice1TxtName = "Choice1-";
    answerTxtName = "AnswerChoice-";
    choice2TxtName = "Choice2-";
    choice3TxtName = "Choice3-";
    choice4TxtName = "Choice4-";
    questionBankCheckName = "QuestionBank-";
}
function createTable(aid,aclass,asize,cols){
    var tableObj = document.createElement("table");
    tableObj.setAttribute("class",aclass);
    tableObj.setAttribute("id",aid);
    tableObj.setAttribute("size",asize);
    tableObj.setAttribute("cols","5");

    return tableObj;
}

function createTextArea(aid,aname,arows,acols,avalue) {
    var qtxtArea = document.createElement("textarea");
    qtxtArea.setAttribute("name",aname);
    qtxtArea.setAttribute("id",aid);
    qtxtArea.setAttribute("rows",arows);
    qtxtArea.setAttribute("cols",acols);
    qtxtArea.value = avalue;

    return qtxtArea;
}

function createTextInput(aid,aname,avalue,asize) {
    var textInput = document.createElement("input");
    textInput.setAttribute("type","text");
    textInput.setAttribute("id",aid);
    textInput.setAttribute("name",aname);
    textInput.setAttribute("size",asize);
    textInput.setAttribute("value",avalue);
    return textInput;
}

function createCheckBoxInput(aid,aname,avalue) {
    var checkBox = document.createElement("input");
    checkBox.setAttribute("type","checkbox");
    checkBox.setAttribute("id",aid);
    checkBox.setAttribute("name",aname);
    checkBox.setAttribute("value",avalue);

    return checkBox;
}

function createSmartTable(aQuestionType,aCounter,aId,aDiv,aInnerTableId,aTxtAreaName,aTimeLimitName,$aQuestionBankCheckName) {
    if(aCounter ==0) {
        var tableObj1 = createTable(aId,"gridtable","100","5");
        var table1Row = tableObj1.insertRow(-1);
        var table1Cell = table1Row.insertCell(0);
        table1Cell.setAttribute("colSpan","5");

        aDiv.appendChild(tableObj1);

    } else {
        tableObj1 = document.getElementById(aId);
        var table1Row = tableObj1.insertRow(-1);
        var table1Cell = table1Row.insertCell(0);
        table1Cell.setAttribute("colSpan","5");
    }
    var tableId = aInnerTableId + aCounter;
    var tableObj2 = createTable(tableId,"gridtable","100%","5")
    tableObj2.setAttribute("saved","new");

    var textRow = tableObj2.insertRow(0);
    var textCell1 = textRow.insertCell(0);
    textCell1.appendChild(document.createTextNode("Question Text"));
    var textCell2 = textRow.insertCell(1);
    textCell2.setAttribute("colSpan","4");
    aTxtAreaName += aCounter;
    var qtxtArea = createTextArea(aTxtAreaName,aTxtAreaName,"2","100","");
    textCell2.appendChild(qtxtArea);


    var timeLimitRow = tableObj2.insertRow(1);
    var timeLimitCell1 = timeLimitRow.insertCell(0);
    timeLimitCell1.appendChild(document.createTextNode("Time Limit (minutes)"));
    var timeLimitCell2 = timeLimitRow.insertCell(1);
    timeLimitCell2.setAttribute("colSpan","2");


    aTimeLimitName += aCounter;
    var timeLimitTxt = createTextInput(aTimeLimitName,aTimeLimitName,"","12");
    timeLimitCell2.appendChild(timeLimitTxt);

    var timeLimitCell3 = timeLimitRow.insertCell(2);
    timeLimitCell3.appendChild(document.createTextNode("Add to Question Bank"));

    var timeLimitCell4 = timeLimitRow.insertCell(3);
    $aQuestionBankCheckName += aCounter;
    var questionBankTxt = createCheckBoxInput($aQuestionBankCheckName,$aQuestionBankCheckName,"");
    timeLimitCell4.appendChild(questionBankTxt);


    if(aQuestionType == "objective") {
        var answersRow = tableObj2.insertRow(2);
        var cellTag1 = document.createElement("td");
        var tdText = document.createTextNode("Answers");

        cellTag1.appendChild(tdText);
        answersRow.appendChild(cellTag1);

        var cellTag2 = document.createElement("td");
        choice1TxtName += aCounter;
        var textTag2 = createTextInput(choice1TxtName,choice1TxtName,"Option1","12");

        answerTxtName += aCounter;
        var radioTag2 = createCheckBoxInput(answerTxtName,answerTxtName,"1");

        cellTag2.appendChild(textTag2);
        cellTag2.appendChild(radioTag2);
        answersRow.appendChild(cellTag2);

        var cellTag3 = document.createElement("td");
        choice2TxtName += aCounter;
        var textTag3 = createTextInput(choice2TxtName,choice2TxtName,"Option2","12");


        var radioTag3 = createCheckBoxInput(answerTxtName,answerTxtName,"2");
        cellTag3.appendChild(textTag3);
        cellTag3.appendChild(radioTag3);
        answersRow.appendChild(cellTag3);

        var cellTag4 = document.createElement("td");
        choice3TxtName += aCounter;
        var textTag4 = createTextInput(choice3TxtName,choice3TxtName,"Option3","12");

        var radioTag4 = createCheckBoxInput(answerTxtName,answerTxtName,"3");
        cellTag4.appendChild(textTag4);
        cellTag4.appendChild(radioTag4);
        answersRow.appendChild(cellTag4);

        var cellTag5 = document.createElement("td");
        choice4TxtName += aCounter;
        var textTag5 = createTextInput(choice4TxtName,choice4TxtName,"Option4","12");

        var radioTag5 = createCheckBoxInput(answerTxtName,answerTxtName,"4");
        cellTag5.appendChild(textTag5);
        cellTag5.appendChild(radioTag5);
        answersRow.appendChild(cellTag5);

        table1Cell.appendChild(tableObj2);
    } else if(aQuestionType == "video") {


        var assRow = tableObj2.insertRow(2);
        var cellTag1 = document.createElement("td");
        var assCText = document.createTextNode("Assessment Criteria");

        cellTag1.appendChild(assCText);
        assRow.appendChild(cellTag1);

        var cellTag2 = document.createElement("td");
        var vAssCriteriaTxtName ="VideoAssessmentCriteria-" +  aCounter;

        alert("TextArea: "+vAssCriteriaTxtName)
        var vAssCriArea = createTextArea(vAssCriteriaTxtName,vAssCriteriaTxtName,"2","100","");
        cellTag2.appendChild(vAssCriArea);
        assRow.appendChild(cellTag2);

        table1Cell.appendChild(tableObj2);
    }

}
function addTable(questionType) {
    var tableObj1,tableObj2;
    var objectiveDiv,videoDiv;
    initiate();
    if(questionType == "objective") {
        objectiveDiv = document.getElementById("ObjectiveTab");

        createSmartTable(questionType,objectiveCounter,"questionTable11",objectiveDiv,"InnerTable-",txtAreaName,
                            timeLimitName,questionBankCheckName);

        objectiveCounter++;

    } else if(questionType == "video") {
        videoDiv = document.getElementById("VideoTab");

        createSmartTable(questionType,videoCounter,"VideoQuestionTable11",videoDiv,"VideoInnerTable-","VideoQuestionText-",
                    "VideoTimeLimit-","VideoQuestionBank-");

        videoCounter++;
    }


}
function submitQuestions(aQuestionType,aTableId,aNoOfRows,aQText,aTimeL,aQBank,aAnswerCh,aCh1,aCh2,aCh3,aCh4,aAssCriteria) {
    var question = new Array();

    for(var i=0;i<aNoOfRows;i++) {
        var isSaved = "";
        var tempSaved ="";
        var checked="";
        var choice1="",choice2="",choice3="",choice4="",assessmentCriteria="";
        var qText = aQText+i;
        var timeL = aTimeL+i;
        var qBankCheck = aQBank+i;
        var answerCh = aAnswerCh+i;
        var ch1 = aCh1+i;
        var ch2 = aCh2+i;
        var ch3 = aCh3+i;
        var ch4 = aCh4+i;
        var tableId = aTableId+i;
        var addToQuestionBank;
        var assC = aAssCriteria + i;

        tempSaved = document.getElementById(tableId).getAttribute("saved");

        if(tempSaved !=null || tempSaved !="") {
            isSaved = tempSaved;
        }


        if(document.getElementById(qText).value == null || document.getElementById(qText).value.trim() == "") {
            alert("Please enter Question Text");
            return;
        } else {
            questionTextValue = document.getElementById(qText).value.trim();
        }

        questionId = document.getElementById(qText).getAttribute("questionId");

        var tempTimeL = document.getElementById(timeL).value;

        if(isNaN(tempTimeL)) {
            alert("Time Limit should be a number");
            return;
        }

        if(tempTimeL == null || tempTimeL.trim() == "") {
            alert("No Time Limit Entered. Default time limit of 1 minute will be taken");
            document.getElementById(timeL).value = 1;
        } else {
            timeLimit = document.getElementById(timeL).value.trim();
        }
        var qB = document.getElementById(qBankCheck);
        if(qB.checked) {
            addToQuestionBank = "1";
        } else {
            addToQuestionBank = "0";
        }
        if(aQuestionType == "objective") {
            var inputs = document.getElementsByName(answerCh);
            var checkedFlag = false;
            for (var j = 0; j < inputs.length; j++) {
                if (inputs[j].checked) {
                    checked += "1" + "|";
                    checkedFlag = true;

                } else {
                    checked += "0" + "|"
                }
            }
            if(checkedFlag == false) {
                alert("Please select at least one correct answer choice");
                return;
            }

            choice1 = document.getElementById(ch1).value.trim();
            if(choice1 == null || choice1 == "") {
                alert("Please enter the answer choice");
                return;
            }
            choice2 = document.getElementById(ch2).value.trim();
            if(choice2 == null || choice2 == "") {
                alert("Please enter the answer choice");
                return;
            }
            choice3 = document.getElementById(ch3).value.trim();
            if(choice3 == null || choice3 == "") {
                alert("Please enter the answer choice");
                return;
            }
            choice4 = document.getElementById(ch4).value.trim();
            if(choice4 == null || choice4 == "") {
                alert("Please enter the answer choice");
                return;
            }
        } else if(aQuestionType == "video") {
//            alert("Assessment criteria object inside SubmitQuestions is:"+assC);
            assessmentCriteria = document.getElementById(assC).value.trim();
        }


        if(isSaved == "new" || isSaved=="false") {
            question.push({"questionId":questionId,"isSaved":isSaved,"questionType":aQuestionType,"questionText":questionTextValue,"timeLimit":timeLimit,"addToQuestionBank":addToQuestionBank,"choices":checked,"choice1":choice1,"choice2":choice2,"choice3":choice3,"choice4":choice4,"assessmentCriteria":assessmentCriteria});
        }



    }

    return question;
}



function submitForm(aQuestionType) {
    alert("Question Type:"+aQuestionType);
    var table1,oldQuestions,newQuestions;
    if(aQuestionType == "objective"){
        table1 = document.getElementById("questionTable1");
    } else if(aQuestionType == "video"){
        table1 = document.getElementById("VideoQuestionTable1");
    }

    var rows1 = table1.rows.length;


    if(aQuestionType == "objective"){
        noOfObjectiveInputRows1 = (rows1 -1);
        oldQuestions = submitQuestions(aQuestionType,"InnerTable",noOfObjectiveInputRows1,"QuestionText","TimeLimit","QuestionBank","AnswerChoice","Choice1","Choice2","Choice3","Choice4");
    } else if(aQuestionType == "video"){
        noOfVideoInputRows1 = (rows1 -1);
        oldQuestions = submitQuestions(aQuestionType,"VideoInnerTable",noOfVideoInputRows1,"VideoQuestionText","VideoTimeLimit",
            "VideoQuestionBank","","","","","","VideoAssessmentCriteria");
    }
    newQuestions = [];
    if(objectiveCounter != 0) {
        var table11 = document.getElementById("questionTable11");
        var rows11 = table11.rows.length;
        noOfObjectiveInputRows11 = (rows11);
        initiate();

        newQuestions = submitQuestions(aQuestionType,"InnerTable-",noOfObjectiveInputRows11,txtAreaName,timeLimitName,
            questionBankCheckName,answerTxtName,choice1TxtName,choice2TxtName,choice3TxtName,choice4TxtName);
        if(newQuestions == null || newQuestions =="") {
            return;
        }

    }
    if(videoCounter != 0) {
        var table11 = document.getElementById("VideoQuestionTable11");
        var rows11 = table11.rows.length;
        noOfVideoInputRows11 = (rows11);

        newQuestions = submitQuestions(aQuestionType,"VideoInnerTable-",noOfVideoInputRows11,"VideoQuestionText-","VideoTimeLimit-",
            "VideoQuestionBank-","","","","","","VideoAssessmentCriteria-");
        if(newQuestions == null || newQuestions =="") {
            return;
        }

    }
    var jsonQuestions = oldQuestions.concat(newQuestions);
    var toServer = JSON.stringify(jsonQuestions,null,2);
//    alert(toServer);

    var jobId = encodeURIComponent("<?php echo $_REQUEST['JOB_ID']; ?>");
    var jobTitle = encodeURIComponent("<?php echo $_REQUEST['JOB_TITLE']; ?>");
    var url = "../viewclasses/CreateInterviewView.php"
    var parameters = "jobId=" + jobId + "&jobTitle=" + jobTitle + "&QIDS="+questionIds + "&QuestionObjects="+toServer;
    hiddenQuestions = document.createElement("input");
    hiddenQuestions.setAttribute("type","hidden");
    hiddenQuestions.setAttribute("id","qObjs");
    hiddenQuestions.value = toServer;

    xmlHttp.open("POST",url,true);
    xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    //setup a function for the server to run when it's done
    if(aQuestionType == "objective") {
        xmlHttp.onreadystatechange = restoreObjectiveForm;
    } else if(aQuestionType == "video") {
        xmlHttp.onreadystatechange = restoreVideoForm;
    }


    xmlHttp.send(parameters);
    //create and hidden object

}

function restoreFormElement(id,questionId,saved,color) {

    document.getElementById(id).setAttribute("saved",saved);
    aQId = document.getElementById(id).getAttribute("questionId");
    if(aQId ==null || aQId=="") {
        document.getElementById(id).setAttribute("questionId",questionId);
    }

    document.getElementById(id).style.backgroundColor = color;
    document.getElementById(id).onkeyup = function() {editQuestion(this,questionId,saved)};

}
function restoreVideoForm() {
    var questionId,saved;
    questionId ="";
    var color = "#66cdaa";
    alert("Inside restoreVideoForm");
    if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        alert("Questions are saved");
        questionIds = xmlHttp.responseText;
        alert("QuestionIDs:" + questionIds);
        saved = "true";
        var questionIdsArray = questionIds.split("|");
        var noOfInsertedRows = questionIdsArray.length-1;
        for(var j=0;j<noOfVideoInputRows1;j++) {
            questionId = questionIdsArray[j];

            document.getElementById("VideoInnerTable"+j).setAttribute("saved",saved);
            restoreFormElement("VideoQuestionText"+j,questionId,saved,color);
            restoreFormElement("VideoTimeLimit"+j,questionId,saved,color);
            restoreFormElement("VideoAssessmentCriteria"+j,questionId,saved,color);
        }

        for(var j=0;j<noOfVideoInputRows11;j++) {
            alert("Inserted rows:"+noOfInsertedRows + " noOfVideoInputRows1:" + noOfVideoInputRows1 + " noOfVideoInputRows11:" + noOfVideoInputRows11);
            if(noOfInsertedRows == (noOfVideoInputRows1 + noOfVideoInputRows11)) {
                questionId = questionIdsArray[noOfVideoInputRows1+j];
            }
            else {
                questionId = questionIdsArray[j];
            }

            alert("Question Id new in table:" + questionId);
            document.getElementById("VideoInnerTable-"+j).setAttribute("saved",saved);
            restoreFormElement("VideoQuestionText-"+j,questionId,saved,color);
            restoreFormElement("VideoTimeLimit-"+j,questionId,saved,color);
            restoreFormElement("VideoAssessmentCriteria-"+j,questionId,saved,color);


        }
    }

}
function restoreObjectiveForm() {
    var questionId,saved;
    questionId ="";
    var color = "#66cdaa";
    if(xmlHttp.readyState == 4 && xmlHttp.status == 200) {
        alert("Questions are saved");
        questionIds = xmlHttp.responseText;
//            alert("QuestionIDs:" + questionIds);
        saved = "true";
        questionIdsArray = questionIds.split("|");
        noOfInsertedRows = questionIdsArray.length-1;
        for(var j=0;j<noOfObjectiveInputRows1;j++) {
            questionId = questionIdsArray[j];

            document.getElementById("InnerTable"+j).setAttribute("saved",saved);
            restoreFormElement("QuestionText"+j,questionId,saved,color);
            restoreFormElement("TimeLimit"+j,questionId,saved,color);
            restoreFormElement("Choice1"+j,questionId,saved,color);
            restoreFormElement("Choice2"+j,questionId,saved,color);
            restoreFormElement("Choice3"+j,questionId,saved,color);
            restoreFormElement("Choice4"+j,questionId,saved,color);

            var checks = document.getElementsByName("AnswerChoice"+j);
            for(var k=0;k<checks.length;k++) {
//                    checks[k].setAttribute("disabled","disabled");
                checks[k].setAttribute("saved",saved);
                checks[k].setAttribute("questionId",questionId);
                checks[k].style.backgroundColor = color;
                checks[k].onclick = function() {editQuestion(this,questionId,saved)};
            }
        }
        initiate();

        for(var j=0;j<noOfObjectiveInputRows11;j++) {
//                alert("Inserted rows:"+noOfInsertedRows + " noOfInputRows1:" + noOfInputRows1 + " noOfInputRows11:" + noOfInputRows11);
            if(noOfInsertedRows == (noOfObjectiveInputRows1 + noOfObjectiveInputRows11)) {
                questionId = questionIdsArray[noOfObjectiveInputRows1+j];
            }
            else {
                questionId = questionIdsArray[j];
            }

//                alert("Question Id new in table:" + questionId);
            document.getElementById("InnerTable-"+j).setAttribute("saved",saved);
            restoreFormElement(txtAreaName+j,questionId,saved,color);
            restoreFormElement(timeLimitName+j,questionId,saved,color);
            restoreFormElement(choice1TxtName+j,questionId,saved,color);
            restoreFormElement(choice2TxtName+j,questionId,saved,color);
            restoreFormElement(choice3TxtName+j,questionId,saved,color);
            restoreFormElement(choice4TxtName+j,questionId,saved,color);

            var checks = document.getElementsByName(answerTxtName+j);
            for(var k=0;k<checks.length;k++) {
//                    checks[k].setAttribute("disabled","disabled");
                checks[k].setAttribute("saved",saved);
                checks[k].setAttribute("questionId",questionId);
                checks[k].style.backgroundColor = color;
                checks[k].onclick = function() {editQuestion(this,questionId,saved)};
            }
        }
    }

}

function editQuestion(obj,questionId,saved) {


    editedColor = "#FFBAD2";
    var parent = obj.parentNode;
    while(true) {
        if(parent == null) {
            return;
        }
        if(parent.nodeName.toLowerCase() == "table") {
            break;
        }
        parent = parent.parentNode;
    }

    if(saved == "true") {
        parent.setAttribute("saved","false");
        obj.style.backgroundColor = editedColor;
    }

}

