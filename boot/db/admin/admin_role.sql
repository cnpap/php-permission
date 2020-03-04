drop table if exists admin_role_permission;

drop table if exists admin_role;

create table admin_role
(
    code       varchar(32)  not null primary key,
    name       varchar(40)  not null unique,
    memo       varchar(200) not null,
    status     smallint     not null,
    created_at varchar(12),
    updated_at varchar(12),
    deleted_at varchar(12)
);

insert into
    admin_role
    (
        code,
        name,
        memo,
        status
    )
values
    (
        '001_kefu',
        '客服',
        '订单信息、订单进程变更、提交工单',
        1
    ),
    (
        '002_caiwu',
        '财务',
        '商品信息、订单流水',
        1
    ),
    (
        '003_yunying',
        '运营',
        '商品管理、订单流水、仪表盘',
        1
    );