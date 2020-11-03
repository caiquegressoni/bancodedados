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
    constraint fk_possui 
    foreign key(id_pessoa)
    references pessoas(id_pessoa)
);
describe pessoas;
describe telefones;
describe usuarios;
show tables;

insert into pessoas(nome) values
("Leonardo"),
("Rodolpho"),
("Jurema");

insert into pessoas(nome) values("Maria Silva");
insert into pessoas values(default,"Marcos Ribeiro");

select * from pessoas;

insert into telefones(id_pessoa, telefone)
values (1, "19 5555-5555"), (1, "19 7777-8888");
insert into telefones(id_pessoa, telefone)
values (3,"19 232323-434343");
insert into telefones(id_pessoa, telefone)
values (2,"19 7070-7070");

select * from telefones;

update pessoas set nome = "Leonardo Silva"
where id_pessoa = 1;
update pessoas set nome = "Rodolpho Viatina"
where id_pessoa = 2;
update pessoas set nome = "Jurema Marques"
where id_pessoa = 3;


select p.id_pessoa, p.nome, t.telefone
from pessoas p inner join telefones t
on p.id_pessoa = t.id_pessoa;

create view vw_pessoas as

select p.id_pessoa, p.nome, t.telefone
from pessoas p left join telefones t
on p.id_pessoa = t.id_pessoa;


select * from vw_pessoas;

insert into usuarios values
(1,"silva.leonardor",md5("12345678")),
(2,"viatina.rodolpho",md5("12345678")),
(3,"marques.jurema",md5("12345678"));

select * from usuarios;

