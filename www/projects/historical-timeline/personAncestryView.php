<?php
if(isset($familyTree) && isset($id)){
    echo "<h3>Family tree of ";
    foreach($familyTree["ancestors"] as $ancestor) if($ancestor["id"]==$id) echo $ancestor["name"];
    echo "</h3>";
    echo "<sup><a href='?action=get&id=$id' class='darker'>$id</a></sup>";
    echo "<ol>";
    foreach ($familyTree["ancestors"] as $layer=>$match){
        ?>
        <li>
            <div style='width: <?=$layer*2?>%; display: inline-block; border-bottom: solid black'></div>
            <div style="display: inline-block" class="fifth" id="<?=$match["id"]?>">
                <a href="?action=ancestry&id=<?= $match["id"] ?>">
                    <button class="full <?=$match["id"]==$id?"selected":"" ?>"><?= $match["name"] ?></button>
                </a>
            </div>
            <?php
            if($match["id"]==$id && isset($familyTree["siblings"])){
                ?>
                <div style='display: inline-block; border-bottom: solid black'>siblings</div>
                <?php
                foreach($familyTree["siblings"] as $id=>$sibling){
                    ?>

                    <div style="display: inline-block" class="fifth" id="<?=$id?>">
                        <a href="?action=ancestry&id=<?= $sibling["id"] ?>">
                            <button class="full"><?= $sibling["name"] ?></button>
                        </a>
                    </div>
                    <?php
                }unset($id, $sibling);
            }
            ?>
        </li>
<?php
    }unset($match);
    if(isset($familyTree["children"])){
        $lineWidth = ($layer+1)*2;
        echo "<li><div style='min-width: $lineWidth%; display: inline-block; border-bottom: solid black'>children</div>";
        foreach($familyTree["children"] as $id=>$child){
            ?>
            <div style="display: inline-block" class="fifth" id="<?=$id?>">
                <a href="?action=ancestry&id=<?= $child["id"] ?>">
                    <button class="full"><?= $child["name"] ?></button>
                </a>
            </div>
            <?php
        }unset($id, $child);
        echo "</li>";
    }

    echo "</ol>";

}else
    echo "Family tree can't display";