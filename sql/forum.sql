create database if not exists diendan;
use diendan;
create table if not exists nguoidung(
    taikhoan char(13) not null,
    matkhau char(50) not null,
    hoten nvarchar(50) not null,
    ngaysinh date,
    gioitinh varchar(10),
    sothich text,
    sodienthoai char(12),
    chucvu nvarchar(50) DEFAULT "Thành Viên",
    anhdaidien varchar(250) DEFAULT "./lib/images/default_avatar.png",
    ngaytao timestamp not null default current_timestamp,
	constraint pk_nguoidung primary key(taikhoan)
);

create table if not exists baiviet(
    mabaiviet bigint not null,
    tieude nvarchar(150) not null,
    noidung text not null,
    taikhoan char(13) not null,
    nhom nvarchar(50) not null,
    ngaytao timestamp not null default current_timestamp,
    constraint pk_posts primary key(mabaiviet),
    constraint fk_posts foreign key(taikhoan) references nguoidung(taikhoan)
);

create table if not exists binhluan(
	mabinhluan int not null auto_increment,
    noidung text not null,
    taikhoan char(13) not null,
    mabaiviet bigint not null,
    ngaytao timestamp not null default current_timestamp,
	constraint pk_comments primary key(mabinhluan),
    constraint fk_comments_user foreign key(taikhoan) references nguoidung(taikhoan),
    constraint fk_comments_post foreign key(mabaiviet) references baiviet(mabaiviet)
);

create table if not exists luotthich(
	maluotthich int not null auto_increment,
    loai boolean not null, -- 0: binh luan, 1: bai viet
    taikhoan char(13) not null,
    mabaiviet bigint,
    mabinhluan int,
	constraint pk_likes primary key(maluotthich),
    constraint fk_likes_post foreign key(mabaiviet) references baiviet(mabaiviet),
    constraint fk_likes_cmt foreign key(mabinhluan) references binhluan(mabinhluan),
    constraint fk_likes_user foreign key(taikhoan) references nguoidung(taikhoan)
);
create table if not exists thongbao(
	mathongbao bigint not null,
    trangthai boolean default 0, -- 0: chua doc, 1: da doc
    nguoigui char(13) not null,
    noidung nvarchar(200) not null,
    nguoinhan char(13) not null,
	`url` varchar(200),
    ngaytao timestamp not null default current_timestamp,
	constraint pk_notify primary key(mathongbao),
    constraint fk_notify_from foreign key(nguoigui) references nguoidung(taikhoan),
    constraint fk_notify_to foreign key(nguoinhan) references nguoidung(taikhoan)
);
create table if not exists hinhanh(
	mahinhanh int not null auto_increment,
    taikhoan char(13) not null,
    loai char(15) not null,
	`url` varchar(200) not null,
    mabaiviet bigint,
    mabinhluan int,
    ngaytao timestamp not null default current_timestamp,
	constraint pk_images primary key(mahinhanh),
    constraint fk_images_owner foreign key(taikhoan) references nguoidung(taikhoan)
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

insert into nguoidung(taikhoan, matkhau, hoten, chucvu) values('admin', '900150983cd24fb0d6963f7d28e17f72', 'ADMIN', 'Admin');
insert into nguoidung(taikhoan, matkhau, hoten) values('user', '900150983cd24fb0d6963f7d28e17f72', 'User');
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
