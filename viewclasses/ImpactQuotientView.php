<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gauravkhullar
 * Date: 7/27/12
 * Time: 2:31 PM
 * To change this template use File | Settings | File Templates.
 */
include_once('LinkedInFetch.php');
include_once('../include/common.php');
include_once('../include/weights.php');

class ImpactQuotientView
{
    private $industry;
    private $specialities;
    private $numOfConnections;
    private $presentTitle;
    private $linkedInFollowCompany;
    private $linkedInPresentCompanyNames;
    private $linkedInPresentCompanyIds;
    private $linkedInPresentCompanyStartDates;
    private $linkedInPresentCompanyEndDates;
    private $linkedInPreviousCompanyNames;
    private $linkedInPreviousCompanyIds;
    private $linkedInPreviousCompanyStartDates;
    private $linkedInPreviousCompanyEndDates;
    private $linkedInJobTitles;

    private $linkedInConnectionsData;
    private $linkedInConnectionsIndustryTitlesExperienceArray;


    private $linkedInCertifications ;
    private $linkedInPatents;
    private $linkedInEducations;
    private $meaningfulNetworking;

    private $CXO;
    private $SVP;
    private $VP;
    private $DIRECTOR;

    function __construct() {
        $this->CXO = '\bCEO\b|\bCFO\b|\bCXO\b|\bCTO\b|\bCOO\b|\bCMO\b|\bFounder\b|\bCo-Founder\b|\bCoFounder\b';
        $this->SVP = '\bSVP\b|\bEVP\b|\bSenior Vice President\b|\bExecutive Vice President\b';
        $this->VP = '\bVP\b|\bVice President\b|\bVice-President\b';
        $this->DIRECTOR = "Director";
        $this->linkedInFollowCompany = array();
        $this->linkedInPresentCompanyNames = array();
        $this->linkedInPreviousCompanyNames = array();
        $this->linkedInCertifications = array();
        $this->linkedInPatents = array();
        $this->linkedInEducations = array();
        $this->linkedInJobTitles = array();
        $this->linkedInConnectionsIndustryTitlesExperienceArray = array();


        $this->getLinkedInProfileInformation();
        $this->getLinkedInConnectionsJSON();
        $this->getNumberOfConnectedIndustryTitlesExperience();
    }

