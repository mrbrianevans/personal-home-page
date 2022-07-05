<?php
?>
    <div class="flex row wrap">
        <div class="bordered full">
            <div class="white blockHeader">
                Search
            </div>
            <div class="margin">
                <h3><label for="search-by-name-box">Find a person by name</label></h3>
                <form>
                    <input name="name" class="yellow material-text-box" placeholder="type here..." autocomplete="off" list="all-people" id="search-by-name-box"/>
                    <button type="submit" name="action" value="search-by-name" class="yellow">Search</button>
                </form>
            </div>
        </div>
        <?php
        $action = "Add";
        require "personUpdateView.php" // form to add a new person
        ?>
        <div class="bordered half">
            <div class="white blockHeader">
                Edit
            </div>
            <div class="margin">
                <h3>Bulk add sons</h3>
                <form>
                    <label>Father: <input name="id" list="fathers"/></label> <br>
                    <label>Mother: <input name="mother" list="mothers"/></label><br>
                    <label>Book reference: <input name="mention"/></label><br>
                    <label>Sons: <br><textarea name="sons"></textarea></label><br>
                    <button type="submit" name="action" value="bulk-add" class="wide yellow">Add</button>
                </form>

            </div>
        </div>
    </div>


<?php
