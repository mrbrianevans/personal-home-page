create table u787130504_oceans.psc
(
	companyNumber char(8) null,
	notified date null,
	nationality varchar(20) null,
	firstName varchar(50) null,
	middleName varchar(50) null,
	lastName varchar(50) null,
	countryOfResidence varchar(50) null,
	birthMonth tinyint null,
	birthYear smallint null,
	link varchar(30) not null,
	addressLocality varchar(50) null,
	addressPostCode varchar(20) null,
	addressRegion varchar(50) null,
	constraint link
		unique (link),
	constraint psc_link_uindex
		unique (link),
	constraint psc_ibfk_1
		foreign key (companyNumber) references u787130504_oceans.companies (number)
);

create index companyNumber
	on u787130504_oceans.psc (companyNumber);

create index fullName
	on u787130504_oceans.psc (firstName, lastName);

alter table u787130504_oceans.psc
	add primary key (link);

