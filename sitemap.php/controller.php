<?php
$topLevelFolder = $_SERVER["DOCUMENT_ROOT"];

function scanFolder($folder){
    if(strpos($folder, "price_history")) return ["files"=>"investing"];
    if(strpos($folder, "corpora")) return ["files"=>"corpora"];
    if(strpos($folder, "crawler") or strpos($folder, "dataProductDownload")) return ["files"=>"companies"];
    $children = scandir($folder);
    $childrenFolders = [];
    $childrenFiles = [];
    $reccuranceLimit = 1000_000;
    $reccuranceCounter = 0;
    foreach($children as $child){
        if($reccuranceCounter++==$reccuranceLimit) break;
        if($child == "." || $child == "..") continue; // this is just the above and equal folder. Not named
        $link = str_replace($_SERVER["DOCUMENT_ROOT"], "", "$folder/$child");
        $linkTag = "<a href='$link' class='darker'>$child</a>";
        if(is_dir("$folder/$child")) $childrenFolders[$linkTag] = scanFolder("$folder/$child");
        else $childrenFiles[] = $linkTag;
    }
    $childrenFolders["files"] = $childrenFiles;
    return $childrenFolders;
}

$treeStructure = scanFolder($topLevelFolder);
?>

<style>
    ul{
        list-style: circle;
        margin-left: 15px;
    }
</style>

<?php
function printTreeStructure($array){
    foreach($array["files"] as $file){
        echo "<li>$file</li>";
    }
    unset($array["files"]);
    foreach ($array as $folder=>$subArray) {
        echo "<li>$folder";
        echo "<ul>";
        printTreeStructure($subArray);
        echo "</ul></li>";
    }
}
echo "<ul>";
printTreeStructure($treeStructure);
echo "</ul>";