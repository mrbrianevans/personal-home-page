create table u787130504_oceans.projects
(
    id          int           auto_increment
        primary key,
    name        varchar(255)  not null,
    description varchar(2047) null,
    start_date  date          null,
    end_date    date          null,
    url         varchar(255)  null,
    featured    tinyint(1) default 0 null,
    constraint projects_id_uindex
        unique (id)
);

