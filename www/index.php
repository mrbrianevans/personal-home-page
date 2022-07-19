<?php
include_once "../src/Projects.php";
$p = new ProjectsService();
$projects = $p->listProjects(true);
?>

<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="index_styling.css"/>
    <title>Brian Evans</title>
</head>
<body>
<main>
    <article class="grid">

        <div id="page-title">
            <header><h1>Brian Evans</h1></header>
            <p>Welcome to my website. I'm a software developer focused on web development with JavaScript frontend and backend.</p>
            <p>Take a look at some of my projects listed here or
                view some source code on my <a href="https://github.com/mrbrianevans">GitHub profile</a>.</p>
        </div>

        <div id="profile">
            <img src="./static/me.png">
        </div>

        <div class="project-box-outer">
            <div class="project-box-inner" >
            <h3 class="project-title">Open to offers</h3>
                <p class="project-description">I am currently working on these projects, but I am open to employment offers.</p>
            </div>
        </div>

        <?php foreach ($projects as $project) { ?>
            <a
                href="<?= $project["url"] ?>" class="project-link project-box-outer"
                <?= $project["url"] == "/projects" ? 'id="full-project-directory"' : "" ?>>
                <div class="project-box-inner" >
                    <h3 class="project-title"><?= $project["name"] ?></h3>
                    <p class="project-description"><?= $project["short_description"] ?></p>
<!--                    --><?//= print_r($project["tags"]) ?>
                    <?php foreach ($project["tags"] as $tag){ ?>
                        <span class="project-tag"><?= $tag ?></span>
                    <?php } ?>
                </div>
            </a>
        <?php } ?>
    </article>

    <footer><a href="https://github.com/mrbrianevans">github</a> | <a href="https://www.linkedin.com/in/brianevanstech/">linked in</a> | <a href="contact">contact</a> | <a href="about">about</a> | <a href="https://wakatime.com/@f8dd9b3d-8b67-421e-8f3e-ab941b402e60"><img src="https://wakatime.com/badge/user/f8dd9b3d-8b67-421e-8f3e-ab941b402e60.svg?style=flat-square" alt="Total time coded since Jul 23 2020" /></a></footer>
</main>
</body>
</html>
