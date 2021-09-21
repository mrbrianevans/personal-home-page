create table u787130504_oceans.contests
(
	contest_id int auto_increment
		primary key,
	contest_name varchar(255) not null,
	date_started datetime default current_timestamp() null,
	outcome_int int null,
	outcome_date datetime null,
	outcome_string varchar(255) null,
	type varchar(255) not null,
	constraint contests_contest_name_uindex
		unique (contest_name)
);

