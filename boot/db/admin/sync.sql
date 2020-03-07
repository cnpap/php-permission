create table admin_user_role
(
    user_code varchar(32) not null references admin_user(code),
    role_code varchar(32) not null references admin_role(code)
);

create unique index user_role_nique on admin_user_role (user_code, role_code);

create table admin_user_permission
(
    user_code       varchar(32) not null references admin_user(code),
    permission_code varchar(32) not null references admin_permission(code)
);

create unique index user_permission_nique on admin_user_permission (user_code, permission_code);

create table admin_role_permission
(
    role_code varchar(32)       not null references admin_role(code),
    permission_code varchar(32) not null references admin_permission(code)
);

create unique index role_permission_nique on admin_role_permission (role_code, permission_code);