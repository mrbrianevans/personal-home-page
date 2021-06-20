<?php // this snippet should be on every page
include($_SERVER['DOCUMENT_ROOT'] . "/visit.php");
$pageName = "CBT App Download";
$data = array(
        "date"=>$date,
        "ip_address"=>$ip_address,
        "name"=>$_SESSION['user'],
        "page"=>"https://brianevans.tech/CBT/APP/DOWNLOAD",
        "previous"=>$previous,
        "session"=>session_id()
        );
?>
<!doctype html>
<html lang="en">
<head>


    <link href="https://www.brianevans.tech/styelsheet.css" media="only screen and (min-width: 769px)" rel="stylesheet"
          type="text/css">
    <link href="https://www.brianevans.tech/mobile_stylesheet.css" media="only screen and (max-width: 768px)"
          rel="stylesheet" type="text/css">
    <link href="https://www.brianevans.tech/images/favicon.ico" rel="icon" type="image/x-icon"/>
    <script src="https://www.brianevans.tech/frontend.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.typekit.net/rwv6npw.css">
    <meta name="author" content="Brian Evans">
    <script src="feedbackSender.js"></script>
    <link rel="stylesheet" href="style.css">

    <title><?= $pageName ?></title>
    <meta name="keywords" content="<?= $pageName ?>">
    <meta name="description" content="<?= $pageName ?>">

</head>

<body>
<header><a href="https://www.brianevans.tech/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">

        <?php
        require $_SERVER['DOCUMENT_ROOT'] . "/breadcrumb.php";
        ?>
        <h3>Release V3.0.0</h3>
        <a href="cbt-android-app-v3.apk" download="cbt.apk">
            <button onclick="countDownload('<?=base64_encode(json_encode($data))?>')">Download</button>
        </a>
        <div>
            <p>
                When you download the app, your browser might give you a warning saying
                <br>
                <span style="border-radius: 4px;background-color: rgba(0, 0, 0, 0.1);padding: 3px">
                    This type of file can harm your device
                </span>
            </p>
            <p>
                When you install the app, you will be prompted by a system warning saying something along the lines of
            </p>
            <div style="border: solid #2F302F; padding: 10px">
                <h4>Blocked by Play Protect</h4>
                <p>
                    Play Protect doesn't recognize this app's developer. Apps from unknown developers can sometimes be
                    unsafe.
                </p>
            </div>
            <p>
                Press <span style="border-radius: 4px;background-color: rgba(0, 0, 0, 0.1);padding: 3px">Install Anyway</span> to continue
            </p>
        </div>
<div>

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
