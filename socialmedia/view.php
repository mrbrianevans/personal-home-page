<?php 

if(is_null($uname)){
    ?>

<p>It is recommended you <a class=darker href="../register/">create an account</a>, or <a class=darker href="../login/">sign in</a> if you already have one, but it is not necessary to post or comment. </p>
<?php
}
?>
<form method="post">
	Author: <input type="text" name="author" value="<?=ucwords($uname)?>"><br>
	Title: <input type="text" name="title"><br>
	Content: <textarea class="content" name="content" rows=10></textarea><br>
	<input type="submit" value="Post" name="new_post" id="postbutton">
</form>
<?php 
	foreach($posts as $post){
	?>	
<div class='post' id="post<?=$post["postID"]?>">
<p class="smallhead"><?= $post['title'] ?></p>
<p>Written by: <b><?= $post['author'] ?></b> (<em><?= $post['username'] ?></em>) on <?= date("l, j F", strtotime($post['datetime']))?></p>
<p><?= $post['content'] ?></p>

    Likes: <span id="like<?=$post["postID"]?>"><?= $post['likes'] ?></span>  <button onclick="like(<?=$post["postID"]?>)"><img id='likebut<?=$post["postID"]?>' class="like" src="like_button.svg"></button>

    <form class=reply>
		<textarea id="content<?=$post['postID']?>" class="comment" name="content" rows=1 onClick="clearBox('content<?=$post["postID"]?>')" onBlur="setToDefaultIfEmpty('content<?=$post["postID"]?>')">Type reply here...</textarea>
		Name: <input class="replyname" type="text" name="author" id="author<?=$post['postID']?>" onClick="clearBox('author<?=$post["postID"]?>')" onBlur="setToDefaultIfEmpty('author<?=$post["postID"]?>')" value="<?=ucwords($uname)?>">
		<button class="replybutton" type="button" value="<?=$post['postID']?>" name="reply" onclick="postReply(<?=$post['postID']?>, <?=$post['postID']?>, <?=$post['postID']?>)">Post reply</button>
	</form>
    <div id="replyboxof<?=$post["postID"]?>">
    <?php foreach($post['replies'] as $reply){

            ?>
        <div class='reply'>
            Reply from:<strong> <?= $reply['author'] ?></strong>. Posted on <?= date("l", strtotime($reply['datetime']))?><br>
            <?= $reply['content'] ?>
        </div>
        <?php
        }
    ?>
    </div>
</div>
<?php 
	}
?>