create database pvzpokemon;
use pvzpokemon;

create table usuario(

nome_usr varchar(100) not null unique,
id_usr int auto_increment primary key,
senha varchar(20) not null,
cor_favorita char(7) default '#00bfff',
dinheiro int default 500

);


create table item(

nome_item varchar(100) not null,
id_item int auto_increment primary key,
preco_item int not null,
desc_item varchar(200)

);

create table tipo(

nome_tipo varchar(20) not null,
id_tipo int primary key auto_increment,

f1 int,
f2 int,
w1 int,
w2 int,

foreign key (f1) references tipo(id_tipo),
foreign key (f2) references tipo(id_tipo),
foreign key (w1) references tipo(id_tipo),
foreign key (w2) references tipo(id_tipo)
);


create table condicao (
nome_cond varchar(20) not null, 
id_cond int primary key auto_increment,
efeito varchar(100) not null

);


create table ataque(

id_atk int primary key auto_increment,
nome_atk varchar(50) not null,
id_tipo int not null,

mult_dano double not null, 
priori int not null,

desc_atk varchar(200) not null,
id_cond int,
chance_cond int,

foreign key (id_cond) references condicao(id_cond),
foreign key (id_tipo) references tipo(id_tipo)
);


create table especie(

nome_esp varchar(100) not null,
id_esp int primary key auto_increment,
vida int not null,
forca int not null,
veloc int not null,

id_tipo int not null,
foreign key (id_tipo) references tipo(id_tipo)
);

create table pokemon(

nome_pkm varchar(100) not null,
id_usr int,

id_esp int not null,
id_evo int,

id_atk1 int,
id_atk2 int,
id_atk3 int,
id_atk4 int,

id_cond int,

vida_pkm int not null,

id_pkm int primary key auto_increment,

foreign key (id_atk1) references ataque(id_atk),
foreign key (id_atk2) references ataque(id_atk),
foreign key (id_atk3) references ataque(id_atk),
foreign key (id_atk4) references ataque(id_atk),


foreign key (id_cond) references condicao(id_cond),
foreign key (id_esp) references especie(id_esp),
foreign key (id_evo) references especie(id_esp)
);

create table inventario(

id_usr int not null,
id_item int not null,
quant_item int not null,

foreign key (id_usr) references usuario(id_usr),
foreign key (id_item) references item(id_item)

);

create table timepokemon(

id_usr int not null,
id_pkm int not null,

foreign key (id_usr) references usuario(id_usr),
foreign key (id_pkm) references pokemon(id_pkm)

);

use pvzpokemon; -- INSERTS

insert into tipo(nome_tipo) values ('Protetor'); -- 1 
insert into tipo(nome_tipo) values ('Explosivo'); -- 2
insert into tipo(nome_tipo) values ('Crescida'); -- 3 
insert into tipo(nome_tipo) values ('Esperto'); -- 4 
insert into tipo(nome_tipo) values ('Solar'); -- 5
 
insert into tipo(nome_tipo) values ('Feroz'); -- 6
insert into tipo(nome_tipo) values ('Miolástico'); -- 7
insert into tipo(nome_tipo) values ('Doidão'); -- 8
insert into tipo(nome_tipo) values ('Vigoroso'); -- 9
insert into tipo(nome_tipo) values ('Furtivo'); -- 10

update tipo set f1 = 7, f2 = 8, w1 = 10, w2 = 8 where (id_tipo = 1); -- prot
update tipo set f1 = 8, f2 = 9, w1 = 6, w2 = 8 where id_tipo = 2; -- expl
update tipo set f1 = 9, f2 = 6, w1 = 7, w2 = 10 where id_tipo = 3; -- cres
update tipo set f1 = 7, f2 = 10, w1 = 9, w2 = 6 where id_tipo = 4; -- espe
update tipo set f1 = 6, f2 = 10, w1 = 7, w2 = 9 where id_tipo = 5; -- sola

update tipo set f1 = 4, f2 = 2, w1 = 3, w2 = 5 where id_tipo = 6; -- froz
update tipo set f1 = 5, f2 = 3, w1 = 1, w2 = 4 where id_tipo = 7; -- miol
update tipo set f1 = 1, f2 = 2, w1 = 1, w2 = 2 where id_tipo = 8; -- doid
update tipo set f1 = 4, f2 = 5, w1 = 2, w2 = 3 where id_tipo = 9; -- vigo
update tipo set f1 = 1, f2 = 3, w1 = 4, w2 = 5 where id_tipo = 10; -- furt


