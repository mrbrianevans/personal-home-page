create table u787130504_oceans.assessments
(
	assessment_id int auto_increment
		primary key,
	assessment_name varchar(255) null,
	subject_id int null,
	max_mark int null,
	attained_mark int null,
	datetime datetime default current_timestamp() null,
	weighting int null,
	constraint assessments_subjects_subject_id_fk
		foreign key (subject_id) references u787130504_oceans.subjects (subject_id)
);

