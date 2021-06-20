<?php
require_once('../server_details.php');
class SocialMediaModel{
	private $database;
	public function __construct(){
		$this->database = new mysqli(SERVER_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
		if ($this->database->connect_error) {
        	die("Connection failed, error code: " . $this->database->connect_error);
    	}
	}
	public function __destruct(){
		$this->database->close();
	}
	
	public function newPost($author, $username, $title, $content){
        $sql = "INSERT INTO posts(author, username, title, content) VALUES('$author', '$username', '$title', '$content')";
		$this->database->query($sql);
	}
	public function replyTo($originalPost, $author, $username, $content){
		$sql = "INSERT INTO posts(author, username, title,  content, replyto) VALUES('$author', '$username', 'reply', '$content', $originalPost)";
		$this->database->query($sql);
	}
	public function like($post){
        $sql = "SELECT likes FROM posts WHERE postID=$post limit 1";
        $currentlikes = mysqli_fetch_object($this->database->query($sql))->likes;
        $currentlikes++;
        $sql = "UPDATE posts SET likes=$currentlikes WHERE postID=$post";
        $this->database->query($sql);
    }
	
	function getPosts(){
		$posts = [];
		$sql = "SELECT postID, datetime, author, username, title, content, likes, replyto FROM posts ORDER BY postID DESC";
		$allposts = $this->database->query($sql);
		
		while($post = $allposts->fetch_assoc()){
			if(is_null($post['replyto'])){ // this is a new post
				$posts[$post['postID']] = $post;
			}else{  // this is a reply to a previous post
				$replyto = $post['replyto'];
				if(is_null($posts[$replyto]['replies'])){
					$posts[$replyto]['replies'] = [];
				}
				array_push($posts[$replyto]['replies'] , $post);
			}
		}
		// array_multisort($posts, SORT_DESC); This isn't working, but I want to make recent posts show first
		return $posts;
	}
}
?>