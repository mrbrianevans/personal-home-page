<?php


class genealogyEditingModel
{
    private array $genealogyArray;
    public function __construct()
    {
        $this->genealogyArray = json_decode(file_get_contents("genealogy.json"), true);
    }
    public function __destruct()
    {
    }

    public function addPersonToInternalArray($name, $place, $father, $mother, $siblings, $mentions, $gender, $otherNames, $spouses){
        $id = $this->generateId($name, $place, $father, $mother, $siblings, $mentions, $gender, $otherNames, $spouses);
        $writeToFile = array(
            "name"=>$name, "place"=>$place, "father"=>$father, "mother"=>$mother, "siblings"=>$siblings,
            "mentions"=>$mentions, "gender"=>$gender, "otherNames"=>$otherNames, "id"=>$id, "spouses"=>$spouses
        );
        $this->genealogyArray[$id] = $writeToFile;
        return $id;
    }
    public function addPerson($name, $place, $father, $mother, $siblings, $mentions, $gender, $otherNames, $spouses)
    {
        $id=$this->addPersonToInternalArray($name, $place, $father, $mother, $siblings, $mentions, $gender, $otherNames, $spouses);
        file_put_contents("genealogy.json", json_encode($this->genealogyArray));
        return $id;
    }

    public function generateId($name, $place, $father, $mother, $siblings, $mentions, $gender, $otherNames, $spouses){
        $inputString = $name . $place . $father. $mother . implode("", $siblings) . implode("", $mentions)
            . $gender . implode("", $otherNames) . implode("", $spouses);
        $id = hash("sha256", $inputString);
        return $id;
    }
    public function updatePersonDetails($oldId, $name, $place, $father, $mother, $siblings, $mentions, $gender, $otherNames, $spouses){
        $newId = $this->addPersonToInternalArray($name, $place, $father, $mother, $siblings, $mentions, $gender, $otherNames, $spouses);
//        echo "Old ID: $oldId , New Id: $newId";
        if($newId==$oldId) return false; // no updates have been made, because ID is equal
        if(isset($this->genealogyArray[$newId])){
            unset($this->genealogyArray[$oldId]);
            $newFileToWrite = str_replace("$oldId", $newId, json_encode($this->genealogyArray));
            if(file_put_contents("genealogy.json", $newFileToWrite))
                return $newId;
            else return false;
        }else{
            return false;
        }
    }

    /**
     * This is for adding multiple sons of a person, as this is a common use case
     * @param string $fathersId The ID of the father in common
     * @param array $sons A list of the sons such as ['Moses', 'Aaron']
     * @param $bookReference string The reference of where in the Bible their names are mentioned
     * @param string $motherId The ID of the mother in common (optional)
     * @return array
     */
    public function addMultipleSons($fathersId, $sons, $bookReference, $motherId=""){
        $addedChildren = [];
        foreach($sons as $son){
            $addedChildren[$son]=$this->addPersonToInternalArray($son, "", $fathersId, $motherId,
                array_diff($sons, [$son]), [$bookReference], "male", [], []);
//            echo "<li>";
//            print_r($this->genealogyArray[$addedChildren[$son]]);
//            echo "</li>";
        }
        file_put_contents("genealogy.json", json_encode($this->genealogyArray));
        return $addedChildren;
    }
    public function deletePerson($id)
    {
        unset($this->genealogyArray[$id]);
        if(file_put_contents("genealogy.json", json_encode($this->genealogyArray)))
            return true;
        else return false;
    }
}