    private function getLinkedInProfileInformation(){

        $linkedInFetch = new LinkedInFetch();
        $jsonLoggedInUser = $linkedInFetch->fetch($this->createLinkedInUserFetchUrl());
       logToFile($jsonLoggedInUser);


        if(!isset($jsonLoggedInUser) || $jsonLoggedInUser == ''){
            logToFile('User could not be retrieved from LinkedIn');
            throw new Exception('User could not be retrieved from LinkedIn');
        } else {
            $jsonIterator = json_decode($jsonLoggedInUser, TRUE);
//            echo $jsonIterator;
            $firstName = $jsonIterator['firstName'];
            $lastName = $jsonIterator['lastName'];
            $linkedInID = $jsonIterator['id'];
            $this->setIndustry($jsonIterator['industry']);
            $this->setNumOfConnections($jsonIterator['numConnections']);
            $this->setSpecialities($jsonIterator['specialties']);

            logToFile("FirstName: " .$firstName);
            logToFile("LastName: " .$lastName);
            logToFile("LinkedInID: ".$linkedInID);


            $positions = $jsonIterator['positions'];

            $linkedInPresentCompanyNames = array();
            $linkedInPresentCompanyIds = array();
            $linkedInPresentCompanyStartDates = array();
            $linkedInPresentCompanyEndDates = array();

            $linkedInPreviousCompanyNames = array();
            $linkedInPreviousCompanyIds = array();
            $linkedInPreviousCompanyStartDates = array();
            $linkedInPreviousCompanyEndDates = array();

            foreach ($positions as $pos) {

                foreach ($pos as $val) {
                    if($val['isCurrent'] == true || $val['isCurrent'] == 1 ) {
                        array_push($linkedInPresentCompanyNames,$val['company']['name']);
                        array_push($linkedInPresentCompanyIds,$val['company']['id']);
                        array_push($linkedInPresentCompanyStartDates,$val['startDate']['year']);
                        array_push($linkedInPresentCompanyEndDates,$val['endDate']['year']);
                        array_push($linkedInJobTitles,$val['title']);
                        $this->setPresentTitle($val['title']);
                        logToFile($val['title']);


                    } else {
                        array_push($linkedInPreviousCompanyNames,$val['company']['name']);
                        array_push($linkedInPreviousCompanyIds,$val['company']['id']);
                        array_push($linkedInPreviousCompanyStartDates,$val['startDate']['year']);
                        array_push($linkedInPreviousCompanyEndDates,$val['endDate']['year']);
                        array_push($linkedInJobTitles,$val['title']);
                        logToFile($val['title']);
                    }
                }

                $this->setLinkedInPresentCompanyNames($linkedInPresentCompanyNames);
                $this->setLinkedInPresentCompanyIds($linkedInPresentCompanyIds);
                $this->setLinkedInPresentCompanyStartDates($linkedInPresentCompanyStartDates);
                $this->setLinkedInPresentCompanyEndDates($linkedInPresentCompanyEndDates);

                $this->setLinkedInPreviousCompanyNames($linkedInPreviousCompanyNames);
                $this->setLinkedInPreviousCompanyIds($linkedInPreviousCompanyIds);
                $this->setLinkedInPreviousCompanyStartDates($linkedInPreviousCompanyStartDates);
                $this->setLinkedInPreviousCompanyEndDates($linkedInPreviousCompanyEndDates);

            }

            $followingCompanies = $jsonIterator['following'];
            $linkedInFollowCompany = array();
            $count = 0;
            foreach ($followingCompanies as $follow) {
                foreach ($follow as $f) {
                    foreach($f as $val) {
                        $linkedInFollowCompany[$count] = $val['id'];
                        $linkedInFollowCompany[$count+1]= $val['name'];

                        $count++;
                    }

                }
            }
            $this->setLinkedInFollowCompany($linkedInFollowCompany);


            $count = 0;
            $linkedInEducations = array();
            $educations = $jsonIterator['educations'];
            logToFile("Educations :".$educations);
            foreach ($educations as $edu) {

                foreach ($edu as $val) {

                    $linkedInEducations[$count] = $val['id'];
                    $linkedInEducations[$count+1] = $val['schoolName'];
                    $linkedInEducations[$count+2]= $val['fieldOfStudy'];
                    $linkedInEducations[$count+3]= $val['startDate']['year'];
                    $linkedInEducations[$count+4]= $val['endDate']['year'];
                    $linkedInEducations[$count+5]= $val['degree'];
                    $linkedInEducations[$count+6]= $val['activities'];
                    $linkedInEducations[$count+7]= $val['notes'];

                    $count++;

                }
            }
            $this->setLinkedInEducations($linkedInEducations);

            $count = 0;
            $linkedInCertifications = array();
            $certifications = $jsonIterator['certifications'];
            foreach ($certifications as $cert) {

                foreach ($cert as $val) {

                    $linkedInCertifications[$count] = $val['id'];
                    $linkedInCertifications[$count+1] = $val['name'];
                    $linkedInCertifications[$count+2]= $val['authority']['name'];
                    $linkedInCertifications[$count+3]= $val['number'];
                    $linkedInCertifications[$count+4]= $val['startDate']['year'];
                    $linkedInCertifications[$count+5]= $val['endDate']['year'];

                    $count++;

                }

            }
            $this->setLinkedInCertifications($linkedInCertifications);

            $count = 0;
            $linkedInPatents = array();
            $patents = $jsonIterator['patents'];
            foreach ($patents as $patent) {

                foreach ($patent as $val) {

                    $linkedInPatents[$count] = $val['id'];
                    $linkedInPatents[$count+1] = $val['title'];
                    $linkedInPatents[$count+2]= $val['summary'];
                    $linkedInPatents[$count+3]= $val['number'];


                    $count++;

                }

            }
            $this->setLinkedInPatents($linkedInPatents);


            $_SESSION['_linkedInUserID'] = $linkedInID;
        }

    }

    private function getLinkedInConnectionsJSON() {
        $linkedInFetch = new LinkedInFetch();
        $jsonLoggedInUser = $linkedInFetch->fetch($this->createLinkedInConnectionFetchUrl());

//       logToFile($jsonLoggedInUser);
        $this->setLinkedInConnectionsData($jsonLoggedInUser);

    }

