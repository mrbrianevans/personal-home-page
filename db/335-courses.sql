create table u787130504_oceans.courses
(
	course_id int auto_increment
		primary key,
	course_name varchar(255) null,
	course_type varchar(255) null,
	username varchar(255) null,
	datetime datetime default current_timestamp() null
);

