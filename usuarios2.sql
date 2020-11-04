drop database if exists usuarios;
create database usuarios;
use usuarios;
create table pessoas(
    id_pessoa integer not null auto_increment,
    nome varchar(40) not null,
    primary key(id_pessoa)
);
create table telefones(
    id_pessoa integer not null,
    telefone varchar(15) not null,
    constraint fk_tel_pess 
    foreign key(id_pessoa)
    references pessoas(id_pessoa)
);
create table usuarios(
    id_pessoa integer not null,
    login varchar(12) not null,
    senha varchar(50) not null,
    tipo varchar(15) not null,
    constraint fk_possui 
    foreign key(id_pessoa)
    references pessoas(id_pessoa)
);

create view vw_pessoas as
select p.id_pessoa, p.nome, t.telefone
from pessoas p left join telefones t
on p.id_pessoa = t.id_pessoa;


insert into pessoas(nome) values
("Leonardo Silva"),
("Rodolpho Vieira"),
("Jurema Andrade"),
("Maricia Souza"),
("Rodrigo Vieira"),
("Maria Silva");

insert into telefones values
(1, "19 45677-0925"),
(1, "19 35669-1952"),
(3, "19 70701-6060"),
(5, "19 33322-5591");

insert into usuarios values
(1,"silva.leonar",md5("12345678"),"comum"),
(3,"andrade.jure",md5("12345678"),"comum"),
(4,"souza.marcia",md5("12345678"),"adm"),
(6,"silva.maria",md5("12345678"),"adm");

select * from pessoas;
select * from telefones;
select * from vw_pessoas;
select * from usuarios;