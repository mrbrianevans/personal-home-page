<?php
if(isset($match)){

    ?>

    <div class="bordered padded half">
        <h3><?=$match["name"]?></h3>
        <sub><?=$match["id"]?></sub>
        <p>Gender: <?=$match["gender"]?></p>
        <p>From: <?=$match["place"]?></p>
        <p>Father: <a href="?action=ancestry&id=<?=$match["father"]?>" class="darker">
                <?=$getModel->getPersonById($match["father"])["name"]?>
            </a></p>
        <p>Mother: <a href="?action=ancestry&id=<?=$match["mother"]?>" class="darker">
                <?=$getModel->getPersonById($match["mother"])["name"]?>
            </a></p>
        <p>Siblings: <?php foreach($match["siblings"] as $sibling) echo " $sibling "?></p>
        <p>Other names: <?php foreach($match["otherNames"] as $otherName) echo " $otherName "?></p>
        <p>Spouses: <?php foreach($match["spouses"] as $spouse)
            echo "<a href='?action=search-by-name&name=$spouse' class='darker'> $spouse "?></p>
        <p>Books mentioned in: <?php foreach($match["mentions"] as $mention) echo " $mention "?></p>
        <a href="?action=ancestry&id=<?=$match["id"]?>"><button class="wide yellow">View</button></a>
        <a href="?action=update-form&id=<?=$match["id"]?>"><button class="wide yellow">Edit</button></a>
        <a href="?action=delete&id=<?=$match["id"]?>"><button class="wide delete">Delete</button></a>
    </div>

    <?php
}else{
    echo "<p>Sorry, no match found to display</p>";
}