<label for="pagesbyipdropdown">IP ADDRESS: </label>
<select id="pagesbyipdropdown" oninput="pagesbyip()">
    <?php
    foreach($ips as $visitor => $visits){
    ?>

        <option value="<?=$visitor?>"><?=$visitor?></option>

    <?php
} ?>
</select>
<p class="smallhead">Traffic by IP address</p>
<ol>
<?php

foreach($ips as $visitor => $visits){
	?>

	<li>IP ADDRESS: <?=$visitor ?>  VISITS: <?=$visits ?></li>

<?php
} ?>
</ol>
<hr>
<p class="smallhead">Traffic by page </p>
<ol>
<?php

foreach($pages as $page => $visits){
	?>

	<li>Webpage: <?=$page ?>  VISITS: <?=$visits ?></li>

<?php
} ?>
</ol>