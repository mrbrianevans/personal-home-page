<?php // this snippet should be on every page
include($_SERVER['DOCUMENT_ROOT'] . "/visit.php");
$pageName = "Curriculum Vitae";
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
    <link rel="stylesheet" href="../aboutPageStyles.css"/>
    <script src="../../googleChartsLocal.js" rel="script" type="text/javascript"></script>
    <script src="subjectGrapher.js" rel="script" type="text/javascript"></script>
    <title><?= $pageName ?></title>
    <meta name="keywords" content="<?= $pageName ?>">
    <meta name="description" content="<?= $pageName ?>">

</head>

<body>
<header><a href="/index.php" style="text-decoration: none"><h1 class="orange" id="brian">
            Brian Evans</h1></a></header>
<div class="mainbody">
    <div class="singlebox">

        <h2>&nbsp; <a class='darker' href="<?= $_SERVER['SCRIPT_URI'] ?>">
                <?= $pageName ?>
            </a></h2>
        <p>
            Thanks for checking out my CV!
            This page is a directory to help you find what you're looking for in my portfolio of programming work.
            Hover over a project to see a preview (tap on mobile)
        </p>
    <p class="smallhead">Programming projects:</p>
        <div class="projectsContainer">
            <div class="projectType">
                <span class="projectTypeHeader">PHP and JavaScript</span>
                <div class="project">
                    <div class="projectHeader">Grades comparison charts</div>
                    <?php
                    require "../../projects/grades-comparison/jcqGradeStatisticsModel.php";
                    $jcqModelTiny = new jcqGradeStatisticsModel();
                    $subjectParticipationByGender = $jcqModelTiny->getSubjectParticipationByGenderStackedColumnData();
                    ?>
                    <script type="text/javascript">
                        let data = JSON.parse('<?=$subjectParticipationByGender?>');
                    </script>
                    A visualisation dashboard of A Level grades across different subjects in the UK.
                    <br>To explore the project, visit <a class="darker" href="../../projects/grades-comparison">the dashboard</a>
                    <div class="projectTooltip" id="graphTooltip"><a class="nolink" href="../../projects/grades-comparison"><div id="gradesChartContainer"></div></a>  </div>
                </div>
                <div class="project">
                    <div class="projectHeader">Investment management</div>
                    In this project, I designed and coded an investment tool which tracks a users share portfolio over time.
                    <br>
                    To see the tool in action, visit the <a class="darker" href="../../investing">investing  page</a>
                    <div class="projectTooltip"><img src="images/exampleStocksGraph.PNG" alt="Screenshot from investing website tool"></div>
                </div>
            </div>

            <div class="projectType">
                <span class="projectTypeHeader">Python</span>
                <div class="project">
                    <div class="projectHeader">Elevator</div>
                    In this project, I designed a simulator for an elevator(lift) to find the most efficient way for it to operate. <br>
                    To watch the simulation running, visit the <a target="_blank" href="https://www.youtube.com/watch?v=Seuzg6lI1j0" class="darker">YouTube video</a>,
                    and to see the code behind it, visit the <a target="_blank" href="https://github.com/mrbrianevans/elevator" class="darker">GitHub repo</a>.
                    To see some of the details about the simulation, view the <a
                            href="https://github.com/mrbrianevans/elevator/blob/master/README.md" class="darker"
                            target="_blank">README</a>
                    <div class="projectTooltip"><img src="images/simulation-example.PNG" alt="Screenshot of elevator simulation running"></div>
                </div>
                <div class="project">
                    <div class="projectHeader">Percolation</div>
                    In this project, I designed a simulation for water percolating (filtering) through rocks, replicating what happens when it rains.<br>
                    To view the source code, visit the <a target="_blank" class="darker" href="https://github.com/mrbrianevans/percolation/blob/master/Readme.md">GitHub repo</a> and to see an example of a simulation, watch the
                    <a href="https://www.youtube.com/watch?v=y8m9elMrCrw" class="darker" target="_blank">video on YouTube</a>.
                    <div class="projectTooltip"><img src="images/percolationGIF.gif" alt="Picture of percolation simulation"></div>
                </div>
                <div class="project">
                    <div class="projectHeader">Projectile modelling</div>
                    In this project I modelled the path of a projectile using the equations of motion (SUVAT), given an initial projection velocity. <br>
                    To see more details about this project, visit the <a class="darker" href="/projects/projectile">project page</a>
                    or to see the source code, visit the <a href="https://mrbrianevans.github.io/projectile-modeling/" class="darker" target="_blank">GitHub Page</a>.
                    <div class="projectTooltip"><img src="/images/projectile/projectileALLa30v001s.png" alt="Graph of projectile path"></div>
                </div>
            </div>
            <div class="projectType">
                <span class="projectTypeHeader">Java</span>
                <div class="project">
                    <div class="projectHeader">List objects</div>
                    In this project, I programmed an object in Java which can be used to store an unspecified number of elements.<br>
                    To view more details about the project, visit the <a class='darker' href="/projects/list">project page</a> and to see the code behind it, visit the
                    <a target="_blank" href="https://github.com/mrbrianevans/java-lists" class="darker">GitHub repo</a>.
                    <div class="projectTooltip">
                        <div class="code large">
                            <code>
                                <span class="py">public void</span> <span class=def>add</span>(<span class="py">int</span> value) { <br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class=py>if</span> (items <span class=py>>=</span> <span class=func>arr.length</span>){<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="py">int</span> [] tempArr <span class=py>= new int</span>[<span class=func>arr.length</span>*<span class=num>2</span>]<span class=py>;</span><span class=comment> //create a new array, double the size</span><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;System.<em>arraycopy</em>(<span class=func>arr</span><span class=py>, </span><span class=num>0</span><span class=py>, </span>tempArr<span class=py>, </span><span class=num>0</span><span class=py>, </span><span class=func>arr.length</span>)<span class=py>;</span> <span class=comment> //copy arr to tempArr</span><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class=func>arr</span> = tempArr<span class=py>;</span><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;}<br>
                                &nbsp;&nbsp;&nbsp;&nbsp;<span class=func>arr</span>[<span class=func>items</span>++] = value<span class=py>;</span><span class=comment> //increment items counter and assign value</span><br>
                                }
                            </code>
                        </div>
                    </div>
                </div>
                <div class="project">
                    <div class="projectHeader">Strassen matrix</div>
                    In this project, I wrote a script that uses the Strassen method of matrix multiplication to more
                    efficiently multiply matrices which have a size of a power two, for example multiplying two 4x4 matrices together. <br>
                    To view the code, visit the <a target="_blank" class="darker" href="https://github.com/mrbrianevans/strassen-matrix/tree/master/docs">GitHub repo</a>.
                    <div class="projectTooltip"><img src="images/strassenScreenshot.PNG" alt="Screenshot of code on GitHub"/> </div>
                </div>
                <div class="project">
                    <div class="projectHeader">Colour pallet</div>
                    In the project, I designed a Colour pallet generator, inspired by <a target="_blank" class="darker" href="https://color.adobe.com">Adobe Color</a>. <br>
                    I made the UI using Swing in Java. To view the code, visit the <a target="_blank" class="darker" href="https://github.com/mrbrianevans/colour-palette">GitHub repo</a>.
                    <div class="projectTooltip"><img src="images/colourPaleteScreenshot.png" alt="Screenshot of colour pallet program running in Windows"/> </div>
                </div>
            </div>

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

    <div class="column"><a href="/sitemap.php" style="text-decoration: none">Site map</a>
    </div>

    <div class="column"><a href="/contact/index.php" style="text-decoration: none">Contact
            me</a></div>

</footer>
</body>
</html>
