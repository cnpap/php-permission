drop table if exists admin_user_role;

drop table if exists admin_user_permission;

drop table if exists admin_user;

create table admin_user
(
    code       varchar(32)  not null primary key,
    username   varchar(20)  not null unique,
    password   varchar(32)  not null,
    name       varchar(40)  not null unique,
    memo       varchar(200) not null,
    status     smallint     not null,
    created_at timestamp,
    updated_at timestamp,
    deleted_at timestamp
);

insert into
    admin_user
    (
        code,
        username,
        password,
        name,
        memo,
        status
    )
values
    (
        '001_admin',
        '13355557777',
        'e10adc3949ba59abbe56e057f20f883e',
        '管理员',
        '默认存在的管理员',
        1
    );