create database pvzpokemon;
use pvzpokemon;

create table usuario(

nome_usr varchar(100) not null unique,
id_usr int auto_increment primary key,
senha varchar(20) not null,
cor_favorita char(7) default '#00bfff',
dinheiro int default 500,
foto varchar(200),
fase int default 1

);

create table item(

nome_item varchar(100) not null,
id_item int auto_increment primary key,
preco_item int not null,
foto varchar(200) not null,
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
vida_max int not null,
forca int not null,
veloc int not null,
atk1 int not null,
atk2 int,

id_evo int,

foto varchar(200),

id_tipo int not null,

foreign key (atk1) references ataque(id_atk),
foreign key (atk2) references ataque(id_atk),

foreign key (id_tipo) references tipo(id_tipo),
foreign key (id_evo) references especie(id_esp)
);

create table pokemon(

nome_pkm varchar(100) not null,
id_usr int,

id_esp int not null,
id_cond int,

vida_atual int not null,
id_pkm int primary key auto_increment,

foreign key (id_cond) references condicao(id_cond),
foreign key (id_esp) references especie(id_esp)
);

create table inventario(

id_usr int not null,
id_item int not null,
quant_item int not null,


constraint item_usuario primary key clustered (id_usr, id_item),
foreign key (id_usr) references usuario(id_usr),
foreign key (id_item) references item(id_item)


);

