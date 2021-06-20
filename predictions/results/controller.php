<?php

include_once "predictionResultsModel.php";

$predictionResultsModel = new predictionResultsModel();

$contests = $predictionResultsModel->getContestsWithResults();
include "view.php";