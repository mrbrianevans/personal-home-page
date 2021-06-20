<?php
?>
<div id="keypadArea">
<table id="keypad" onload="sizeTable()">
    <tr>
        <td onclick="appendNumber(1)">
            <div class="key">
                <div class="numberKey">1</div>
                <div class="lettersKey">. , :</div>
            </div>
        </td>
        <td onclick="appendNumber(2)">
            <div class="key">
                <div class="numberKey">2</div>
                <div class="lettersKey">a b c</div>
            </div>
        </td>
        <td onclick="appendNumber(3)">
            <div class="key">
                <div class="numberKey">3</div>
                <div class="lettersKey">d e f</div>
            </div>
        </td>
    </tr>
    <tr>
        <td onclick="appendNumber(4)">
            <div class="key">
                <div class="numberKey">4</div>
                <div class="lettersKey">g h i</div>
            </div>
        </td>
        <td onclick="appendNumber(5)">
            <div class="key">
                <div class="numberKey">5</div>
                <div class="lettersKey">j k l</div>
            </div>
        </td>
        <td onclick="appendNumber(6)">
            <div class="key">
                <div class="numberKey">6</div>
                <div class="lettersKey">m n o</div>
            </div>
        </td>
    </tr>
    <tr>
        <td onclick="appendNumber(7)">
            <div class="key">
                <div class="numberKey">7</div>
                <div class="lettersKey">p q r s</div>
            </div>
        </td>
        <td onclick="appendNumber(8)">
            <div class="key">
                <div class="numberKey">8</div>
                <div class="lettersKey">t u v</div>
            </div>
        </td>
        <td onclick="appendNumber(9)">
            <div class="key">
                <div class="numberKey">9</div>
                <div class="lettersKey">w x y z</div>
            </div>
        </td>
    </tr>

</table>


<div class="key" id="backspace" onclick="backspace()">
    Backspace
</div>
</div>