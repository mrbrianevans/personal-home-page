<?php

echo "loaded jcqGradeStatisticsModel.php";

class jcqGradeStatisticsModel
{
    /**
     * @var mixed
     * @depracated variable. Phasing out a single variable approach, moving to an array approach, to read multiple files in
     */
    private array $gradeStatArrays;
    public function __construct()
    {
        echo "__construct jcqGradeStatisticsModel.php";
//        $this->latestGrades = json_decode(file_get_contents("https://brianevans.tech/projects/grades-comparison/json/jcq-2019-a-level-stats-text.json"), true);
        $this->gradeStatArrays["aLevels"]["2019"]["uk"] = json_decode(file_get_contents("json/jcq-2019-a-level-stats-text.json"), true);
        $this->gradeStatArrays["aLevels"]["2019"]["wales"] = json_decode(file_get_contents("json/jcq-2019-a-level-stats-wales.json"), true);
        $this->gradeStatArrays["aLevels"]["2019"]["northernIreland"] = json_decode(file_get_contents("json/jcq-2019-a-level-stats-northern-ireland.json"), true);
        $this->gradeStatArrays["aLevels"]["2019"]["england"] = json_decode(file_get_contents("json/jcq-2019-a-level-stats-england.json"), true);
        $this->gradeStatArrays["aLevels"]["2019"]["england"] = json_decode(file_get_contents("json/jcq-2019-a-level-stats-england.json"), true);
        
        $this->gradeStatArrays["aLevels"]["2020"]["uk"] = json_decode(file_get_contents("json/jcq-2020-a-level-stats-uk.json"), true);
    }
    /**
     * Returns a JSON array of {"subjectName":{"male":0, "female":0}}
     */
    public function getSubjectParticipationByGenderStackedColumnData(){
        $graphData = array();
        foreach($this->gradeStatArrays["aLevels"]["2020"]["uk"] as $subjectName => $genders){
            $graphData[$subjectName]["total"] = 0;
            foreach($genders as $gender=>$genderInfo){
                $graphData[$subjectName][$gender] = $genderInfo["numberSat"];
                $graphData[$subjectName]["total"] += $genderInfo["numberSat"];
            }
        }
        array_multisort(array_column($graphData, "total"), SORT_DESC, $graphData);
        return json_encode($graphData);
    }

    public function getIndividualSubjectGenderParticipation($subjectName)
    {
        $maleTotal = $this->gradeStatArrays["aLevels"]["2020"]["uk"][$subjectName]["male"]["numberSat"];
        $femaleTotal = $this->gradeStatArrays["aLevels"]["2020"]["uk"][$subjectName]["female"]["numberSat"];
        $graphData[$subjectName] = array("male"=>$maleTotal, "female"=>$femaleTotal);
        return json_encode($graphData);
    }

    public function getCumulativeSubjectGradesByGender($subjectName)
    {
        $graphData = array();
        foreach($this->gradeStatArrays["aLevels"]["2020"]["uk"][$subjectName] as $gender=> $genderInfo){
            foreach($genderInfo["grades"] as $grade=>$percentage){
                $graphData[$grade][$gender] = $percentage*$genderInfo["numberSat"];
            }
        }
        return json_encode($graphData);
    }

    public function getNoncumulativeSubjectGradePercentageByGender($subjectName)
    {
        $graphData = array();
        foreach($this->gradeStatArrays["aLevels"]["2020"]["uk"][$subjectName] as $gender=> $genderInfo){
            $previousGradeNumber = 0;
            foreach($genderInfo["grades"] as $grade=>$percentage){
                $graphData[$grade][$gender] += ($percentage-$previousGradeNumber)/100;
                $previousGradeNumber = $percentage;
            }
        }
        return json_encode($graphData);
    }

    public function getPercentOfAllSatByGender($subjectName)
    {
        $male = $this->gradeStatArrays["aLevels"]["2020"]["uk"][$subjectName]["male"]["percentOfAllSat"]/100;
        $female = $this->gradeStatArrays["aLevels"]["2020"]["uk"][$subjectName]["female"]["percentOfAllSat"]/100;
        $graphData = array("male"=>$male, "female"=>$female);
        return json_encode($graphData);
    }

    public function getTotalParticipationByGender()
    {
        $graphData = array();
        foreach($this->gradeStatArrays["aLevels"]["2020"]["uk"] as $subjectName => $genders){
            foreach($genders as $gender=>$genderInfo){
                $graphData[$gender] += $genderInfo["numberSat"];
            }
        }
        return json_encode($graphData);
    }

    public function getTotalParticipationByCountry(){
        $graphData = array();
        foreach($this->gradeStatArrays["aLevels"]["2019"] as $country=>$gradeStatArray){
            foreach($gradeStatArray as $subjectName => $genders){
                foreach($genders as $gender=>$genderInfo){
                    $graphData[$country] += $genderInfo["numberSat"];
                }
            }
        }
        return json_encode($graphData);
    }
    public function getSubjectParticipationByCountry($subjectName){
        $graphData = array();
        foreach($this->gradeStatArrays["aLevels"]["2019"] as $country=>$gradeStatArray){
            $genders = $gradeStatArray[$subjectName];
                foreach($genders as $gender=>$genderInfo){
                    $graphData[$country] += $genderInfo["numberSat"];
                }
        }
        return json_encode($graphData);
    }

    public function getSubjectParticipationByGradeColumnChart()
    {
        $graphData = array();
        foreach($this->gradeStatArrays["aLevels"]["2020"]["uk"] as $subjectName => $genders){
            $graphData[$subjectName]["total"] = 0;
            foreach($genders as $gender=>$genderInfo){
                $graphData[$subjectName]["total"] += $genderInfo["numberSat"];
                $previousPercent = 0;
                foreach($genderInfo["grades"] as $grade=>$percent){
                    $noncumulativePercent = $percent-$previousPercent;
                    $graphData[$subjectName][$grade] += round($noncumulativePercent*$genderInfo["numberSat"]/100);
                    $previousPercent = $percent;
                }
            }
        }
        foreach($graphData as $subjectName=>$stats) if($stats["total"]<5000) unset($graphData[$subjectName]);

        array_multisort(array_column($graphData, "total"), SORT_DESC, $graphData);
        return json_encode($graphData);
    }
}