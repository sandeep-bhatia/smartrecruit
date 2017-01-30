<?php
/**
 * Created by JetBrains PhpStorm.
 * User: gauravkhullar
 * Date: 7/27/12
 * Time: 4:52 PM
 * To change this template use File | Settings | File Templates.
 */
include_once('../include/common.php');

class Weights {
    const _SameIndustryConnectionsWeight = 1;
    const _CXOSameIndustryWeight = 1;
    const _SVPSameIndustryWeight = 0.95;

    const _CXODifferentIndustryWeight = 0.80;
    const _SVPDifferentIndustryWeight = 0.70;
    const _VPSameIndustryWeight = 0.65;
    const _DirectorSameIndustryWeight = 0.50;
    const _VPDifferentIndustryWeight = 0.30;
    const _DirectorDifferentIndustryWeight = 0.10;

    const _PersonalExperienceSameIndustryWeight = 1;
    const _PersonalExperienceDiffIndustryWeight = 1;

    /*
     * Ratio = (Same industry connections/total connections).
     *We need to come up with weights for each ratio. It is assumed that the weights for ratio will behave in a linear fashion.
     *The minimum and maximum value of the ratios will be 0 and 1 respectively i.e. no connections in the same industry and all connections of the same industry. i.e Min_Range = 0 and Max_Range = 1.
     *The weights for 0 and 1 ratio will be 0 and 1 respectively. Therefore the linear equation will be:
     *(Min_range x w1)  + constant = 0   ( y =0, x = Min_Range and slope is w1)
     *(Max_Range x w1) + constant = 1 ( y=1, x=Max_Range and slope is w1).
     *Using these two simultaneous equations, we will compute slope and constant i.e. w1 and constant.
     *Therefore,
     *(0xw1) + c = 0 i.e. c =0.
     *And 1xw1 + c = 1 i.e. w1 =1.
     *Now, the linear equation is computed as weight = slopexratio + constant
     * i.e. weight = 1x ratio + 0
     *i.e. weight = ratio.

     */
    public static function getSameIndustryWeight($ratio) {
        $minRange = 0.0;
        $maxRange = 1;

        $slope = 1/($maxRange-$minRange);
        $constant = -($minRange)/($maxRange-$minRange);

        $weight = ($slope * $ratio) + $constant;

        return $weight;

/*
        if($sameIndustryWeight >0.75 && $sameIndustryWeight <=1) {
            return 1.00;
        } elseif($sameIndustryWeight >0.50 && $sameIndustryWeight <=0.75) {
            return 0.75;
        } elseif($sameIndustryWeight >0.25 && $sameIndustryWeight <=0.50) {
            return 0.50;
        } elseif($sameIndustryWeight >0.00 && $sameIndustryWeight <=0.25) {
            return 0.25;
        } else
            return 0;
*/
    }
/*
    max_range coming from ones network --> weight = 1.0
    0 years --> weight =0;
*/
    public static function getSeniorityWeight($exp,$maxRange) {
        $minRange = 0;
//        $maxRange = 25;

        $slope = 1/($maxRange-$minRange);
        $constant = -($minRange)/($maxRange-$minRange);

        $weight = ($slope * $exp) + $constant;
        return $weight;

    }
/*
 * Ratio = number of respective officer connections/total connections.
 * Min_value of ratio = 0 will have a weight of 0
 * Max_value of ratio = 1 will have a weight of 1.
 * 0xw1 + c = 0 and 1xw1 + c = 1 ⇒ c = 0 and w1 = 1
 * Equation is: weight = w1*ratio + c ⇒ weight = ratio ….. 1)

 */
    public static function getOfficersRatioWeight($aNumberOfCXOs,$aNumConnections) {

        //number of connections = number of connections in same industry for getting weight about same industry
        // number of connections = number of connections in different industry for getting weight about different industry


        $ratio = $aNumberOfCXOs/$aNumConnections;
        $minRangeForRatio = 0;
        $maxRangeForRatio = 1;

        $slope = 1/($maxRangeForRatio-$minRangeForRatio);
        $constant = -($minRangeForRatio)/($maxRangeForRatio-$minRangeForRatio);

        $weight = ($slope * $ratio) + $constant;
        return $weight;
    }
/*
    * We should also consider the number of absolute connections while creating this weight.
    * Assuming that a person with 100+ CXO connections should get a weight of 1 and a person with 0 CXO connections should get a weight of 0.
    * Min_Value = 0 and Max_value = 100
    * 0xw1 + c = 0 and 100xw1 + c = 1
    * Solving these equations: c = 0 and w1 = 1/100  = 0.01
    * Therefore, linear equation will be weight = 0.01 * (number of CXO connections)  ---- 2)
*/
    public static function getOfficersAbsoluteWeight($aNumberOfCXOs) {

        $minRangeForAbsoluteNumber = 0;
        $maxRangeForAbsoluteNumber = 100;

        $slope = 1/($maxRangeForAbsoluteNumber-$minRangeForAbsoluteNumber);
        $constant = -($minRangeForAbsoluteNumber)/($maxRangeForAbsoluteNumber-$minRangeForAbsoluteNumber);

        $weight = ($slope * $aNumberOfCXOs) + $constant;
        return $weight;
    }
}


