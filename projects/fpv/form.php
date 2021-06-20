<?php
?>
<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
    <label for="motorSize">Motor size: </label><input type="text" placeholder="2308" id="motorSize" name="motorSize"/>
    <label for="motorKV">Motor KV: </label><input type="text" placeholder="1950" id="motorKV" name="motorKV"/>
    <label for="motorBrand">Motor brand: </label><input type="text" placeholder="Diatone" id="motorBrand" name="motorBrand"/> <br>
    <label for="escAmps">ESC amp rating: </label><input type="text" placeholder="60" id="escAmps" name="escAmps"/>
    <label for="escBrand">ESC brand: </label><input type="text" placeholder="HobbyWing" id="escBrand" name="escBrand"/> <br>
    <label for="dryWeight">Dry weight: </label><input type="text" placeholder="560" id="dryWeight" name="dryWeight"/> (grams) <br>
    <label for="throttleHover">Throttle to hover: </label><input type="text" placeholder="35" id="throttleHover" name="throttleHover"/> <br>
    <input type="submit" value="View insights" name="new"/>
</form>
