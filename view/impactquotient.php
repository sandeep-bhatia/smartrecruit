<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gauravkhullar
 * Date: 7/27/12
 * Time: 2:20 PM
 * To change this template use File | Settings | File Templates.
 */
include_once('../viewclasses/ImpactQuotientView.php');

session_start();
$errorId = $_REQUEST['ERROR_ID'];

$impactView = new ImpactQuotientView();
$weightArray = $impactView ->getWeights();

$sameIndustryConnectionsWeight = $weightArray["SameIndustryConnectionsWeight"];
$cxoSameIndustryConnectionsRatioWeight = $weightArray["CXOSameIndustryConnectionsRatioWeight"];
$svpSameIndustryConnectionsRatioWeight = $weightArray["SVPSameIndustryConnectionsRatioWeight"];
$vpSameIndustryConnectionsRatioWeight = $weightArray["VPSameIndustryConnectionsRatioWeight"];
$directorSameIndustryConnectionsRatioWeight = $weightArray["DirectorSameIndustryConnectionsRatioWeight"];
$cxoSameIndustryConnectionsAbsoluteWeight = $weightArray["CXOSameIndustryConnectionsAbsoluteWeight"];
$svpSameIndustryConnectionsAbsoluteWeight = $weightArray["SVPSameIndustryConnectionsAbsoluteWeight"];
$vpSameIndustryConnectionsAbsoluteWeight = $weightArray["VPSameIndustryConnectionsAbsoluteWeight"];
$directorSameIndustryConnectionsAbsoluteWeight = $weightArray["DirectorSameIndustryConnectionsAbsoluteWeight"];
$myExperienceSameIndustryWeight = $weightArray["MyExperienceSameIndustryWeight"];

$cxoDiffIndustryConnectionsRatioWeight = $weightArray["CXODifferentIndustryConnectionsRatioWeight"];
$svpDiffIndustryConnectionsRatioWeight = $weightArray["SVPDifferentIndustryConnectionsRatioWeight"];
$vpDiffIndustryConnectionsRatioWeight = $weightArray["VPDifferentIndustryConnectionsRatioWeight"];
$directorDiffIndustryConnectionsRatioWeight = $weightArray["DirectorDifferentIndustryConnectionsRatioWeight"];
$cxoDiffIndustryConnectionsAbsoluteWeight = $weightArray["CXODifferentIndustryConnectionsAbsoluteWeight"];
$svpDiffIndustryConnectionsAbsoluteWeight = $weightArray["SVPDifferentIndustryConnectionsAbsoluteWeight"];
$vpDiffIndustryConnectionsAbsoluteWeight = $weightArray["VPDifferentIndustryConnectionsAbsoluteWeight"];
$directorDiffIndustryConnectionsAbsoluteWeight = $weightArray["DirectorDifferentIndustryConnectionsAbsoluteWeight"];
$myExperienceDiffIndustryWeight = $weightArray["MyExperienceDifferentIndustryWeight"];

$niq = (Weights::_SameIndustryConnectionsWeight * $sameIndustryConnectionsWeight) +
    (Weights::_CXOSameIndustryWeight * ($cxoSameIndustryConnectionsRatioWeight + $cxoSameIndustryConnectionsAbsoluteWeight)) +
    (Weights::_SVPSameIndustryWeight * ($svpSameIndustryConnectionsRatioWeight + $svpSameIndustryConnectionsAbsoluteWeight)) +
    (Weights::_VPSameIndustryWeight * ($vpSameIndustryConnectionsRatioWeight + $vpSameIndustryConnectionsAbsoluteWeight)) +
    (Weights::_DirectorSameIndustryWeight * ($directorSameIndustryConnectionsRatioWeight + $directorSameIndustryConnectionsAbsoluteWeight)) +
    (Weights::_PersonalExperienceSameIndustryWeight * $myExperienceSameIndustryWeight) +
    (Weights::_CXODifferentIndustryWeight * ($cxoDiffIndustryConnectionsRatioWeight + $cxoDiffIndustryConnectionsAbsoluteWeight)) +
    (Weights::_SVPDifferentIndustryWeight * ($svpDiffIndustryConnectionsRatioWeight + $svpDiffIndustryConnectionsAbsoluteWeight)) +
    (Weights::_VPDifferentIndustryWeight * ($vpDiffIndustryConnectionsRatioWeight + $vpDiffIndustryConnectionsAbsoluteWeight)) +
    (Weights::_DirectorDifferentIndustryWeight * ($directorDiffIndustryConnectionsRatioWeight + $directorDiffIndustryConnectionsAbsoluteWeight)) +
    (Weights::_PersonalExperienceDiffIndustryWeight * $myExperienceDiffIndustryWeight);

$sumOfWeights = Weights::_SameIndustryConnectionsWeight + Weights::_CXOSameIndustryWeight + Weights::_SVPSameIndustryWeight +
    Weights::_VPSameIndustryWeight +  Weights::_DirectorSameIndustryWeight + Weights::_PersonalExperienceSameIndustryWeight +
    Weights::_CXODifferentIndustryWeight + Weights::_SVPDifferentIndustryWeight + Weights::_VPDifferentIndustryWeight +
    Weights::_DirectorDifferentIndustryWeight + Weights::_PersonalExperienceDiffIndustryWeight;

