create database if not exists forum;
use forum;
create table if not exists users(
    user_name char(13) not null,
    pass char(50) not null,
    hoten nvarchar(50) not null,
    ngaysinh date,
    gioitinh varchar(10),
    sothich text,
    sodienthoai char(12),
    chucvu nvarchar(50) DEFAULT "Thành Viên",
    avatar varchar(250) DEFAULT "./lib/images/default_avatar.png",
    date timestamp not null default current_timestamp,
	constraint pk_users primary key(user_name)
);

create table if not exists posts(
    post_id bigint not null,
    title nvarchar(150) not null,
    content text not null,
    user_name char(13) not null,
    nhom nvarchar(50) not null,
    date timestamp not null default current_timestamp,
    constraint pk_posts primary key(post_id),
    constraint fk_posts foreign key(user_name) references users(user_name)
);

create table if not exists comments(
	comment_id int not null auto_increment,
    content text not null,
    user_name char(13) not null,
    post_id bigint not null,
    date timestamp not null default current_timestamp,
	constraint pk_comments primary key(comment_id),
    constraint fk_comments_user foreign key(user_name) references users(user_name),
    constraint fk_comments_post foreign key(post_id) references posts(post_id)
);

create table if not exists likes(
	like_id int not null auto_increment,
    is_post boolean not null,
    user_name char(13) not null,
    post_id bigint,
    cmt_id int,
	constraint pk_likes primary key(like_id),
    constraint fk_likes_post foreign key(post_id) references posts(post_id),
    constraint fk_likes_cmt foreign key(cmt_id) references comments(comment_id),
    constraint fk_likes_user foreign key(user_name) references users(user_name)
);
create table if not exists notifications(
	id bigint not null,
    readed boolean default false,
    from_user char(13) not null,
    msg nvarchar(200) not null,
    to_user char(13) not null,
	`url` varchar(200),
    `date` timestamp not null default current_timestamp,
	constraint pk_notify primary key(id),
    constraint fk_notify_from foreign key(from_user) references users(user_name),
    constraint fk_notify_to foreign key(to_user) references users(user_name)
);
create table if not exists images(
	image_id int not null auto_increment,
    `owner` char(13) not null,
    `type` char(15) not null,
	`url` varchar(200) not null,
    post_id bigint,
    comment_id int,
    `date` timestamp not null default current_timestamp,
	constraint pk_images primary key(image_id),
    constraint fk_images_owner foreign key(`owner`) references users(user_name)
);

-- delimiter $$
-- create function deleteLikes(id bigint) returns boolean
-- begin

-- end

-- $$
-- delimiter ;
-- DELIMITER //
-- create event delete_event
-- on schedule at current_timestamp + interval 1 day
-- on completion preserve
-- do begin 
-- 	delete from forum.notifications where `date` < date_sub(now(), interval 7 day) and readed = true;
-- end//
-- DELIMITER ;
-- set global event_scheduler = on;

insert into users(user_name, pass, hoten, chucvu) values('admin', '900150983cd24fb0d6963f7d28e17f72', 'ADMIN', 'Admin');
insert into users(user_name, pass, hoten) values('user', '900150983cd24fb0d6963f7d28e17f72', 'User');
-- alter table users add column date timestamp default current_timestamp() after avatar

-- alter table posts add constraint fk_posts foreign key(user_name) references users(user_name);
-- alter table comments add constraint fk_comments_user foreign key(user_name) references users(user_name);
-- alter table comments add constraint fk_comments_post foreign key(post_id) references posts(post_id);
-- alter table likes add constraint fk_likes_post foreign key(post_id) references posts(post_id);
-- alter table likes add constraint fk_likes_cmt foreign key(cmt_id) references comments(comment_id);
-- alter table likes add constraint fk_user foreign key(user_name) references users(user_name);
-- alter table notifications add constraint fk_from foreign key(from_user) references users(user_name);
-- alter table notifications add constraint fk_to foreign key(to_user) references users(user_name);

































-- create table if not exists images(
-- 	image_id int not null auto_increment,
--     link text not null,
--     post int not null,
-- 	constraint pk_images primary key(image_id)
-- );


-- alter table users add constraint fk_users_add foreign key(anhdaidien) references images(image_id);
-- alter table images add constraint fk_images_post foreign key(post) references posts(post_id);
-- create table if not exists likes(
--     user_name char(13) not null,
--     post_id int not null,
--     time_like datetime,
--     constraint fk_likes_user foreign key(user_name) references users(user_name),
--     constraint fk_likes_post foreign key(post_id) references posts(post_id)
-- 
-- create table if not exists notifications(
-- 	notif_id int not null auto_increment,
--     content text not null,
--     user_name char(13) not null,
--     time_notif datetime,
--     constraint pk_notifications primary key(notif_id),
--     constraint fk_notifications_user foreign key(user_name) references users(user_name)
-- );
