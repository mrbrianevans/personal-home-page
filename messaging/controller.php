<?php
require_once "messagingModel.php";
$model = new messagingModel();
$contacts = $model->getContacts();
require_once "view.php";