<?php

require('socialMediaModel.php');
$model = new SocialMediaModel();
$posts = $model->getPosts();
require("view.php");

if(isset($_POST['new_post'])){
	$author = str_replace("'", "\'", htmlspecialchars($_POST['author']));
	$title = str_replace("'", "\'", htmlspecialchars($_POST['title']));
	$content = str_replace("'", "\'", htmlspecialchars($_POST['content']));
	$model->newPost($author, $uname, $title, $content);
}
if(isset($_POST['reply'])){
	$author = str_replace("'", "\'", htmlspecialchars($_POST['author']));
	$content = str_replace("'", "\'", htmlspecialchars($_POST['content']));
	$post = $_POST['reply'];
	$model->replyTo($post, $author, $uname, $content);
}
if(isset($_GET['like'])){
    $post = $_GET['like'];
    $model->like($post);
}
?>