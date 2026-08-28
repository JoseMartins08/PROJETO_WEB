create database if not exists bd_mundo;
use bd_mundo;

create table continentes (
	id_continente int auto_increment primary key,
    nome varchar(50) not null,
    populacao bigint,
    area decimal(15,2),
    total_paises int default 0
);

create table governantes (
	id_governante int auto_increment primary key,
    nome varchar(90) not null,
    partido_politico varchar(90),
    data_nascimento date,
    idade int,
    data_inicio_mandato date,
    data_fim_mandato date
);

create table paises (
	id_pais int auto_increment primary key,
    nome varchar(90) not null,
    continente_id int,
    populacao bigint,
    area decimal(15,2),
    idioma varchar(50),
    clima varchar(50),
    regime_politico varchar(50),
    moeda varchar(50),
    governante_id int,
    foreign key (continente_id) references continentes(id_continente) on delete set null,
    foreign key (governante_id) references governantes(id_governante) on delete set null
);

create table cidades (
	id_cidade int auto_increment primary key,
    nome varchar(100) not null,
    pais_id int not null,
    populacao bigint,
    area decimal(15,2),
    clima varchar(50),
    data_fundacao date,
    governante_id int,
    foreign key (pais_id) references paises(id_pais) on delete cascade,
    foreign key (governante_id) references governantes(id_governante) on delete set null
);

create table usuarios (
	id_usuario int auto_increment primary key,
    username varchar(30) not null,
    senha varchar(128) not null,
    nome varchar(80) not null,
    status char(1) not null default 'A',
    tipo char(1) not null default 'U',
    qtde_acesso int not null default 0,
    primeiro_acesso char(1) not null default 'S'
);

create table logs (
	id_log int auto_increment primary key,
    data_acesso date not null default current_timestamp,
    descricao varchar(200),
    username varchar(30) not null,
    foreign key (username) references usuarios(username)
);

show create table usuarios;