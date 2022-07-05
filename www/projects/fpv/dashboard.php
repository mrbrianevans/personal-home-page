<?php
if (isset($_POST["submitNewQuadButton"])) {
    $sessionID = session_id();
    $username = $_SESSION["user"] ?? null;
    $built = boolval($_POST["built"])  ? 1 : 0;
    require "quadcopterModel.php";
    $newModel = new quadcopterModel($sessionID, $username, $ip_address, $built);
    $_SESSION["quad_id"] = $newModel->getId();

    $newModel->setParts(
        $_POST["class"],
        $_POST["motors"],
        $_POST["esc"],
        $_POST["flightController"],
        $_POST["frame"]
    );
    $errors = $newModel->getErrorList();
    foreach($errors as $error){
        echo "<dialog class='error' open onclick='this.open = false'>
                Error occured: " . $error["name"] . "<br>
                Desc: " . $error["message"] . "</dialog>";
    }
}
?>
<div id="wholeDashboard">


<div id="userDetails">Username: <?=$_SESSION["user"]?> ,  Joined October 2019</div>

<div id="quadDashboard">
    <div class="quadPanel" id="plus"">
        +
    </div>
    <?php
    require("retrieve.php");
    foreach ($quads as $quad){

        require("quadpanelView.php");

    }
    ?>

</div>

</div>