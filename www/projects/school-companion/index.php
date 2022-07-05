<?php // this snippet should be on every page
include($_SERVER['DOCUMENT_ROOT'] . "/visit.php");
$pageName = "School companion companion";
?>
<!doctype html>
<html lang="en">
<head>


    <link href="/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet"
          type="text/css">
    <link href="/mobile_stylesheet.css" media="only screen and (max-width: 768px)"
          rel="stylesheet" type="text/css">
    <link href="/images/favicon.ico" rel="icon" type="image/x-icon"/>
    <script src="/frontend.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <meta name="author" content="Brian Evans">
    <link rel="stylesheet" href="style.css">

    <title><?= $pageName ?></title>
    <meta name="keywords" content="<?= $pageName ?>">
    <meta name="description" content="<?= $pageName ?>">

</head>

<body>
<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">

        <?php
        require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
        ?>

        <p>
            This is the server that helps run the school-companion React app by storing and serving flashcard packs.
        </p>
        <p>
            To see more about this project, visit the GitHub page:
            <a href="https://github.com/mrbrianevans/school-companion" target="_blank"><button>GitHub repo</button></a>
        </p>
        <a href="../converters/csv-json/flashcards"><button>Upload CSV</button></a>
        <h3>Flashcard storage API</h3>
        <p>This is the specification for the API used to store flashcard packs remotely. The API has the CRUD operations: create, read, update and delete</p>
        <ol>
            <li>
                <h4>Create</h4>
                <p>Use POST request with JSON body of the list of flashcards in the pack</p>
            </li>
            <li>
                <h4>Read</h4>
                <p>Use GET request with packId request variable set</p>
            </li>
            <li>
                <h4>Update</h4>
                <p>Use PUT request with JSON body of the list of updated flashcards and metadata</p>
            </li>
            <li>
                <h4>Delete</h4>
                <p>Use DELETE request with packId request variable set</p>
            </li>
        </ol>
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

    <div class="column"><a href="/sitemap.php" style="text-decoration: none">Site map</a>
    </div>

    <div class="column"><a href="/contact/index.php" style="text-decoration: none">Contact
            me</a></div>

</footer>
</body>
</html>