    private function getNumberOfConnectedIndustryTitlesExperience() {
        $connectionsJSON = $this->getLinkedInConnectionsData();

        if(!isset($connectionsJSON) || $connectionsJSON == ''){
            logToFile('User Network could not be retrieved from LinkedIn');
            throw new Exception('User Network could not be retrieved from LinkedIn');
        } else {
            $linkedInNetwork = json_decode($connectionsJSON, TRUE);
            $industryAndTitlesArray = array();
            foreach($linkedInNetwork as $network) {
                foreach($network as $val) {
                    $linkedInConnectionPresentCompanyStartDates = array();
                    $linkedInConnectionPresentCompanyEndDates = array();
                    $linkedInConnectionPreviousCompanyStartDates = array();
                    $linkedInConnectionPreviousCompanyEndDates = array();

                    $industry = $val['industry'];
//                    logToFile("Network Industry :".$industry);
                    $positions = $val['positions'];
                    $titleArray = array();
                    foreach ($positions as $pos) {
                        foreach ($pos as $val) {
                            if($val['isCurrent'] == true || $val['isCurrent'] == 1 ) {
                                array_push($linkedInConnectionPresentCompanyStartDates,$val['startDate']['year']);
                                array_push($linkedInConnectionPresentCompanyEndDates,$val['endDate']['year']);
                                $networkTitle = $val['title'];
//                                logToFile("Network Title: ".$networkTitle);
                                array_push($titleArray,$networkTitle);

                            } else {
                                array_push($linkedInConnectionPreviousCompanyStartDates,$val['startDate']['year']);
                                array_push($linkedInConnectionPreviousCompanyEndDates,$val['endDate']['year']);
                            }


                        }
                    }
                    $maxEndDateInPresentCompany = max($linkedInConnectionPresentCompanyEndDates);
                    if(trim($maxEndDateInPresentCompany) == "" ||$maxEndDateInPresentCompany == null ) {
                        $maxEndDateInPresentCompany = date("Y");
                    }
//                    logToFile("maxEndDateInPresentCompany:" . $maxEndDateInPresentCompany);

                    $minStartDateInPreviousCompany = min($linkedInConnectionPreviousCompanyStartDates);
                    if(trim($minStartDateInPreviousCompany) == "" || $minStartDateInPreviousCompany == null) {
                        $minStartDateInPreviousCompany = date("Y");
                    }
//                    logToFile("minStartDateInPreviousCompany:" . $minStartDateInPreviousCompany);

                    $yearsOfExperience = $maxEndDateInPresentCompany - $minStartDateInPreviousCompany;
//                    logToFile("Experience in years:".$yearsOfExperience);

                    array_push($industryAndTitlesArray,$industry,$titleArray,$yearsOfExperience);
//                    print_r($industryAndTitles) ;

                }
            }
            $this->setLinkedInConnectionsIndustryTitlesExperienceArray($industryAndTitlesArray);
        }
    }


    //find the number of years of experience of the person in context
    private function getExperienceInYears() {

        $maxEndDateInPresentCompany = max($this->getLinkedInPresentCompanyEndDates());
        if(trim($maxEndDateInPresentCompany) == "" || $maxEndDateInPresentCompany == null) {
            $maxEndDateInPresentCompany = date("Y");
        }
        logToFile("maxEndDateInPresentCompany:" . $maxEndDateInPresentCompany);

        $minStartDateInPreviousCompany = min($this->getLinkedInPreviousCompanyStartDates());
        if(trim($minStartDateInPreviousCompany) == "" || $minStartDateInPreviousCompany == null) {
            $minStartDateInPreviousCompany = date("Y");
        }
        logToFile("minStartDateInPreviousCompany:" . $minStartDateInPreviousCompany);

        $yearsOfExperience = $maxEndDateInPresentCompany - $minStartDateInPreviousCompany;
        return $yearsOfExperience;
    }

