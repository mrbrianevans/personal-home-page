<?php
if(isset($persons)){
    foreach($persons as $person){
        $name = $person["firstName"] . " " . $person["lastName"];
        $monthSlashYear = str_pad($person["birthMonth"], 2, "0", STR_PAD_LEFT)
            . "/01/" . $person["birthYear"];
        $birthDate = date("F Y", strtotime($monthSlashYear));
        $link = "?action=persons-companies&firstName=".$person["firstName"]."&lastName=".$person["lastName"].
            "&birthMonth=".$person["birthMonth"]."&birthYear=".$person["birthYear"];
        ?>
        <div><a class="darker" href="<?=$link?>"><?=$name?></a> born in <?=$birthDate?></div>
        <?php
    }
}else{
    echo "Person not found";
}