<?php


class flashcardStorageModel
{
    public function readFlashcardPack($packId)
    {
        if(!$this->packExists($packId)) return false;
        $storedArray = json_decode(file_get_contents("storage/$packId.json"), true);
        $storedArray["metadata"]["accessed"] = time();
        file_put_contents("storage/$packId.json", json_encode($storedArray)); // saves access time
        return $storedArray;
    }
    public function writeNewFlashcardpack($flashcards)
    {
        do{
            $id = "";
            try{
                foreach (range(0, 10,1) as $i){
                    $id .= chr(random_int(97, 121));
                }
                $id .= random_int(0, 1_000_000);
            }catch (Exception $exception){
                return false;
            }
        }while($this->packExists($id));

        $metadata = array(
            "created"=>time(),
            "modified"=>time(),
            "accessed"=>time(),
            "count"=>count($flashcards),
            "id"=>$id
        );
        $writeableArray = array("flashcards"=>$flashcards, "metadata"=>$metadata);
        $bytes = file_put_contents("storage/$id.json", json_encode($writeableArray));
        return $bytes?$id:false;
    }

    public function modifyFlashcardPack($packId, $newFlashcards)
    {
        if(!$this->packExists($packId)) return false;
        $oldFlashcards = $this->readFlashcardPack($packId);
        if($oldFlashcards["flashcards"]!=$newFlashcards["flashcards"]) $oldFlashcards["metadata"]["modified"] = time();
        $oldFlashcards["metadata"]["accessed"] = time();
        $oldFlashcards["metadata"]["count"] = count($newFlashcards["flashcards"]);
        $oldFlashcards["flashcards"] = $newFlashcards["flashcards"];
        $bytes = file_put_contents("storage/$packId.json", json_encode($oldFlashcards));
        return (bool) $bytes;
    }
    public function deleteFlashcardPack($packId){
        return ($this->packExists($packId)) && unlink("storage/$packId.json");
    }

    private function packExists($packId){
        return file_exists("storage/$packId.json");
    }

    public function getListOfPacks(){
        $files = scandir("storage");
        $packs = [];
        foreach($files as $file){
            if(pathinfo($file, PATHINFO_EXTENSION) == "json")
                $packs[] = $this->readFlashcardPack(pathinfo($file, PATHINFO_FILENAME))["metadata"];
        }
        return $packs;
    }
}