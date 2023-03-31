CREATE TABLE `user` (
`pk_user_id` INT NOT NULL AUTO_INCREMENT ,
`login` VARCHAR( 40 ) NOT NULL ,
`password` VARCHAR( 80 ) NOT NULL ,
`nom` VARCHAR( 40 ) NOT NULL ,
`prenom` VARCHAR( 40 ) NOT NULL ,
`email` VARCHAR( 80 ) NOT NULL ,
PRIMARY KEY ( `pk_user_id` )
);

CREATE TABLE `participation` (
`pk_participation_id` INT NOT NULL AUTO_INCREMENT ,
`fk_user_id` INT NOT NULL ,
`la5` VARCHAR( 3 ) NOT NULL check (la5 in ('Oui', 'Non')),
`dors5` VARCHAR( 3 ) NOT NULL check (dors5 in ('Oui', 'Non')),
`bouffe5` VARCHAR( 10 ) NOT NULL check (bouffe5 in ('grillades', 'fondue')),
`la13` VARCHAR( 3 ) NOT NULL check (la13 in ('Oui', 'Non')),
`dors13` VARCHAR( 3 ) NOT NULL check (dors13 in ('Oui', 'Non')),
`bouffe13` VARCHAR( 10 ) NOT NULL check (bouffe13 in ('grillades', 'fondue')),
PRIMARY KEY ( `pk_participation_id` )
);

insert into user (login, password, nom, prenom, email) values ('Pumbaa', 'loic1', 'Correvon', 'Loïc', 'pumbaa@net2000.ch');
insert into user (login, password, nom, prenom, email) values ('Al', 'wenger', 'Vadi', 'Aldéric', 'alderic_vadi@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Cidotis', 'paris', 'Christophe', 'Nicolas', 'cidotis@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Brunal', 'crepe', 'Calhau', 'Bruno', 'bcalhau@gmail.com');
insert into user (login, password, nom, prenom, email) values ('Matth', 'vert', 'Neier', 'Matthieu', 'matthieu_neier@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Steph', 'tshirt', 'Diluca', 'Stephane', 'skap1142@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Val', 'slipknot', 'Mosimann', 'Valérie', 'ania8931@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Al1', 'festivals', 'Gentizon', 'Alain', 'alain@gentizon.ch');
insert into user (login, password, nom, prenom, email) values ('Kim', 'mousse', 'Maeder', 'Kim', 'laneuch@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Cora', 'pique', 'Neier', 'Coralie', 'coralie_neier@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Benoit', 'vinasse', 'Joss', 'Benoit', 'benoitjoss@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Lucie', 'balle', '', 'Lucie', 'benoitjoss@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('NiN', 'zelda', 'Vadi', 'Norine', 'themisscool@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('JD', 'juifi', 'Hoffman', 'JD', 'phebounette@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Virg', 'minnie', 'Schornoz', 'Virginie', 'virg.schornoz@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Yvan', 'concert', 'Anderson', 'Yvan', 'ombre_19@hotmail.com');
insert into user (login, password, nom, prenom, email) values ('Stephanie', 'twiggy', '', 'Stephanie', 'ombre_19@hotmail.com');

CREATE TABLE `forum` (
`pk_forum_id` INT NOT NULL AUTO_INCREMENT ,
`fk_forum_id` INT NOT NULL ,
`fk_user_id` INT NOT NULL ,
`title` VARCHAR( 80 ) NOT NULL ,
`text` TEXT NOT NULL ,
PRIMARY KEY ( `pk_forum_id` )
);

CREATE TABLE `user_forum` (
`pk_user_forum_id` INT NOT NULL AUTO_INCREMENT ,
`fk_user_id` INT NOT NULL ,
`fk_forum_id` INT NOT NULL ,
`nb_reponse` INT NOT NULL ,
PRIMARY KEY ( `pk_user_forum_id` ),
UNIQUE (fk_user_id, fk_forum_id)
)

alter table participation modify la5 varchar(3 byte) not null check (la5 in ('Oui', 'Non'))
alter table participation modify dors5 varchar(3 byte) not null check (dors5 in ('Oui', 'Non'))
alter table participation modify bouffe5 varchar(10 byte) not null check (bouffe5 in ('grillades', 'fondue'))
alter table participation modify la13 varchar(3 byte) not null check (la13 in ('Oui', 'Non'))
alter table participation modify dors13 varchar(3 byte) not null check (dors13 in ('Oui', 'Non'))
alter table participation modify bouffe13 varchar(10 byte) not null check (bouffe13 in ('grillades', 'fondue'))