insert into condicao(nome_cond, efeito) values ('Queimado', 'Perde 5% vida Max no fim do turno. Nao pode cong. ATK -10%'); -- 1
insert into condicao(nome_cond, efeito) values ('Envenenado', 'Perde 10% vida Max no fim do turno'); -- 2
insert into condicao(nome_cond, efeito) values ('Paralizado', 'Vel - 50%. 30% de chance de nao se mover'); -- 3
insert into condicao(nome_cond, efeito) values ('Adormecido', 'Nao se move. 30% de chance de acordar no fim. incrementa +15'); -- 4
insert into condicao(nome_cond, efeito) values ('Congelado', 'Nao se move. Some ao tomar um total de 20% da vida. Uso unico'); -- 5

insert into item(nome_item, preco_item, desc_item) values ('Bacon', 15, 'Cura 6 de vida'); -- 1
insert into item(nome_item, preco_item, desc_item) values ('Waffle', 30, 'Cura 12 de vida'); -- 2
insert into item(nome_item, preco_item, desc_item) values ('Taco', 90, 'Cura 45 de vida'); -- 3
insert into item(nome_item, preco_item, desc_item) values ('Taco com Waffle', 130, 'Cura 100% de vida'); -- 4
insert into item(nome_item, preco_item, desc_item) values ('Alfaiceberg', 20, 'Cura de Queimadura'); -- 5
insert into item(nome_item, preco_item, desc_item) values ('Molho Picante', 40, 'Cura de Congelamento'); -- 6
insert into item(nome_item, preco_item, desc_item) values ('Vitamina Z', 20, 'Cura de Veneno'); -- 7
insert into item(nome_item, preco_item, desc_item) values ('Doce Açucarado', 20, 'Cura da Paralisia'); -- 8
insert into item(nome_item, preco_item, desc_item) values ('Aloe', 60, 'Cura Tudo'); -- 9
insert into item(nome_item, preco_item, desc_item) values ('Frangão', 120, 'Cura Tudo e 100% de vida'); -- 10
insert into item(nome_item, preco_item, desc_item) values ('Cenoura do Poder', 80, 'Revive com 50% de vida'); -- 11

insert into item(nome_item, preco_item, desc_item) values ('Pedra de Gelo', 100, 'Evolui Disparervilha para Gelervilha'); -- 12
insert into item(nome_item, preco_item, desc_item) values ('Capacete', 300, 'Evolui Gelervilha para Ervilhadora'); -- 13
insert into item(nome_item, preco_item, desc_item) values ('Saxofone', 250, 'Evolui Uvas Azedas para Mirtilartista'); -- 14
insert into item(nome_item, preco_item, desc_item) values ('Buraco Negro', 400, 'Evolui Boca-de-Dragão para Pitaya'); -- 15
insert into item(nome_item, preco_item, desc_item) values ('Arco-íris', 250, 'Evolui Girassol para Flor Elétrica'); -- 16
insert into item(nome_item, preco_item, desc_item) values ('Babosa', 350, 'Evolui Nabossauro para Babosassauro'); -- 17

insert into item(nome_item, preco_item, desc_item) values ('Cutelo', 200, 'Evolui Galinha para Mascote de Time'); -- 18
insert into item(nome_item, preco_item, desc_item) values ('Pedra do Fogo', 200, 'Evolui Galinha para Galo'); -- 19
insert into item(nome_item, preco_item, desc_item) values ('Super Fedor', 300, 'Evolui Zumbolha para Gigante Gasoso'); -- 20
insert into item(nome_item, preco_item, desc_item) values ('Discobola', 250, 'Evolui Dançarino para Discotron 3000'); -- 21
insert into item(nome_item, preco_item, desc_item) values ('Hambúrguer', 100, 'Evolui Paparazzumbi para Sumô'); -- 22
insert into item(nome_item, preco_item, desc_item) values ('Bandeira', 300, 'Evolui Sumô para Aspirante a Herói'); -- 23
insert into item(nome_item, preco_item, desc_item) values ('Telporte', 200, 'Evolui Cabra para Planador Cabra 3000'); -- 24



insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Noz Obstáculo', 0,60,1,1);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Cacto', 4,50,3,1);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Tricenourátops', 2,20,2,1);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Planterna', 6,30,3,1);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Batatamina', 0,10,1,1);


insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Lança-Banana', 0,30,2,2);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Coronel Milho', 12,60,8,2);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Nabossauro Bebê', 3,20,3,2);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Uvas Azedas', 4,20,4,2);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Mirtilartista', 6,30,3,2);


insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Disparervilha', 2,10,1,3);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Ervilhadora', 10,40,5,3);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Batatossauro', 10,50,6,3);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Repolho Boxeador', 4,10,1,3);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Aneis de Cebola', 8,40,4,3);


insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Carambola Cadente', 5,20,5,4);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Boca-de-Dragão', 6,30,4,4);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Gelervilha', 4,20,2,4);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Pitaya de Matéria-Escura', 12,60,8,4);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Typha', 6,20,3,4);


insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Girassol', 2,10,1,5);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Flor Elétrica', 6,50,4,5);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Babosassauro', 8,70,5,5);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Carnívora', 5,30,4,5);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zoiudo', 4,10,2,5);


insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Cabra', 2,10,0,6);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Planador Cabra 3000', 3,30,3,6);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Brontaço', 10,50,5,6);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbi Assombrado', 4,10,2,6);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Cavaleiro do Golfinho', 6,20,2,6);

insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Químico Louco', 6,50,4,7);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbolha', 4,50,3,7);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbi Guarda-Sol', 6,60,4,7);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbinho Leprechaun', 3,20,1,7);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Paparazzumbi', 2,10,1,7);

insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Gigante Gasoso', 5,50,6,8);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbi Soldado', 6,40,5,8);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbi Dançarino', 6,10,3,8);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Discotron 3000', 10,50,6,8);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Valquíria', 0,60,4,8);

insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbi Sumô', 4,30,2,9);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Aspirante a Herói', 12,10,7,9);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('All-Star Cósmico', 4,20,3,9);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Faraó Imortal', 8,60,6,9);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbi Mascote de Time', 3,40,3,9);

insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbinho Salsicha', 4,20,2,10);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Galinha Zumbi', 4,20,1,10);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Galo de Fogo', 6,10,4,10);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Yeti Criogênico', 6,40,5,10);
insert into especie(nome_esp, forca , vida, veloc, id_tipo) values ('Zumbô SubTubarãoTrônico', 10,50,6,10);


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Ruinagumelo',1,7.0,4,'Destroi violentamente o inimigo, mas precisa de recarga', null); -- 1
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Barreira de Noz',1,0.0,-1,'Se proteja de ataques', null); -- 2

insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Explosão Frutífera',2,1.8,2,'Frutinhas violentamente explosivas', null); -- 3 3
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Bombanana',2,4,1,'Explosão Forte e rápida', null); -- 4


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Canhervilha',3,1,4,'Dispara um projetil comum', null); -- 5
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Fertilizar',3,0,4,'Aumente seu Ataque em +20', null); -- 6


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond) values ('Tiro Gelado',4,0.8,2,'Tem uma chance de congelar o inimigo', 5,50); -- 7
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond) values ('Alfaiceberg',4,0,2,'Congele seu inimigo', 5,100); -- 8


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond) values ('Pulso Solar',5,0.7,3,'Tem uma chance de queimar o oponente', 1,50); -- 9
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Ensolação',5,0,20,'Diminui a prioridade dos ataques de planta', null); -- 10


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond) values ('Mordiscada',6,0.8,1,'Tem uma chance de paralisar o oponente', 3,30); -- 11
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Miolo no Prato',6,0,2,'Aumente seu ataque em +10', null); -- 12


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Ciência de Foguetes',7,2,9,'Foguetes violentamente explosivos', null); -- 13
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Criomiolos',7,0,20,'Reduz a prioridade dos ataques zumbis', null); -- 14


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Dança dos Mortos Vivos',8,1,4,'Dançar até a morte!', null); -- 15
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond) values ('Dança do Sono',8,0,10,'Como uma canção de ninar, só que dançante', 4,100); -- 16


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond) values ('Arroto',9,0.8,3,'Tem chance de envenenar', 2,30); -- 17
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Sepultar',9,0,-1,'Protege contra ataques', null); -- 18

insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Pedrada',10,0.5,3,'Olha a pedra!', null); -- 19
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Doce Saudável',10,0,10,'Cura 20 de vida', null); -- 20!

