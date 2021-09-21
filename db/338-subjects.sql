create table u787130504_oceans.subjects
(
	subject_id int auto_increment
		primary key,
	subject_name varchar(255) null,
	course_id int null,
	datetime datetime default current_timestamp() null,
	average int null,
	grade varchar(255) null,
	constraint subjects_courses_course_id_fk
		foreign key (course_id) references u787130504_oceans.courses (course_id)
);

