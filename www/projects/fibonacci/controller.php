<?php
if (isset($_GET["term"])) {
    $term = $_GET["term"];
    ?>
    <h3>Term <?=$term?> of the Fibonacci sequence</h3>
    <?php
    require_once "FibonacciModel.php";
    if($term < 36){
    ?>
        <h3>Typical recursion method: </h3>
    <?php
        $startTime = microtime(true);
        echo number_format(FibonacciModel::calculateFibonacciTerm($term));
        $timeTaken = number_format((microtime(true) - $startTime)*1000, 3);
        echo "<br><sub>$timeTaken milliseconds taken to calculate</sub>";
    }

    ?>
    <h3>More efficient method (using storage): </h3>
    <?php
    $startTime = microtime(true);
    $fibModel = new FibonacciModel();
    echo number_format($fibModel->calculateFibonacciWithSaves($term));
    $timeTaken = number_format((microtime(true) - $startTime)*1000, 3);
    echo "<br><sub>$timeTaken milliseconds taken to calculate</sub>";
}else{
    ?>
    <form>
        <label for="term-slider">Choose a term from the slider</label>
        <br>
        <input type="range" min="0" max="100" value="20" name="term" id="term-slider"/>
        <button type="submit">Calculate</button>
    </form>
    <form>
        <label for="term-textbox">Or type a term</label>
        <br>
        <input type="text" name="term" id="term-textbox"/>
        <button type="submit">Calculate</button>
    </form>
    <p>The inefficient algorithm can only calculate up to 35 terms. The efficient algorithm is accurate up to the 78th term</p>
    <?php
}

