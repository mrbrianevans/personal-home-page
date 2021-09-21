create table u787130504_oceans.quadcopters
(
	id int auto_increment
		primary key,
	session_id varchar(63) null,
	ip_address varchar(127) null,
	creation_timestamp timestamp default current_timestamp() null,
	username varchar(255) null,
	motor_size int null,
	motor_kv int null,
	motor_line varchar(255) null,
	motor_brand varchar(255) null,
	esc_amps int null,
	esc_brand varchar(255) null,
	esc_line varchar(255) null,
	class varchar(255) null,
	throttle_hover int null,
	built bit default b'1' null,
	dry_weight int null,
	frame_line varchar(255) null,
	frame varchar(255) null,
	flight_controller varchar(255) null,
	flight_controller_line varchar(255) null,
	deleted bit default b'0' null
);

