create table u787130504_oceans.users
(
	username varchar(255) not null
		primary key,
	password varchar(255) not null,
	date_signed_up datetime default current_timestamp() not null
);

