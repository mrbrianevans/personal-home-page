<?php
if(str_replace("www.", "", parse_url($_SERVER["HTTP_REFERER"], PHP_URL_HOST))==="brianevans.tech"){
    ?>

    <p class="smallhead">Usage statistics </p>
    <div id="uploadsGraph">Number of uploads each day</div>

    <?php
}else echo "Not authorised";