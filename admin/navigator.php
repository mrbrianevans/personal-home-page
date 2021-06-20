<?php
// sections to show:
// a table of usernames with total number of visits and visits in the last week
// each of them is clickable to view only the visits by that user
//
// same for IP addresses
//
// better contact message display (make it look like email) with links for their session
//
// total traffic for each page, and traffic in the last week, with a link to view the most recent sessions that include that page
//
// in the model, make location function take $response as an arg, and get the map data from that, to have a map for each query
//
// have a summary of sessions like number of pages visited, length of session,

if(isset($ips)&&isset($users)&&isset($pages)) {
    ?>

    <a href="investing">
        <button>Investing</button>
    </a>
    <a href="?page=CBT/APP/DOWNLOAD">
        <button>App downloads</button>
    </a>
    <a href="?page=predictions">
        <button>Predictions</button>
    </a>
    <a href="?recent=">
        <button>Recent traffic</button>
    </a>

    <h3>Site traffic</h3>
    <div class="tri-container">
        <div class="tri-item">
            <h4>Traffic by IP Address </h4>
            <form>
                <label>Search for ip <input placeholder="192.168.0.1" list="ipDatalist" name="ip" autocomplete="off"/></label>
            </form>
            <table class="analytics">
                <th>IP ADDRESS</th>
                <th>Number of visits</th>
                <?php
                $ipDatalist = "";
                foreach ($ips as $visitor => $visits) {
                    $ipDatalist .= "<option>$visitor</option>";
                    ?>
                    <tr>
                        <td><a class="darker" href="?ip=<?=$visitor?>"><?= substr($visitor, 0, 15) ?></a></td>
                        <td><?= $visits ?></td>
                    </tr>
                    <?php
                } ?><datalist id="ipDatalist"><?=$ipDatalist?></datalist>
            </table>
        </div>

        <div class="tri-item">
            <h4>Traffic by page </h4>
            <form>
                <label>Search for page <input placeholder="predictions" list="pageDatalist" name="page" autocomplete="off"/></label>
            </form>
            <table class="analytics">
                <th>Page</th>
                <th>Number of visits</th>
                <?php
                $pageDatalist = "";
                foreach($pages as $page => $visits){
                    $pageDatalist .= "<option>$page</option>";
                    ?>
                    <tr>
                        <td><a class="darker" href="?page=<?=$page?>"><?=$page ?></a></td>
                        <td><?=$visits ?></td>
                    </tr>
                    <?php
                } ?><datalist id="pageDatalist"><?=$pageDatalist?></datalist>
            </table>
        </div>

        <div class="tri-item">
            <h4>Traffic by username </h4>
            <form>
                <label>Search for username <input placeholder="brianevans" list="usernameDatalist" name="username" autocomplete="off"/></label>
            </form>
            <table class="analytics">
                <th>Username</th>
                <th>Number of visits</th>
                <?php
$userNameDataList = "";
                foreach($users as $user => $visits){
                    if($user == "not logged in") $link = ""; else $link = $user;
                    $userNameDataList .= "<option value='$link'>$user</option>";
                    ?>
                    <tr>
                        <td><a class="darker" href="?username=<?=$link?>"><?= substr($user, 0, 15) ?></a></td>
                        <td><?=$visits ?></td>
                    </tr>
                    <?php
                }
                ?> <datalist id="usernameDatalist"><?=$userNameDataList?></datalist>
            </table>
        </div>
    </div>

    <?php
}