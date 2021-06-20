<?php // this snippet should be on every page
$root = $_SERVER['DOCUMENT_ROOT'];
require("$root/visit.php");
?>
<!doctype html>
<html lang="en">
<head>

    <link href="project_gallery_styling.css" rel="stylesheet" type="text/css">
    <link href="https://www.brianevans.tech/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet"
          type="text/css">
    <link href="https://www.brianevans.tech/mobile_stylesheet.css" media="only screen and (max-width: 768px)"
          rel="stylesheet" type="text/css">
    <link href="/images/favicon.ico" rel="icon" type="image/x-icon"/>
    <script src="https://www.brianevans.tech/frontend.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <meta name="author" content="Brian Evans">


    <title>Project page</title>
    <meta name="keywords" content="coding projects, brian evans website projects">
    <meta name="description"
          content="These are some projects I have done over the last couple months. Some of them are related to my computer science degree, and some are just interesting.">

</head>

<body>
<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="lightpink" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="box single pink">

        <?php
        require_once "$root/breadcrumb.php";
        ?>
        <p>
            For a full sitemap, visit <a href="../sitemap.php/index.php">human readable sitemap</a>
        </p>
        <div class="project_gallery pink">
            <?php
            $listOfProjects = array();
            foreach(scandir("../projects") as $projectDirectoryName){
                if(is_dir($projectDirectoryName) && $projectDirectoryName!="." && $projectDirectoryName!="..") {
                    $printableName = ucwords(str_replace("-", " ", $projectDirectoryName));
                    if (strlen($printableName) == 3) $printableName = strtoupper($printableName);
                    $modified = filemtime("$projectDirectoryName/index.php");
                    $modified = filectime("$projectDirectoryName/index.php");
                    $listOfProjects[$projectDirectoryName] = array("name" => $printableName, "lastModified" => $modified, "status" => "incomplete",
                                            "visible" => false, "href"=>$projectDirectoryName);
                }
            }
            $visibleProjects = array(
                "collatz",
                "companies-house",
                "cookies",
                "fpv",
                "gcd",
                "grades-calculator",
                "grades-comparison",
                "historical-timeline",
                "list",
                "projectile",
                "t9autocomplete",
                "ons",
                "fibonacci",
                "encryption"
            );
            $completeProjects = array(
                "collatz",
                "projectile",
                "gcd"
            );
            $tooltipModifiedCompleted = array("complete"=>"Completed on ", "incomplete"=>"Last updated on ");
            $tooltipMessage = array("complete"=>"Project finished", "incomplete"=>"Project under development");
            foreach($visibleProjects as $project) if(isset($listOfProjects[$project]))$listOfProjects[$project]["visible"] = true;
            foreach($completeProjects as $project) if(isset($listOfProjects[$project]))$listOfProjects[$project]["status"] = "complete";
            foreach($listOfProjects as $project){
                if($project["visible"]){
?>
                    <div class="project_item padded">
                        <a class="project_link" href="<?=$project["href"]?>">
                            <div class="innerLink">
                                <?=$project["name"]?>
                                <div class="status <?=$project["status"]?>">
                                    <span class="statusTooltip">
                                        <?=$tooltipMessage[$project["status"]]?> <br>
                                        <?=$tooltipModifiedCompleted[$project["status"]]?> <?=date("j M Y", $project["lastModified"])?>
                                    </span>
                                </div>
                            </div>

                        </a>

                    </div>
                    <?php
                }
            }
            ?>
        </div>

    </div>
</div>

<footer>
    <div class="column">Connect with me on <a href="https://www.linkedin.com/in/brianevanstech">LinkedIn</a></div>

    <div class="column">Find me on <a href="https://www.behance.net/brianevanstech">Behance</a></div>

    <div class="column">Follow me on <a href="https://github.com/mrbrianevans">GitHub</a></div>

    <div class="blankline">
        <hr>
    </div>

    <div class="column">&copy; Brian Evans <?= date("Y") ?></div>

    <div class="column"><a href="https://www.brianevans.tech/sitemap.php" style="text-decoration: none">Site map</a>
    </div>

    <div class="column"><a href="https://www.brianevans.tech/contact/index.php" style="text-decoration: none">Contact
            me</a></div>

</footer>
</body>
</html>