    private function getNumberOfConnectedOfficersOfSameIndustry() {
        $industryAndTitlesArray = $this->getLinkedInConnectionsIndustryTitlesExperienceArray();
        $cxoCountOfSameIndustry =0;
        $svpCountOfSameIndustry =0;
        $vpCountOfSameIndustry =0;
        $directorCountOfSameIndustry = 0;

        $myIndustry = $this->getIndustry();
        $myTitle = $this->getPresentTitle();
        $experienceArray = array();
        $titlesOfConnection = array();
        for($i=0;$i<count($industryAndTitlesArray);$i+=3) {
            $cxoFlag = false;
            $svpFlag=false;
            $vpFlag = false;
            $directorFlag = false;
            $industryOfConnection = $industryAndTitlesArray[$i];
            $isMatch = preg_match('/\b'.$myIndustry.'\b/i',$industryOfConnection);
            if($isMatch == 0) continue;
            $titlesOfConnection = $industryAndTitlesArray[$i+1];

            $cxo = $this->CXO;
            $tempArray1 = preg_grep("/".$cxo."/i",$titlesOfConnection);
            if(count($tempArray1)>0){
                $cxoCountOfSameIndustry++;
                $cxoFlag = true;
            }
            $tempArray2 = preg_grep("/".$cxo."/i",$titlesOfConnection,PREG_GREP_INVERT);

            $svp = $this->SVP;
            $tempArray1 = preg_grep("/".$svp."/i",$tempArray2);
            if(count($tempArray1)>0){
                $svpCountOfSameIndustry++;
                $svpFlag = true;
            }
            $tempArray2 = preg_grep("/".$svp."/i",$tempArray2,PREG_GREP_INVERT);

            $vp = $this->VP;
            $tempArray1 = preg_grep("/".$vp."/i",$tempArray2);
            if(count($tempArray1)>0){
                $vpCountOfSameIndustry++;
                $vpFlag = true;
            }
            $tempArray2 = preg_grep("/".$vp."/i",$tempArray2,PREG_GREP_INVERT);

            $director = $this->DIRECTOR;
            $tempArray1 = preg_grep("/".$director."/i",$tempArray2);
            if(count($tempArray1)>0){
                $directorCountOfSameIndustry++;
                $directorFlag = true;
            }
            $tempArray2 = preg_grep("/".$director."/i",$tempArray2,PREG_GREP_INVERT);

            //if the person is not CXO, SVP, VP or director
            if($cxoFlag | $svpFlag | $vpFlag | $directorFlag == false) {
                $experience = $industryAndTitlesArray[$i+2];
                //all the experiences are pushed into an array. This array will be used to find out the weight of my experience in my network.
                array_push($experienceArray,$experience);
            }


        }
        logToFile("Number of connected CXO's of same industry: ".$cxoCountOfSameIndustry);
        logToFile("Number of connected SVP's of same industry: ".$svpCountOfSameIndustry);
        logToFile("Number of connected VP's of same industry: ".$vpCountOfSameIndustry);
        logToFile("Number of connected Directors's of same industry: ".$directorCountOfSameIndustry);

        $returnArray = array("CXO" => $cxoCountOfSameIndustry,"SVP" => $svpCountOfSameIndustry,
            "VP" => $vpCountOfSameIndustry,"Director" => $directorCountOfSameIndustry,"Others" => $experienceArray);

        return $returnArray;

    }

    private function getNumberOfConnectedOfficersOfDifferentIndustry() {
        $industryAndTitlesArray = $this->getLinkedInConnectionsIndustryTitlesExperienceArray();
        $cxoCountOfDiffIndustry =0;
        $svpCountOfDiffIndustry =0;
        $vpCountOfDiffIndustry =0;
        $directorCountOfDiffIndustry = 0;
        $myIndustry = $this->getIndustry();
        $myTitle = $this->getPresentTitle();
        $industryOfConnection = "";
        $titlesOfConnection = array();
        $experienceArray = array();
        for($i=0;$i<count($industryAndTitlesArray);$i+=3) {
            $cxoFlag = false;
            $svpFlag=false;
            $vpFlag = false;
            $directorFlag = false;
            $industryOfConnection = $industryAndTitlesArray[$i];
            $isMatch = preg_match('/\b'.$myIndustry.'\b/i',$industryOfConnection);
            if($isMatch != 0) continue;
            $titlesOfConnection = $industryAndTitlesArray[$i+1];
            $experience = $industryAndTitlesArray[$i+2];

            $cxo = $this->CXO;
            $tempArray1 = preg_grep("/".$cxo."/i",$titlesOfConnection);

            if(count($tempArray1)>0){
                $cxoCountOfDiffIndustry++;
                $cxoFlag = true;
            }

            $tempArray2 = preg_grep("/".$cxo."/i",$titlesOfConnection,PREG_GREP_INVERT);

            $svp = $this->SVP;
            $tempArray1 = preg_grep("/".$svp."/i",$tempArray2);
            if(count($tempArray1)>0){
                $svpCountOfDiffIndustry++;
                $svpFlag = true;
            }
            $tempArray2 = preg_grep("/".$svp."/i",$tempArray2,PREG_GREP_INVERT);

            $vp = $this->VP;
            $tempArray1 = preg_grep("/".$vp."/i",$tempArray2);
            if(count($tempArray1)>0){
                $vpCountOfDiffIndustry++;
                $vpFlag = true;
            }
            $tempArray2 = preg_grep("/".$vp."/i",$tempArray2,PREG_GREP_INVERT);

            $director = $this->DIRECTOR;
            $tempArray1 = preg_grep("/".$director."/i",$tempArray2);
            if(count($tempArray1)>0){
                $directorCountOfDiffIndustry++;
                $directorFlag = true;
            }
            $tempArray2 = preg_grep("/".$director."/i",$tempArray2,PREG_GREP_INVERT);

            //if the person is not CXO, SVP, VP or director
            if($cxoFlag | $svpFlag | $vpFlag | $directorFlag == false) {
                if($cxoFlag | $svpFlag | $vpFlag | $directorFlag == false) {
                    $experience = $industryAndTitlesArray[$i+2];
                    //all the experiences are pushed into an array. This array will be used to find out the weight of my experience in my network.
                    array_push($experienceArray,$experience);
                }
            }

        }
        logToFile("Number of connected CXO's of Different industry: ".$cxoCountOfDiffIndustry);
        logToFile("Number of connected SVP's of Different industry: ".$svpCountOfDiffIndustry);
        logToFile("Number of connected VP's of Different industry: ".$vpCountOfDiffIndustry);
        logToFile("Number of connected Directors's of Different industry: ".$directorCountOfDiffIndustry);

        $returnArray = array("CXO" => $cxoCountOfDiffIndustry,"SVP" => $svpCountOfDiffIndustry,
            "VP" => $vpCountOfDiffIndustry,"Director" => $directorCountOfDiffIndustry,"Others" => $experienceArray);

        return $returnArray;


    }

