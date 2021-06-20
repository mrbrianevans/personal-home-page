<?php
?>
<form>
    <label>Increment a word: <input placeholder="architect" name="word" autocomplete="off"/></label>
</form>
<form>
    <label>Predict a word: <input placeholder="2566" name="partial" autocomplete="off"/></label>
</form>
<form method="post">
    <label>Paste a large body of text to process: <br>
        <textarea name="paragraph" placeholder="paste text here"></textarea>
    </label>
    <button type="submit">Process</button>
</form>

<a href="?view=full"><button>View full database</button></a>