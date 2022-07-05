<?php
if(isset($_GET['link'])){
    $rawlink = str_split($_GET['link'], strpos($_GET['link'], ".html"));
    echo preg_replace('/html.*/', 'html?p=DG060032625672202003&custlinkid=851458', $_GET['link']);
}
else if (isset($_GET["add"])) {
    $partName = $_GET["add"];
    $part = array();
    foreach($_GET as $attribute=>$value){
        if($attribute == "add") continue;
        if(is_numeric($value))
            $part[$attribute] = (int)$value;
        else
            $part[$attribute] = $value;
    }
    $file = json_decode(file_get_contents("parts/$partName.json"));
    $file[] = $part;
    file_put_contents("parts/$partName.json", json_encode($file));
}