    private function getNumberOfCommonIndustry() {
        $noOfSameIndustry = 0;
        $linkedInNetwork = $this->getLinkedInConnectionsData();
        if(!isset($linkedInNetwork) || $linkedInNetwork == ''){
            logToFile('User Network could not be retrieved from LinkedIn');
            throw new Exception('User Network could not be retrieved from LinkedIn');
        } else {
            $linkedInNetwork = json_decode($linkedInNetwork, TRUE);
            foreach($linkedInNetwork as $network) {
                foreach($network as $val) {
                    $industry = $val['industry'];

//                    logToFile("Industry is: " .$industry);
                    $location = $val['location']['country']['code'];
//                    logToFile("Location is: " .$location);
                    if(strcasecmp($this->industry,$industry) == 0) {
                        $noOfSameIndustry++;
                    }
                }

            }

        }
        return $noOfSameIndustry;
    }

    function getWeights() {
        //get weight for connections of same industry
        $numberOfConnectionsOfSameIndustry = $this->getNumberOfCommonIndustry();
        $ratio = round($numberOfConnectionsOfSameIndustry/$this->numOfConnections,4);


        //get weight for kind of officers connected of same industry
        $strArray1 = $this->getNumberOfConnectedOfficersOfSameIndustry();
        $cxoOfSameIndustry = $strArray1["CXO"];
        $svpOfSameIndustry = $strArray1["SVP"];
        $vpOfSameIndustry = $strArray1["VP"];
        $directorOfSameIndustry = $strArray1["Director"];
        $othersOfSameIndustryArray = $strArray1["Others"];
        $maxExperienceOfOthersOfSameIndustry = max($othersOfSameIndustryArray);
        $selfExperienceInYears = $this->getExperienceInYears();

        $sameIndustryConnectionsWeight = round(Weights::getSameIndustryWeight($ratio),4);
        $cxoSameIndustryConnectionsRatioWeight = round(Weights::getOfficersRatioWeight($cxoOfSameIndustry,$numberOfConnectionsOfSameIndustry),4);
        $svpSameIndustryConnectionsRatioWeight = round(Weights::getOfficersRatioWeight($svpOfSameIndustry,$numberOfConnectionsOfSameIndustry),4);
        $vpSameIndustryConnectionsRatioWeight = round(Weights::getOfficersRatioWeight($vpOfSameIndustry,$numberOfConnectionsOfSameIndustry),4);
        $directorSameIndustryConnectionsRatioWeight = round(Weights::getOfficersRatioWeight($directorOfSameIndustry,$numberOfConnectionsOfSameIndustry),4);

        $cxoSameIndustryConnectionsAbsoluteWeight = round(Weights::getOfficersAbsoluteWeight($cxoOfSameIndustry),4);
        $svpSameIndustryConnectionsAbsoluteWeight = round(Weights::getOfficersAbsoluteWeight($svpOfSameIndustry),4);
        $vpSameIndustryConnectionsAbsoluteWeight = round(Weights::getOfficersAbsoluteWeight($vpOfSameIndustry),4);
        $directorSameIndustryConnectionsAbsoluteWeight = round(Weights::getOfficersAbsoluteWeight($directorOfSameIndustry),4);

        $myExperienceSameIndustryWeight = round(Weights::getSeniorityWeight($selfExperienceInYears,$maxExperienceOfOthersOfSameIndustry),4);


        $numOfConnectionsOfDifferentIndustry = $this->numOfConnections - $numberOfConnectionsOfSameIndustry;

        $strArray2 = $this->getNumberOfConnectedOfficersOfDifferentIndustry();
        $cxoOfDifferentIndustry = $strArray2["CXO"];
        $svpOfDifferentIndustry = $strArray2["SVP"];
        $vpOfDifferentIndustry = $strArray2["VP"];
        $directorOfDifferentIndustry = $strArray2["Director"];
        $othersOfDifferentIndustryArray = $strArray2["Others"];

        $maxExperienceOfOthersOfDifferentIndustry = max($othersOfDifferentIndustryArray);

        $cxoDiffIndustryConnectionsRatioWeight = round(Weights::getOfficersRatioWeight($cxoOfDifferentIndustry,$numOfConnectionsOfDifferentIndustry),4);
        $svpDiffIndustryConnectionsRatioWeight = round(Weights::getOfficersRatioWeight($svpOfDifferentIndustry,$numOfConnectionsOfDifferentIndustry),4);
        $vpDiffIndustryConnectionsRatioWeight = round(Weights::getOfficersRatioWeight($vpOfDifferentIndustry,$numOfConnectionsOfDifferentIndustry),4);
        $directorDiffIndustryConnectionsRatioWeight = round(Weights::getOfficersRatioWeight($directorOfDifferentIndustry,$numOfConnectionsOfDifferentIndustry),4);

        $cxoDiffIndustryConnectionsAbsoluteWeight = round(Weights::getOfficersAbsoluteWeight($cxoOfDifferentIndustry),4);
        $svpDiffIndustryConnectionsAbsoluteWeight = round(Weights::getOfficersAbsoluteWeight($svpOfDifferentIndustry),4);
        $vpDiffIndustryConnectionsAbsoluteWeight = round(Weights::getOfficersAbsoluteWeight($vpOfDifferentIndustry),4);
        $directorDiffIndustryConnectionsAbsoluteWeight = round(Weights::getOfficersAbsoluteWeight($directorOfDifferentIndustry),4);

        $myExperienceDiffIndustryWeight = round(Weights::getSeniorityWeight($selfExperienceInYears,$maxExperienceOfOthersOfDifferentIndustry),4);

        logToFile("No of connections in same industry: " .$numberOfConnectionsOfSameIndustry);
        logToFile("Total number of connections: " .$this->numOfConnections);
        logToFile("No of CXO's of Same Industry: ".$cxoOfSameIndustry);
        logToFile("No of SVPs of Same Industry: ".$svpOfSameIndustry);
        logToFile("No of VPs of Same Industry: ".$vpOfSameIndustry);
        logToFile("No of Directors of Same Industry: " .$directorOfSameIndustry);
        logToFile("Max Experience Others of Same Industry: ".$maxExperienceOfOthersOfSameIndustry);
        logToFile("Self Experience: ".$selfExperienceInYears);
        logToFile("No of CXO's of Different Industry: ".$cxoOfDifferentIndustry);
        logToFile("No of SVP's of Different Industry: ".$svpOfDifferentIndustry);
        logToFile("No of VP's of Different Industry: ".$vpOfDifferentIndustry);
        logToFile("No of Director's of Different Industry: ".$directorOfDifferentIndustry);
        logToFile("Max Experience Others of Different Industry: ".$maxExperienceOfOthersOfDifferentIndustry);



        $returnArray = array("SameIndustryConnectionsWeight" =>$sameIndustryConnectionsWeight,
                             "CXOSameIndustryConnectionsRatioWeight" => $cxoSameIndustryConnectionsRatioWeight,
                             "SVPSameIndustryConnectionsRatioWeight" => $svpSameIndustryConnectionsRatioWeight,
                             "VPSameIndustryConnectionsRatioWeight" => $vpSameIndustryConnectionsRatioWeight,
                             "DirectorSameIndustryConnectionsRatioWeight" => $directorSameIndustryConnectionsRatioWeight,
                             "CXOSameIndustryConnectionsAbsoluteWeight" => $cxoSameIndustryConnectionsAbsoluteWeight,
                             "SVPSameIndustryConnectionsAbsoluteWeight" => $svpSameIndustryConnectionsAbsoluteWeight,
                             "VPSameIndustryConnectionsAbsoluteWeight" => $vpSameIndustryConnectionsAbsoluteWeight,
                             "DirectorSameIndustryConnectionsAbsoluteWeight" => $directorSameIndustryConnectionsAbsoluteWeight,
                             "MyExperienceSameIndustryWeight" => $myExperienceSameIndustryWeight,
                             "CXODifferentIndustryConnectionsRatioWeight" => $cxoDiffIndustryConnectionsRatioWeight,
                             "SVPDifferentIndustryConnectionsRatioWeight" => $svpDiffIndustryConnectionsRatioWeight,
                             "VPDifferentIndustryConnectionsRatioWeight" => $vpDiffIndustryConnectionsRatioWeight,
                             "DirectorDifferentIndustryConnectionsRatioWeight" => $directorDiffIndustryConnectionsRatioWeight,
                             "CXODifferentIndustryConnectionsAbsoluteWeight" => $cxoDiffIndustryConnectionsAbsoluteWeight,
                             "SVPDifferentIndustryConnectionsAbsoluteWeight" => $svpDiffIndustryConnectionsAbsoluteWeight,
                             "VPDifferentIndustryConnectionsAbsoluteWeight" => $vpDiffIndustryConnectionsAbsoluteWeight,
                             "DirectorDifferentIndustryConnectionsAbsoluteWeight" => $directorDiffIndustryConnectionsAbsoluteWeight,
                             "MyExperienceDifferentIndustryWeight" => $myExperienceDiffIndustryWeight);

        return $returnArray;
    }