create table inimigo(

id_inim int primary key auto_increment,
txt_entra varchar(300) not null,
txt_mata varchar(300) not null,
txt_morre varchar(300) not null

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
-- ITENS DE CURA
insert into item(nome_item, preco_item, desc_item, foto) values ('Bacon', 15, 'Cura 6 de vida', '../ImagensPVZ/itens/itens de cura/Bacon.png'); -- 1
insert into item(nome_item, preco_item, desc_item, foto) values ('Waffle', 30, 'Cura 12 de vida', '../ImagensPVZ/itens/itens de cura/waffle.png'); -- 2
insert into item(nome_item, preco_item, desc_item, foto) values ('Taco', 90, 'Cura 45 de vida', '../ImagensPVZ/itens/itens de cura/tacomagico.png'); -- 3
insert into item(nome_item, preco_item, desc_item, foto) values ('Taco com Waffle', 130, 'Cura 100% de vida', '../ImagensPVZ/itens/itens de cura/WaffleTaco.png'); -- 4
insert into item(nome_item, preco_item, desc_item, foto) values ('Alfaiceberg', 20, 'Cura de Queimadura', '../ImagensPVZ/itens/itens de cura/cura_de_queimadura.png'); -- 5
insert into item(nome_item, preco_item, desc_item, foto) values ('Molho Picante', 40, 'Cura de Congelamento', '../ImagensPVZ/itens/itens de cura/Hot_Sauce.png'); -- 6
insert into item(nome_item, preco_item, desc_item, foto) values ('Vitamina Z', 20, 'Cura de Veneno', '../ImagensPVZ/itens/itens de cura/Vitamina_Z.png'); -- 7
insert into item(nome_item, preco_item, desc_item, foto) values ('Cabeça Flutuante', 20, 'Cura da Paralisia', '../ImagensPVZ/itens/itens de cura/cura_de_paralisia.png'); -- 8
insert into item(nome_item, preco_item, desc_item, foto) values ('Café', 20, 'Cura da Sono', '../ImagensPVZ/itens/itens de cura/café.png'); -- 9
insert into item(nome_item, preco_item, desc_item, foto) values ('Aloe', 60, 'Cura Tudo', '../ImagensPVZ/itens/itens de cura/Aloe.png'); -- 10
insert into item(nome_item, preco_item, desc_item, foto) values ('Frangão', 180, 'Cura Tudo e 100% de vida', '../ImagensPVZ/itens/itens de cura/restauratudo.png'); -- 11
insert into item(nome_item, preco_item, desc_item, foto) values ('Cenoura do Poder', 80, 'Revive com 50% de vida', '../ImagensPVZ/itens/itens de cura/cenoura_do_poder.png'); -- 12
-- ITENS EVOLUÇÃO
insert into item(nome_item, preco_item, desc_item, foto) values ('Pedra de Gelo', 100, 'Evolui Disparervilha para Gelervilha', '../ImagensPVZ/itens/itens de evolução/pedra_de_gelo.png'); -- 13
insert into item(nome_item, preco_item, desc_item, foto) values ('Capacete', 300, 'Evolui Gelervilha para Ervilhadora', '../ImagensPVZ/itens/itens de evolução/capacete.png'); -- 14
insert into item(nome_item, preco_item, desc_item, foto) values ('Saxofone', 250, 'Evolui Uvas Azedas para Mirtilartista', '../ImagensPVZ/itens/itens de evolução/saxofone.png'); -- 15
insert into item(nome_item, preco_item, desc_item, foto) values ('Buraco Negro', 400, 'Evolui Boca-de-Dragão para Pitaya', '../ImagensPVZ/itens/itens de evolução/buraco_negro.png'); -- 16
insert into item(nome_item, preco_item, desc_item, foto) values ('Arco-íris', 250, 'Evolui Girassol para Flor Elétrica', '../ImagensPVZ/itens/itens de evolução/arcoiris.png'); -- 17
insert into item(nome_item, preco_item, desc_item, foto) values ('Babosa', 350, 'Evolui Nabossauro para Babosassauro', '../ImagensPVZ/itens/itens de evolução/babosa.png'); -- 18

insert into item(nome_item, preco_item, desc_item, foto) values ('Cutelo', 200, 'Evolui Galinha para Mascote de Time', '../ImagensPVZ/itens/itens de evolução/cutelo.png'); -- 19
insert into item(nome_item, preco_item, desc_item, foto) values ('Pedra do Fogo', 200, 'Evolui Galinha para Galo', '../ImagensPVZ/itens/itens de evolução/pedra_de_fogo.png'); -- 20
insert into item(nome_item, preco_item, desc_item, foto) values ('Super Fedor', 300, 'Evolui Zumbolha para Gigante Gasoso', '../ImagensPVZ/itens/itens de evolução/super_fedor.png'); -- 21
insert into item(nome_item, preco_item, desc_item, foto) values ('Discobola', 250, 'Evolui Dançarino para Discotron 3000', '../ImagensPVZ/itens/itens de evolução/Disco_bola.png'); -- 22
insert into item(nome_item, preco_item, desc_item, foto) values ('Hambúrguer', 100, 'Evolui Paparazzumbi para Sumô', '../ImagensPVZ/itens/itens de evolução/hamburguer.png'); -- 23
insert into item(nome_item, preco_item, desc_item, foto) values ('Bandeira', 300, 'Evolui Sumô para Aspirante a Herói', '../ImagensPVZ/itens/itens de evolução/bandeira.png'); -- 24
insert into item(nome_item, preco_item, desc_item, foto) values ('Telporte', 200, 'Evolui Cabra para Planador Cabra 3000', '../ImagensPVZ/itens/itens de evolução/Teleport_HD.png'); -- 25


-- ATAQUES

insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Ruinagumelo',1,7.0,4,'Destroi violentamente o inimigo, mas precisa de recarga', null); -- 1
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Barreira de Noz',1,0.0,-1,'Se proteja de ataques', null); -- 2

insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Explosão Frutífera',2,1.8,2,'Frutinhas violentamente explosivas', null); -- 3 3
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Bombanana',2,4,1,'Explosão Forte e rápida', null); -- 4


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Canhervilha',3,1,4,'Dispara um projetil comum', null); -- 5
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Fertilizar',3,0,4,'Aumente seu Ataque em +20', null); -- 6


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond) values ('Tiro Gelado',4,0.8,2,'Tem uma chance de congelar o inimigo', 5,50); -- 7
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond) values ('Alfaiceberg',4,0,2,'Congele seu inimigo', 5,100); -- 8


insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond, chance_cond) values ('Pulso Solar',5,0.7,3,'Tem uma chance de queimar o oponente', 1,50); -- 9
insert into ataque(nome_atk, id_tipo, mult_dano, priori, desc_atk, id_cond) values ('Insolação',5,0,20,'Diminui a prioridade dos ataques de planta', null); -- 10


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

-- ESPECIES

