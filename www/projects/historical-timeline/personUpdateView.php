<?php
if(!isset($match)){
    // these are default values for adding a new person
    $match = array("name"=>"", "place"=>"", "father"=>"", "mother"=>"", "siblings"=>[],
            "mentions"=>[], "gender"=>"male", "otherNames"=>[], "id"=>"", "spouses"=>[]);
}
    $siblings = implode("\r\n", $match["siblings"]);
    $mentions = implode("\r\n", $match["mentions"]);
    $names = implode("\r\n", $match["otherNames"]);
    $spouses = implode("\r\n", $match["spouses"]);
    ?>
    <div class="bordered half">
        <div class="white blockHeader">
            <?=$action??"Update"?>
        </div>
        <div class="margin">
            <h3><?=$action??"Update"?> person to the genealogy</h3>
            <form>
                <input type="hidden" name="id" value="<?=$match["id"]?>"/>
                <label>Name: <input name="name" placeholder="Moses" tabindex="1" autofocus value="<?=$match["name"]?>" autocomplete="off"/> </label><br>
                <label>From: <input name="place" placeholder="Gershon" tabindex="2" value="<?=$match["place"]?>"/> </label><br>
                <label>Father: <input name="father" placeholder="Kohath" tabindex="3" list="fathers" value="<?=$match["father"]?>"/> </label><br>
                <label>Mother: <input name="mother" placeholder="" tabindex="4" list="mothers" value="<?=$match["mother"]?>"/> </label><br>
                <label>Siblings: <textarea name="siblings" placeholder="Aaron&#13;Mirium" tabindex="5"><?=$siblings?></textarea><br>
                    <label>Spouses: <textarea name="spouses" placeholder="Bathsheba" tabindex="6"><?=$spouses?></textarea>
                    </label><br>
                    <label>Books mentioned in: <textarea name="mentions" placeholder="Exodus&#13;Numbers"
                                                         tabindex="7"><?=$mentions?></textarea> </label><br>
                    <label>Other names: <textarea name="names" placeholder="" tabindex="8"></textarea> </label><br>
                    <label>Male: <input name="gender" tabindex="10" type="radio" value="male" <?=$match["gender"]=="male"?"checked":""?>/> </label>
                    <label>Female: <input name="gender" tabindex="11" type="radio" value="female" <?=$match["gender"]=="female"?"checked":""?>/> </label><br>
                    <button type="submit" name="action" value="update" tabindex="9" class="wide yellow"><?=$action??"Update"?></button>
            </form>
        </div>
    </div>

