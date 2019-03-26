create database testdb;

grant all on testdb.* to root@localhost identified by '';

use testdb;

drop table if exists posts;
create table posts (
    id int not null auto_increment primary key,
    name char(10) not null,
    comment char(20) not null,
    created datetime
);