insert into especie(nome_esp, atk1, atk2, forca,  vida_max, veloc, id_tipo, foto) values ('Noz Obstáculo', 2, 19, 0,60,1,1, '../ImagensPVZ/monstrinhos_campo/Protetor/Wall-NutPvZH.png'); -- 1
insert into especie(nome_esp, atk1, atk2, forca,  vida_max, veloc, id_tipo, foto) values ('Cacto', 3, 6, 4,50,3,1, '../ImagensPVZ/monstrinhos_campo/Protetor/HD_Cactus(PvZH).png'); -- 2
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Tricenourátops', 11, 3, 2,20,2,1, '../ImagensPVZ/monstrinhos_campo/Protetor/Carrot_cutie.png'); -- 3
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo,foto) values ('Planterna', 1, 18, 6,30,3,1, '../ImagensPVZ/monstrinhos_campo/Protetor/Plantern_(PvZH)_HD.png'); -- 4
insert into especie(nome_esp, atk1, forca , vida_max, veloc, id_tipo, foto) values ('Batatamina', 2, 0,10,1,1, '../ImagensPVZ/monstrinhos_campo/Protetor/HD_Potato_Mine.png'); -- 5


insert into especie(nome_esp, atk1, forca , vida_max, veloc, id_tipo, foto) values ('Lança-Banana', 4, 0,30,2,2, '../ImagensPVZ/monstrinhos_campo/Explosivo/Banana_Launcher_PvZH.png'); -- 6
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Coronel Milho', 3, 9, 12,60,8,2, '../ImagensPVZ/monstrinhos_campo/Explosivo/HD_Kernel_Corn.png'); -- 7
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo,foto) values ('Nabossauro Bebê', 11, 20, 3,20,3,2, '../ImagensPVZ/monstrinhos_campo/Explosivo/Velociradish.png'); -- 8
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Uvas Azedas', 3, 6, 4,20,4,2, '../ImagensPVZ/monstrinhos_campo/Explosivo/uvas.png'); -- 9
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Mirtilartista',3, 6, 6,30,3,2, '../ImagensPVZ/monstrinhos_campo/Explosivo/HD_Bluesberry.png'); -- 10


insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Disparervilha', 5, 6, 2,10,1,3, '../ImagensPVZ/monstrinhos_campo/Crescida/HD_Peashooter_(PvZH).png'); -- 11
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Ervilhadora', 5, 6, 10,40,5,3, '../ImagensPVZ/monstrinhos_campo/Crescida/Gatling_Pea_cardface.png'); -- 12
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Batatossauro', 17 ,2, 10,50,6,3, '../ImagensPVZ/monstrinhos_campo/Crescida/Tater_titan.png'); -- 13
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Repolho Boxeador', 9, 2, 4,10,1,3, '../ImagensPVZ/monstrinhos_campo/Crescida/Hd_bonk.png'); -- 14
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Aneis de Cebola', 5, 1, 8,40,4,3, '../ImagensPVZ/monstrinhos_campo/Crescida/Onion_Rings_HD.png'); -- 15


insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Carambola Cadente', 3, 7, 5,20,5,4, '../ImagensPVZ/monstrinhos_campo/Esperto/Shooting_Starfruit_HD.png'); -- 16
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Boca-de-Dragão', 3, 4, 6,30,4,4, '../ImagensPVZ/monstrinhos_campo/Esperto/SnapdragonPvZH.png'); -- 17
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Gelervilha', 7, 6, 4,20,2,4, '../ImagensPVZ/monstrinhos_campo/Esperto/SnowpeaHD.png'); -- 18
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Pitaya de Matéria-Escura', 7, 1, 12,60,8,4, '../ImagensPVZ/monstrinhos_campo/Esperto/Deep_Space_Dragonfruit_HD.png'); -- 19
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Typha', 5, 8, 6,20,3,4, '../ImagensPVZ/monstrinhos_campo/Esperto/HD_Cattail_(PvZH).png'); -- 20


insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Girassol', 9, 10, 2,10,1,5, '../ImagensPVZ/monstrinhos_campo/Solar/Sunflower.png'); -- 21
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Flor Elétrica', 9, 6, 6,50,4,5, '../ImagensPVZ/monstrinhos_campo/Solar/Power_Flower.png'); -- 22
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Babosassauro', 11, 20, 8,70,5,5, '../ImagensPVZ/monstrinhos_campo/Solar/Aloesauru.png'); -- 23
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Carnívora', 11, 4, 5,30,4,5, '../ImagensPVZ/monstrinhos_campo/Solar/Chomper.png'); -- 24
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zoiudo', 17, 9, 4,10,2,5, '../ImagensPVZ/monstrinhos_campo/Solar/EyeSpore.png'); -- 25

-- feroz
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Cabra', 11, 17, 2,10,0,6, '../ImagensPVZ/monstrinhos_campo/feroz/cabra.png'); -- 26
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Planador Cabra 3000', 13, 17, 3,30,3,6, '../ImagensPVZ/monstrinhos_campo/feroz/cabrafuturo.png'); -- 27
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Brontaço', 11, 19, 10,50,5,6, '../ImagensPVZ/monstrinhos_campo/feroz/Montobronto.png'); -- 28
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbi Assombrado', 17, 7, 4,10,2,6, '../ImagensPVZ/monstrinhos_campo/feroz/zumbifantasma.png'); -- 29
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Cavaleiro do Golfinho', 7, 11, 6,20,2,6, '../ImagensPVZ/monstrinhos_campo/feroz/zumbigolfinho.png'); -- 30
-- Miolástico
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Químico Louco', 13, 14, 6,50,4,7, '../ImagensPVZ/monstrinhos_campo/miolástico/cientista.png'); -- 31
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbolha', 17, 12, 4,50,3,7, '../ImagensPVZ/monstrinhos_campo/miolástico/Zomblob.png'); -- 32
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbi Guarda-Sol', 15, 19, 6,60,4,7, '../ImagensPVZ/monstrinhos_campo/miolástico/zumbiguardasol.png'); -- 33
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbinho Leprechaun', 17, 4, 3,20,1,7, '../ImagensPVZ/monstrinhos_campo/miolástico/leprechau.png'); -- 34
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Paparazzumbi', 11, 12, 2,10,1,7, '../ImagensPVZ/monstrinhos_campo/miolástico/Paparazzi.png'); -- 35
-- DOIDÕES
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Gigante Gasoso', 17, 1, 5,50,6,8, '../ImagensPVZ/monstrinhos_campo/doidões/zumbigigantetóxico.png'); -- 36
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbi Soldado', 13, 18, 6,40,5,8, '../ImagensPVZ/monstrinhos_campo/doidões/soldado.png'); -- 37
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbi Dançarino', 15, 16, 6,10,3,8, '../ImagensPVZ/monstrinhos_campo/doidões/zumbidodisco.png'); -- 38
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Discotron 3000', 15, 13, 10,50,6,8, '../ImagensPVZ/monstrinhos_campo/doidões/DiscoTron3000.png'); -- 39
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Valquíria', 20, 15, 0,60,4,8, '../ImagensPVZ/monstrinhos_campo/doidões/valkiria.png'); -- 40
-- VIGOROSO
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbi Sumô', 19, 20, 4,30,2,9, '../ImagensPVZ/monstrinhos_campo/vigoroso/zumbisumo.png'); -- 41
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Aspirante a Herói', 17, 12, 12,10,7,9, '../ImagensPVZ/monstrinhos_campo/vigoroso/devastador.png'); -- 42
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('All-Star Cósmico', 19, 16, 4,20,3,9, '../ImagensPVZ/monstrinhos_campo/vigoroso/allstarcosmico.png'); -- 43
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Faraó Imortal', 9, 18, 8,60,6,9, '../ImagensPVZ/monstrinhos_campo/vigoroso/faraó.png'); -- 44
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbi Mascote de Time', 11, 20, 3,40,3,9, '../ImagensPVZ/monstrinhos_campo/vigoroso/zumbinhogalinha.png'); -- 45
-- FURTIVOS
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbinho Salsicha', 11, 17, 4,20,2,10, '../ImagensPVZ/monstrinhos_campo/furtivos/zumbinhosalcinha.png'); -- 46
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Galinha Zumbi', 17, 11, 4,20,1,10, '../ImagensPVZ/monstrinhos_campo/furtivos/galinha.png'); -- 47
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Galo de Fogo', 9, 11, 6,10,4,10, '../ImagensPVZ/monstrinhos_campo/furtivos/galodefogo.png'); -- 48
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Yeti Criogênico', 7, 19, 6,40,5,10, '../ImagensPVZ/monstrinhos_campo/furtivos/zumbidegelo.png'); -- 49
insert into especie(nome_esp, atk1, atk2, forca , vida_max, veloc, id_tipo, foto) values ('Zumbô SubTubarãoTrônico', 11, 13, 10,50,6,10, '../ImagensPVZ/monstrinhos_campo/furtivos/HDSHARK.png'); -- 50

