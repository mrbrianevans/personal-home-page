create table u787130504_oceans.predictions
(
	prediction_id int auto_increment
		primary key,
	contest_id int not null,
	contest_name varchar(255) not null,
	username varchar(255) not null,
	ip_address varchar(47) not null,
	prediction_int int null,
	prediction_date datetime null,
	prediction_string varchar(2047) null,
	datetime datetime default current_timestamp() null,
	constraint predictions_ibfk_1
		foreign key (contest_id) references u787130504_oceans.contests (contest_id)
			on update cascade on delete cascade,
	constraint predictions_ibfk_2
		foreign key (contest_name) references u787130504_oceans.contests (contest_name)
			on update cascade on delete cascade
);

