<?php
function showTextFiles(){
    $textFiles = scandir("text");
    echo "These files are available to read: <ul>";
    foreach($textFiles as $fileName){
        if(preg_match("/^([a-z]+)/i", $fileName, $publisher)){
            $filesize = number_format(filesize("text/$fileName"));
            echo "<li>Read <a href='pdfReader.php?filename=$fileName&publisher=$publisher[1]' class='darker'>$fileName</a> ($filesize bytes) from $publisher[1]</li>";
        }
    }
    echo "</ul>";
}
function printJSONHTML($json){
    $printableJSON = str_replace("{", "{<br/>", $json);
    $printableJSON = str_replace("}", "<br/>}", $printableJSON);
    $printableJSON = str_replace(",", ", <br/>", $printableJSON);
    echo $printableJSON;
}
if(!isset($_GET["publisher"])) {
    echo "Publisher not set<br>";
    showTextFiles();
} elseif (!isset($_GET["filename"])) {
    echo "Filename not set<br>";
    showTextFiles();
}
elseif($_GET["publisher"]==="edexcel"){
    if($subjectAttainmentFile = file_get_contents("text/" . $_GET["filename"])){
        preg_match_all("/^ Subject \. \. \. \. : ([A-Z0-9]{1,5}?(?= )) (.*)/mi", $subjectAttainmentFile, $subjectNamingInfo);
        function castToInteger($stringNumber){
            return (int) $stringNumber;
        }
        function assignGrades($gradeQuantities){
            $gradeQuantities = array_map("trim", $gradeQuantities);
            $gradeQuantities = array_map("castToInteger", $gradeQuantities);
            if(count($gradeQuantities)==7){
                $grades = array("A*", "A", "B", "C", "D", "E", "U");
                return array_combine($grades, $gradeQuantities);
            }elseif(count($gradeQuantities) < 7)
                echo "<br> !! Too few numbers passed to grades assigning function !! <br>";
            elseif(count($gradeQuantities) > 7)
                echo "<br> !! Too many numbers passed to grades assigning function !! <br>";
            return null;
        }

        preg_match_all("/^ (Centre Type[\s\S]*?)(?= JUNE)/m", $subjectAttainmentFile, $subjectGradeInfo);
        for($i=0; $i<count($subjectGradeInfo[1]); $i++){
            $subjectName = (ucwords(strtolower(trim($subjectNamingInfo[2][$i]))));
            $subjectCode = $subjectNamingInfo[1][$i];
            if($subjectName==="Mathematics") $subjectName.=$subjectCode;
            echo "$subjectName with code $subjectCode <br>";

            $subjectGrades = $subjectGradeInfo[1][$i];
            $subjectGrades = explode("\n", $subjectGrades);

            for($j=0; $j < count($subjectGrades); $j++){ //find the row positions, if they exist
                if(strpos($subjectGrades[$j], "Schools")){
                    $schoolsMaleRow = $j;
                }elseif (strpos($subjectGrades[$j], "Further Education")){
                    $furtherMaleRow = $j;
                }elseif (strpos($subjectGrades[$j], "Private and External")){
                    $privateMaleRow = $j;
                }elseif (strpos($subjectGrades[$j], "Other")){
                    $otherMaleRow = $j;
                }
            }
            $masterArray = array();
            if(isset($schoolsMaleRow)){
                $maleGrades = explode(" ", $subjectGrades[$schoolsMaleRow]);
                $femaleGrades = explode(" ", $subjectGrades[$schoolsMaleRow+2]);
                $masterArray[$subjectName]["Schools"]["Male"] = assignGrades(array_slice($maleGrades, 4, 7));
                $masterArray[$subjectName]["Schools"]["Female"] = assignGrades(array_slice($femaleGrades, 3, 7));
            }
            unset($schoolsMaleRow);

            if(isset($furtherMaleRow)) {
                $maleGrades = explode(" ", $subjectGrades[$furtherMaleRow]);
                $femaleGrades = explode(" ", $subjectGrades[$furtherMaleRow+2]);
                $masterArray[$subjectName]["FurtherEducationEstablishments"]["Male"] = assignGrades(array_slice($maleGrades, 5, 7));
                $masterArray[$subjectName]["FurtherEducationEstablishments"]["Female"] = assignGrades(array_slice($femaleGrades, 3, 7));
            }
            unset($furtherMaleRow);
            if(isset($privateMaleRow)){
                $maleGrades = explode(" ", $subjectGrades[$privateMaleRow]);
                $femaleGrades = explode(" ", $subjectGrades[$privateMaleRow+2]);
                $masterArray[$subjectName]["PrivateAndExternalCandidates"]["Male"] = assignGrades(array_slice($maleGrades, 6, 7));
                $masterArray[$subjectName]["PrivateAndExternalCandidates"]["Female"] = assignGrades(array_slice($femaleGrades, 3, 7));
            }
            unset($privateMaleRow);
            if(isset($otherMaleRow)){
                $maleGrades = explode(" ", $subjectGrades[$otherMaleRow]);
                $femaleGrades = explode(" ", $subjectGrades[$otherMaleRow+2]);
                $masterArray[$subjectName]["Other"]["Male"] = assignGrades(array_slice($maleGrades, 4, 7));
                $masterArray[$subjectName]["Other"]["Female"] = assignGrades(array_slice($femaleGrades, 3, 7));
            }
            unset($otherMaleRow);
        }
        $jsonEncodedArray = json_encode($masterArray);
        printJSONHTML($jsonEncodedArray);
        $newFileName = str_replace(".txt", "", $_GET["filename"]);
        if(file_put_contents("json/$newFileName.json", $jsonEncodedArray)){
            echo "JSON array successfully written to json/$newFileName.json";
        }else{
            echo "Failed to write file";
        }
    }else{
        echo "Failure to retrieve subject data from edexel...";
    }
} elseif ($_GET["publisher"] === "jcq") { // This only does UK stats for the moment, not yet doing individual countries
    if($subjectAttainmentFile = file_get_contents("text/" . $_GET["filename"])){
        if(strpos($_GET["filename"], "gcse")){ //different pattern for different courses ie GCSE grades or A Level grades
        $pattern = "^(.+?)(?= Male) Male ([0-9]{1,7}) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+)\n.*\n".
            "Female ([0-9]{1,7}) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+)";
            $subjectAttainmentFile = str_replace("\r", "", $subjectAttainmentFile);
            preg_match_all("/$pattern/m", $subjectAttainmentFile, $subjectScannedInfo);
            $numberOfSubjectsInFile = count($subjectScannedInfo[0]);
            if($numberOfSubjectsInFile==0) echo "Failure to read GCSE subjects in file<br>";
            $masterArray = array();

            for($subjectId=0;$subjectId<$numberOfSubjectsInFile;$subjectId++){
                $subjectName = $subjectScannedInfo[1][$subjectId];
                $masterArray[$subjectName]["male"]["numberSat"] = (int) $subjectScannedInfo[2][$subjectId];
                $masterArray[$subjectName]["female"]["numberSat"] = (int) $subjectScannedInfo[8][$subjectId];

                $masterArray[$subjectName]["male"]["percentofAllSat"] = (float) $subjectScannedInfo[3][$subjectId];
                $masterArray[$subjectName]["female"]["percentofAllSat"] = (float) $subjectScannedInfo[9][$subjectId];

                //male grades:
                $masterArray[$subjectName]["male"]["grades"]["7/A"] = (float) $subjectScannedInfo[4][$subjectId];
                $masterArray[$subjectName]["male"]["grades"]["4/C"] = (float) $subjectScannedInfo[5][$subjectId];
                $masterArray[$subjectName]["male"]["grades"]["1/G"] = (float) $subjectScannedInfo[6][$subjectId];
                $masterArray[$subjectName]["male"]["grades"]["U"] = (float) $subjectScannedInfo[7][$subjectId];
                //female grades
                $masterArray[$subjectName]["female"]["grades"]["7/A"] = (float) $subjectScannedInfo[10][$subjectId];
                $masterArray[$subjectName]["female"]["grades"]["4/C"] = (float) $subjectScannedInfo[11][$subjectId];
                $masterArray[$subjectName]["female"]["grades"]["1/G"] = (float) $subjectScannedInfo[12][$subjectId];
                $masterArray[$subjectName]["female"]["grades"]["U"] = (float) $subjectScannedInfo[13][$subjectId];
            }
        }elseif(strpos($_GET["filename"], "a-level")){
            preg_match("/[0-9]{4}/", $_GET["filename"], $yearReleased);
            $yearOfResults = $yearReleased[0];
            if($yearOfResults>=2019){ // different layout for A Levels from 2019 onwards
                $pattern = "^(.+?)(?= Male) Male ([0-9]{1,7}) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+)\n.*\n".
                    "Female ([0-9]{1,7}) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+) ([0-9.]+)\n";
            }elseif($yearOfResults<2019){ // more linebreaks before 2019
                $pattern = "^(.+?)\nMale\n([0-9]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)[\s\S]+?".
                    "Female\n([0-9]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)\n([0-9.]+)";
            }
            // procedure for a levels
            $subjectAttainmentFile = str_replace("\r", "", $subjectAttainmentFile);
            preg_match_all("/$pattern/m", $subjectAttainmentFile, $subjectScannedInfo);
            $numberOfSubjectsInFile = count($subjectScannedInfo[0]);
            if($numberOfSubjectsInFile==0) echo "Failure to read subjects in file<br>";
            $masterArray = array();

            for($subjectId=0;$subjectId<$numberOfSubjectsInFile;$subjectId++){
                $subjectName = $subjectScannedInfo[1][$subjectId];
                $masterArray[$subjectName]["male"]["numberSat"] = (int) $subjectScannedInfo[2][$subjectId];
                $masterArray[$subjectName]["female"]["numberSat"] = (int) $subjectScannedInfo[11][$subjectId];

                $masterArray[$subjectName]["male"]["percentofAllSat"] = (float) $subjectScannedInfo[3][$subjectId];
                $masterArray[$subjectName]["female"]["percentofAllSat"] = (float) $subjectScannedInfo[12][$subjectId];

                //male grades:
                $masterArray[$subjectName]["male"]["grades"]["A*"] = (float) $subjectScannedInfo[4][$subjectId];
                $masterArray[$subjectName]["male"]["grades"]["A"] = (float) $subjectScannedInfo[5][$subjectId];
                $masterArray[$subjectName]["male"]["grades"]["B"] = (float) $subjectScannedInfo[6][$subjectId];
                $masterArray[$subjectName]["male"]["grades"]["C"] = (float) $subjectScannedInfo[7][$subjectId];
                $masterArray[$subjectName]["male"]["grades"]["D"] = (float) $subjectScannedInfo[8][$subjectId];
                $masterArray[$subjectName]["male"]["grades"]["E"] = (float) $subjectScannedInfo[9][$subjectId];
                $masterArray[$subjectName]["male"]["grades"]["U"] = (float) $subjectScannedInfo[10][$subjectId];
                //female grades
                $masterArray[$subjectName]["female"]["grades"]["A*"] = (float) $subjectScannedInfo[13][$subjectId];
                $masterArray[$subjectName]["female"]["grades"]["A"] = (float) $subjectScannedInfo[14][$subjectId];
                $masterArray[$subjectName]["female"]["grades"]["B"] = (float) $subjectScannedInfo[15][$subjectId];
                $masterArray[$subjectName]["female"]["grades"]["C"] = (float) $subjectScannedInfo[16][$subjectId];
                $masterArray[$subjectName]["female"]["grades"]["D"] = (float) $subjectScannedInfo[17][$subjectId];
                $masterArray[$subjectName]["female"]["grades"]["E"] = (float) $subjectScannedInfo[18][$subjectId];
                $masterArray[$subjectName]["female"]["grades"]["U"] = (float) $subjectScannedInfo[19][$subjectId];

            }
        }

        $jsonEncodedArray = json_encode($masterArray);
        printJSONHTML($jsonEncodedArray);
        $newFileName = str_replace(".txt", "", $_GET["filename"]);
        if(file_put_contents("json/$newFileName.json", $jsonEncodedArray)){
            echo "JSON array successfully written to json/$newFileName.json";
        }else{
            echo "Failed to write file";
        }
    }else{
        echo "Failure to retrieve text file from joint council for qualifications...";
    }
}else{
    echo "Publisher not recognised. Please choose one from the list: <br>";
    showTextFiles();
}