    function createLinkedInUserFetchUrl(){
        $profile_fields =  ':(id,first-name,last-name,industry,specialties,associations,honors,following,num-connections,';
        $profile_fields .= 'positions:(is-current,title,company:(id,name),start-date,end-date),';
        $profile_fields .= 'educations:(id,school-name,field-of-study,start-date,end-date,degree,activities,notes),';
        $profile_fields .= 'certifications:(id,name,authority:(name),number,start-date,end-date),';
        $profile_fields .= 'patents:(id,title,summary,number))';
//        ,status:(id),status:(name),office:(name),inventors:(name)))';
//        $profile_fields .= 'publications:(id,title,publisher:(name),authors:(id),authors:(name),authors:(person),date,url))';

        $profile_url = 'http://api.linkedin.com/v1/people/~'.$profile_fields;

//        logToFile($profile_url);
        return $profile_url;
    }

    function createLinkedInConnectionFetchUrl() {
        $profile_url = 'http://api.linkedin.com/v1/people/~/connections';
        $profile_fields = ':(id,first-name,last-name,industry,positions:(is-current,title,company:(id,name),start-date,end-date))';
        $profile_url .= $profile_fields;
        return $profile_url;

    }









    function findJobsbyRecruiterId($recruiterId) {
        $jobArray = JobDAO::findJobByRecruiterId($recruiterId);
        return $jobArray;
    }

