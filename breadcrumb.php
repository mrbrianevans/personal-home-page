<?php
if(true){
    $precedingPages = str_replace($_SERVER["DOCUMENT_ROOT"], "", $_SERVER["SCRIPT_FILENAME"]);
    $precedingPages = str_replace("index.php", "", $precedingPages);
    $precedingPages = trim($precedingPages, "/");
    $pageArray = explode("/", $precedingPages);
    $length = count($pageArray)-1;
    // if $pageName is not set, it will just use the filename as the current page name
    $currentPageName = $pageName??ucwords(str_replace("-", " ", $pageArray[array_key_last($pageArray)]));
    unset($pageArray[array_key_last($pageArray)]);
?>
    <h2>&nbsp;&nbsp;

        <?php
        echo '<a class="darker" href="/">Home</a>';
            foreach($pageArray as $dots=>$page){
                $formattedPageName = ucwords(str_replace("-", " ", $page));
                echo "/<a href='".str_repeat("../", $length-$dots)."../$page' class='darker'>$formattedPageName</a>";
            }
        echo "/<a class=\"darker\" href=\"./\" style='text-decoration: none; border-top: 1px dashed #2F302F'>$currentPageName</a>";
            echo "<span style='float: right'><sup><a href='' class='darker'>refresh</a></sup></span>";
        ?>
    </h2>
<?php
}
