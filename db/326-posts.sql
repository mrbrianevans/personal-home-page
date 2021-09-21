create table u787130504_oceans.posts
(
	postID int auto_increment
		primary key,
	datetime datetime default current_timestamp() not null,
	author varchar(255) not null,
	username varchar(255) not null,
	title varchar(255) not null,
	content varchar(2047) not null,
	likes int default 0 not null,
	replyto int null
);

