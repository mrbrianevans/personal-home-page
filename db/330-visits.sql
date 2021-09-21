create table u787130504_oceans.visits
(
	visit_id int auto_increment
		primary key,
	date_visited datetime default current_timestamp() not null,
	ip_address varchar(39) not null,
	name varchar(255) not null,
	page varchar(255) not null,
	previous int null,
	session_id char(32) null
);

