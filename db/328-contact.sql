create table u787130504_oceans.contact
(
	id int auto_increment
		primary key,
	date datetime default current_timestamp() null,
	name varchar(255) null,
	message varchar(2047) null,
	email varchar(255) null,
	username varchar(255) null,
	ip_address varchar(15) null
);

