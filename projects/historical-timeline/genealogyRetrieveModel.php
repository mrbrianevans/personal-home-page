<?php


class genealogyRetrieveModel
{
    private array $genealogyArray;
    public function __construct()
    {
        $this->genealogyArray = json_decode(file_get_contents("genealogy.json"), true);
    }
    public function __destruct()
    {
    }

    public function getAncestors($id){
        $implicatedPersons = [];
        $layerOfOnion = 0;
        if(!isset($this->genealogyArray[$id])) return false;
        $personInQuestion = $this->genealogyArray[$id];
        do{
            $implicatedPersons[$layerOfOnion++] = $personInQuestion;
            if ($hasFather=strlen($personInQuestion["father"]))
                $personInQuestion = $this->genealogyArray[$personInQuestion["father"]];
        }while($hasFather);

        $implicatedPersons = array_reverse($implicatedPersons);

        $personInQuestion = $this->genealogyArray[$id];
        do{
            $children = $this->getImmediateChildren($personInQuestion["id"]);
            if ($hasSingleChild = (is_array($children) && (count($children)==1))) {
                $personInQuestion = array_values($children)[0];
                $implicatedPersons[$layerOfOnion++] = $personInQuestion;
            }
        }while($hasSingleChild);
        return $implicatedPersons;
    }

    public function getDescendants($id){
        // needs to be recursive for each child in a tree style structure
        $implicatedPersons = [];
        $layerOfOnion = 0;
        if(!isset($this->genealogyArray[$id])) return false;
        $personInQuestion = $this->genealogyArray[$id];
        do{
            $implicatedPersons[$layerOfOnion++] = $personInQuestion;
            if ($hasChildren=strlen($personInQuestion["father"]))
                $personInQuestion = $this->genealogyArray[$personInQuestion["father"]];
        }while($hasChildren);
        return array_reverse($implicatedPersons);
    }

    public function getImmediateChildren($fatherId)
    {
        $parentGender = $this->genealogyArray[$fatherId]["gender"]=="male"?"father":"mother";
        foreach($this->genealogyArray as $id=>$person){
            if($person["$parentGender"]==$fatherId){
                $children[$id] = $person;
            }
        }
        return $children??false;
    }

    public function getSiblings($siblingId){
        $fatherId = $this->genealogyArray[$siblingId]["father"];
        foreach($this->genealogyArray as $id=>$person){
            if($id == $siblingId) continue;
            if($person["father"]==$fatherId && $fatherId != ""){
                $siblings[$id] = $person;
            }
        }
        return $siblings??false;
    }

    public function getLastKnownDescendants($furthestId){
        $implicatedPersons = [];
        $layerOfOnion = 0;
        if(!isset($this->genealogyArray[$furthestId])) return false;
        do{
            $hasChildren = false;
            foreach($this->genealogyArray as $id=>$person){
                if($person["father"]==$furthestId){
                    $implicatedPersons[$layerOfOnion][] = $id;
                    $hasChildren = true;
                }
            }
        }while($hasChildren);
        return array_reverse($implicatedPersons);
    }

    public function searchByName($name){
        // using $results[$id] automatically filters out duplicates
        foreach($this->genealogyArray as $id=>$person) if(!strcasecmp($person["name"],$name)) $results[$id] = $person;
        foreach($this->genealogyArray as $id=>$person) if(preg_match("/^$name/i",$person["name"])) $results[$id] = $person;
        foreach($this->genealogyArray as $id=>$person) if(preg_match("/$name/i",$person["name"])) $results[$id] = $person;
        foreach($this->genealogyArray as $id=>$person) if(preg_match("/".$person["name"]."/i",$name)) $results[$id] = $person;
        return $results??false;
    }

    public static function getPersonById($id)
    {
        $genealogyArray = json_decode(file_get_contents("genealogy.json"), true);
        return $genealogyArray[$id]??false;
    }

    public function getName($id){
        $person = $this->genealogyArray[$id];
        $name = $person["name"];
        $fathersName = $this->genealogyArray[$person["father"]]["name"];
        $mothersName = $this->genealogyArray[$person["mother"]]["name"];
        $gender = $person["gender"];
        $sonOrDaughter = $gender=="male"?"son":"daughter";
        $parent = strlen($fathersName)?$fathersName:$mothersName;
        $finalStatement = strlen($fathersName.$mothersName)?"$name, $sonOrDaughter of $parent":$name;
        return $finalStatement;
    }
    public function getListOfPotentialFathers(){
        foreach($this->genealogyArray as $personId=>$person)
            if(!strcasecmp($person["gender"],"male"))
                $results[$personId] = $this->getName($personId);
        return $results??false;
    }
    public function getListOfPotentialMothers(){
        foreach($this->genealogyArray as $personId=>$person)
            if(!strcasecmp($person["gender"],"female"))
                $results[$personId] = $this->getName($personId);
        return $results??false;
    }

    public function getAllPeople()
    {
        foreach($this->genealogyArray as $personId=>$person){
            $results[$person["name"]] = $this->getName($personId);
        }
        return $results??[];
    }
}