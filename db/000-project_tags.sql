create table u787130504_oceans.project_tags
(
    id         int auto_increment,
    project_id int         null,
    tag        varchar(20) not null,
    constraint project_tags_pk
        primary key (id),
    constraint project_tags_projects_id_fk
        foreign key (project_id) references u787130504_oceans.projects (id)
);

