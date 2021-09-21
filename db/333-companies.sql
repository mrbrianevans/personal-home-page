create table u787130504_oceans.companies
(
	name varchar(160) null,
	number char(8) not null
		primary key,
	streetAddress varchar(300) null,
	county varchar(50) null,
	country varchar(50) null,
	postCode varchar(20) null,
	category varchar(100) null,
	origin varchar(50) null,
	status varchar(70) null,
	date date null,
	SicCode1 varchar(10) null,
	SicCode2 varchar(10) null,
	SicCode3 varchar(10) null,
	SicCode4 varchar(10) null
);