    public function setIndustry($industry)
    {
        $this->industry = $industry;
    }

    public function getIndustry()
    {
        return $this->industry;
    }

    public function setLinkedInCertifications($linkedInCertifications)
    {
        $this->linkedInCertifications = $linkedInCertifications;
    }

    public function getLinkedInCertifications()
    {
        return $this->linkedInCertifications;
    }

    public function setLinkedInEducations($linkedInEducations)
    {
        $this->linkedInEducations = $linkedInEducations;
    }

    public function getLinkedInEducations()
    {
        return $this->linkedInEducations;
    }

    public function setLinkedInFollowCompany($linkedInFollowCompany)
    {
        $this->linkedInFollowCompany = $linkedInFollowCompany;
    }

    public function getLinkedInFollowCompany()
    {
        return $this->linkedInFollowCompany;
    }

    public function setLinkedInPatents($linkedInPatents)
    {
        $this->linkedInPatents = $linkedInPatents;
    }

    public function getLinkedInPatents()
    {
        return $this->linkedInPatents;
    }

    public function setLinkedInPresentCompanyNames($linkedInPresentCompany)
    {
        $this->linkedInPresentCompanyNames = $linkedInPresentCompany;
    }

    public function getLinkedInPresentCompanyNames()
    {
        return $this->linkedInPresentCompanyNames;
    }

