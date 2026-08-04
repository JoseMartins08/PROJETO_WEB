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