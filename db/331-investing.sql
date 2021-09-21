create table u787130504_oceans.investing
(
	id int auto_increment
		primary key,
	session varchar(255) null,
	no_of_instruments int null,
	days_in_range int null,
	price_history_time float null,
	cleaning_time float null,
	ror_time float null,
	calcs_time float null,
	datetime datetime default current_timestamp() null
);

