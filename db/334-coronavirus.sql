create table u787130504_oceans.coronavirus
(
	entry int auto_increment
		primary key,
	datetime datetime default current_timestamp() null,
	uk_cases int null,
	uk_deaths int null,
	usa_cases int null,
	usa_deaths int null
);

