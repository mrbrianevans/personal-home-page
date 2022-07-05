<?php
if (isset($words)) {
    foreach ($words as $word){
        echo "<li>";
        print_r($word);
        echo "</li>";
    }
}else{
    echo "No matching words found";
}
