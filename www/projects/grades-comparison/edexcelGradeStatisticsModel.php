<?php

/**
 * Class edexcelGradeStatisticsModel CAUTION: This class uses Male and Female rather than male and female.
 * If graphing with this model, you need to change the JavaScript to match uppercase genders
 */
class edexcelGradeStatisticsModel
{
    private $gradeStatisticsArray;

    public function __construct()
    {
        $this->gradeStatisticsArray = json_decode(file_get_contents("json/edexcelALevel2019.json"), true);
    }
    /**
    * Returns a JSON array of {"subjectName":{"male":0, "female":0}}
    */
    public function getSubjectParticipationByGenderStackedColumnData(){
        $graphData = array();
        foreach($this->gradeStatisticsArray as $subjectName=>$centerTypes){
            $maleTotal = 0;
            $femaleTotal = 0;
            foreach($centerTypes as $centerType=>$genders){
                $maleTotal += $genders["Male"]["U"];
                $femaleTotal += $genders["Female"]["U"];
            }
            $graphData[$subjectName] = array("male"=>$maleTotal, "female"=>$femaleTotal, "total"=>$maleTotal+$femaleTotal);
        }
        array_multisort(array_column($graphData, "total"), SORT_DESC, $graphData);
        return json_encode($graphData);
    }

    public function getIndividualSubjectGenderParticipation($subjectName)
    {
        $maleTotal = 0;
        $femaleTotal = 0;
        foreach($this->gradeStatisticsArray[$subjectName] as $centerType=>$genders){
            $maleTotal += $genders["Male"]["U"];
            $femaleTotal += $genders["Female"]["U"];
        }
        $graphData[$subjectName] = array("male"=>$maleTotal, "female"=>$femaleTotal);
        return json_encode($graphData);
    }

    public function getCumulativeSubjectGradesByGender($subjectName)
    {
        $graphData = array();
        foreach($this->gradeStatisticsArray[$subjectName] as $centerType=>$genders){
            foreach($genders as $gender=>$grades){
                foreach($grades as $grade=>$number){
                    $graphData[$grade][$gender] += $number;
                }
            }
        }
        return json_encode($graphData);
    }

    public function getNoncumulativeSubjectGradePercentageByGender($subjectName)
    {
        $graphData = array();
        $population = array("Male"=>0, "Female"=>0);
        foreach($this->gradeStatisticsArray[$subjectName] as $centerType=>$genders){
            foreach($genders as $gender=>$grades){
                $population[$gender] += $grades["U"];
                $previousGradeNumber = 0;
                foreach($grades as $grade=>$number){
                    $graphData[$grade][$gender] += $number-$previousGradeNumber;
                    $previousGradeNumber = $number;
                }
            }
        }
        unset($centerType, $genders, $gender, $grades, $grade, $number, $previousGradeNumber); // clear memory
        //adjust nominal numbers to a percentage of population:
        foreach($graphData as $grade=>$genders){
            foreach($genders as $gender=>$number){
                $graphData[$grade][$gender] = $number/$population[$gender];
            }
        }
        return json_encode($graphData);
    }
}