update especie set id_evo = 10 where id_esp = 9; -- Uvas Azedas -> Mirtilartista

update especie set id_evo = 23 where id_esp = 8; -- Nabossauro -> Babosassauro

update especie set id_evo = 22 where id_esp = 21; -- Girassol -> Flor Eletrica

update especie set id_evo = 18 where id_esp = 11; -- Disarervilha -> Gelervilha
update especie set id_evo = 12 where id_esp = 18; -- Gelervilha -> Ervilhadora

update especie set id_evo = 19 where id_esp = 17; -- Boca-de-Dragão -> Pitaya

update especie set id_evo = 27 where id_esp = 26; --  Cabra -> Planador Cabra 3000

update especie set id_evo = 36 where id_esp = 32; --  Zumbolha -> Gigante Gasoso

update especie set id_evo = 39 where id_esp = 38; --  Dançarino -> Discotron 3000

update especie set id_evo = 41 where id_esp = 35; --  Paparazzumbi -> Sumô
update especie set id_evo = 42 where id_esp = 41; --  Sumô -> Aspirante a Herói

update especie set id_evo = 48 where id_esp = 47; --  Galinha -> Galo

-- CRIANDO OS INIMIGOS

-- RAISSA
insert into usuario(nome_usr, id_usr, senha, cor_favorita, dinheiro, foto) values ('Raissa', 1, "pudim", '#ff00b3', 0, '../ImagensPVZ/Inimigos/raissa.png');

insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Pudim', 1, 8, 20);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Pudim 2', 1, 32, 50);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Pudim O Maior', 1, 48, 10);

insert into Inimigo(txt_entra, txt_mata, txt_morre) values ("Oii! Meus pudins polimórficos vão acabar com você!", "Sabia que venceria. Você nunca venceria essa sobrecarga de métodos!", "Parece que te subestimei! Mas esse é só o começo.");

-- JACQUE

insert into usuario(nome_usr, id_usr, senha, cor_favorita, dinheiro, foto) values ('Jacque', 2, "banquinhodosdadinhos", '#5bff50', 0, '../ImagensPVZ/Inimigos/jacque.png' );

insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Creaitinho', 2, 2, 20);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Readzinho', 2, 20, 20);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Updatonho', 2, 41, 30);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Deletosa', 2, 22, 50);

insert into Inimigo(txt_entra, txt_mata, txt_morre) values ("Olá! Você sabe quais são as operações do CRUD?", "Parece que meu time deu Truncate no seu", "Ótima luta. Você sabe mesmo de modelagem");

-- MARCIO

insert into usuario(nome_usr, id_usr, senha, cor_favorita, dinheiro, foto) values ('Marcio', 3, "euamorevisaoonline", '#307cff', 0, '../ImagensPVZ/Inimigos/marcio.png' );

insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Hagá Tê Ême Éle', 3, 26, 10);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Cê Ésse Ésse', 3, 15, 40);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Javskri Ptchê', 3, 39, 50);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Pedro Henrique Pereira', 3, 19, 60);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Revisinho Olinu', 3, 46, 20);
insert into pokemon(nome_pkm, id_usr, id_esp, vida_atual) values ('Rhiana', 3, 50, 50);


insert into Inimigo(txt_entra, txt_mata, txt_morre) values ("Esse será o seu teste final. PREPARE-SE", "Você não revisou o suficiente.", "Meus parabens. Você se mostrou capaz de dominar a programação web.");