    public function setLinkedInPreviousCompanyNames($linkedInPreviousCompany)
    {
        $this->linkedInPreviousCompanyNames = $linkedInPreviousCompany;
    }

    public function getLinkedInPreviousCompanyNames()
    {
        return $this->linkedInPreviousCompanyNames;
    }

    public function setMeaningfulNetworking($meaningfulNetworking)
    {
        $this->meaningfulNetworking = $meaningfulNetworking;
    }

    public function getMeaningfulNetworking()
    {
        return $this->meaningfulNetworking;
    }

    public function setNumOfConnections($numOfConnections)
    {
        $this->numOfConnections = $numOfConnections;
    }

    public function getNumOfConnections()
    {
        return $this->numOfConnections;
    }

    public function setSpecialities($specialities)
    {
        $this->specialities = $specialities;
    }

    public function getSpecialities()
    {
        return $this->specialities;
    }

    public function setLinkedInPresentCompanyEndDates($linkedInPresentCompanyEndDates)
    {
        $this->linkedInPresentCompanyEndDates = $linkedInPresentCompanyEndDates;
    }

    public function getLinkedInPresentCompanyEndDates()
    {
        return $this->linkedInPresentCompanyEndDates;
    }

    public function setLinkedInPresentCompanyIds($linkedInPresentCompanyIds)
    {
        $this->linkedInPresentCompanyIds = $linkedInPresentCompanyIds;
    }

    public function getLinkedInPresentCompanyIds()
    {
        return $this->linkedInPresentCompanyIds;
    }

    public function setLinkedInPresentCompanyStartDates($linkedInPresentCompanyStartDates)
    {
        $this->linkedInPresentCompanyStartDates = $linkedInPresentCompanyStartDates;
    }

    public function getLinkedInPresentCompanyStartDates()
    {
        return $this->linkedInPresentCompanyStartDates;
    }

    public function setLinkedInPreviousCompanyEndDates($linkedInPreviousCompanyEndDates)
    {
        $this->linkedInPreviousCompanyEndDates = $linkedInPreviousCompanyEndDates;
    }

    public function getLinkedInPreviousCompanyEndDates()
    {
        return $this->linkedInPreviousCompanyEndDates;
    }

    public function setLinkedInPreviousCompanyIds($linkedInPreviousCompanyIds)
    {
        $this->linkedInPreviousCompanyIds = $linkedInPreviousCompanyIds;
    }

    public function getLinkedInPreviousCompanyIds()
    {
        return $this->linkedInPreviousCompanyIds;
    }

    public function setLinkedInPreviousCompanyStartDates($linkedInPreviousCompanyStartDates)
    {
        $this->linkedInPreviousCompanyStartDates = $linkedInPreviousCompanyStartDates;
    }

    public function getLinkedInPreviousCompanyStartDates()
    {
        return $this->linkedInPreviousCompanyStartDates;
    }

    public function setLinkedInJobTitles($linkedInJobTitles)
    {
        $this->linkedInJobTitles = $linkedInJobTitles;
    }

    public function getLinkedInJobTitles()
    {
        return $this->linkedInJobTitles;
    }

    public function setPresentTitle($presentTitle)
    {
        $this->presentTitle = $presentTitle;
    }

    public function getPresentTitle()
    {
        return $this->presentTitle;
    }

    public function setLinkedInConnectionsData($linkedInConnectionsData)
    {
        $this->linkedInConnectionsData = $linkedInConnectionsData;
    }

    public function getLinkedInConnectionsData()
    {
        return $this->linkedInConnectionsData;
    }

    public function setLinkedInConnectionsIndustryTitlesExperienceArray($linkedInConnectionsIndustryAndTitlesArray)
    {
        $this->linkedInConnectionsIndustryTitlesExperienceArray = $linkedInConnectionsIndustryAndTitlesArray;
    }

    public function getLinkedInConnectionsIndustryTitlesExperienceArray()
    {
        return $this->linkedInConnectionsIndustryTitlesExperienceArray;
    }


}