$weightAverageOfNIQ = round(($niq/$sumOfWeights),2);
$maximumPossibleNIQ = (Weights::_SameIndustryConnectionsWeight * Weights::getSameIndustryWeight(1)) +
    (Weights::_CXOSameIndustryWeight * (Weights::getOfficersRatioWeight(1,1) + Weights::getOfficersAbsoluteWeight(100))) +
    (Weights::_SVPSameIndustryWeight * (Weights::getOfficersRatioWeight(1,1) + Weights::getOfficersAbsoluteWeight(100))) +
    (Weights::_VPSameIndustryWeight * (Weights::getOfficersRatioWeight(1,1) + Weights::getOfficersAbsoluteWeight(100))) +
    (Weights::_DirectorSameIndustryWeight * (Weights::getOfficersRatioWeight(1,1) + Weights::getOfficersAbsoluteWeight(100))) +
    (Weights::_PersonalExperienceSameIndustryWeight * Weights::getSeniorityWeight(30,30)) +
    (Weights::_CXODifferentIndustryWeight * (Weights::getOfficersRatioWeight(1,1) + Weights::getOfficersAbsoluteWeight(100))) +
    (Weights::_SVPDifferentIndustryWeight * (Weights::getOfficersRatioWeight(1,1) + Weights::getOfficersAbsoluteWeight(100))) +
    (Weights::_VPDifferentIndustryWeight * (Weights::getOfficersRatioWeight(1,1) + Weights::getOfficersAbsoluteWeight(100))) +
    (Weights::_DirectorDifferentIndustryWeight * (Weights::getOfficersRatioWeight(1,1) + Weights::getOfficersAbsoluteWeight(100))) +
    (Weights::_PersonalExperienceDiffIndustryWeight * Weights::getSeniorityWeight(30,30));

$maximumPossibleWeightedAverageOfNIQ = round(($maximumPossibleNIQ/$sumOfWeights),2);

$niqPercentage = round((($weightAverageOfNIQ/$maximumPossibleWeightedAverageOfNIQ) * 100),2);
?>
<html>
<head><title>Weights</title>
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
    </style>
</head>
<body>
<table colums = 2 class="gridtable">
    <tr>
        <th colspan="2">Net Impact Quotient (NIQ)</th>
    </tr>
    <tr>
        <td>
            Same Industry Connections Weight (Meaningful Networking)
        </td>
        <td>
            <?php echo $sameIndustryConnectionsWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            CXO's Same Industry Weight (ratio + absolute)
        </td>
        <td>
            <?php echo $cxoSameIndustryConnectionsRatioWeight ?> + &nbsp; <?php echo $cxoSameIndustryConnectionsAbsoluteWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            SVP's Same Industry Weight (ratio + absolute)
        </td>
        <td>
            <?php echo $svpSameIndustryConnectionsRatioWeight ?> + &nbsp; <?php echo $svpSameIndustryConnectionsAbsoluteWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            VP's Same Industry Weight (ratio + absolute)
        </td>
        <td>
            <?php echo $vpSameIndustryConnectionsRatioWeight ?>  + &nbsp; <?php echo $vpSameIndustryConnectionsAbsoluteWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            Director's Same Industry Weight (ratio + absolute)
        </td>
        <td>
            <?php echo $directorSameIndustryConnectionsRatioWeight ?>  + &nbsp; <?php echo $directorSameIndustryConnectionsAbsoluteWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            Personal Experience Weight in Same Industry
        </td>
        <td>
            <?php echo $myExperienceSameIndustryWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            CXO's Different Industry Weight (ratio + absolute)
        </td>
        <td>
            <?php echo $cxoDiffIndustryConnectionsRatioWeight ?> + &nbsp; <?php echo $cxoDiffIndustryConnectionsAbsoluteWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            SVP's Different Industry Weight (ratio + absolute)
        </td>
        <td>
            <?php echo $svpDiffIndustryConnectionsRatioWeight ?> + &nbsp; <?php echo $svpDiffIndustryConnectionsAbsoluteWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            VP's Different Industry Weight (ratio + absolute)
        </td>
        <td>
            <?php echo $vpDiffIndustryConnectionsRatioWeight ?>  + &nbsp; <?php echo $vpDiffIndustryConnectionsAbsoluteWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            Director's Different Industry Weight (ratio + absolute)
        </td>
        <td>
            <?php echo $directorDiffIndustryConnectionsRatioWeight ?>  + &nbsp; <?php echo $directorDiffIndustryConnectionsAbsoluteWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            Personal Experience Weight in Different Industry
        </td>
        <td>
            <?php echo $myExperienceDiffIndustryWeight ?>
        </td>
    </tr>
    <tr>
        <td>
            <b>Network Impact Score</b>
        </td>
        <td>
            <?php echo $weightAverageOfNIQ ?> &nbsp; /&nbsp; <?php echo $maximumPossibleWeightedAverageOfNIQ ?>
        </td>
    </tr>
    <tr>
        <td>
            <b>Network Impact % Score</b>
        </td>
        <td>
            <?php echo $niqPercentage ?>%
        </td>
    </tr>
</table>
</body>
</html>




