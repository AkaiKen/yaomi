-- Modèle de données

CREATE TABLE auth_users(
	id					int(11) AUTO_INCREMENT not null primary key,
	login 				varchar(255),
	password 			varchar(255),
	email 				varchar(255),
	app_lang 			varchar(25),
	cards_lang 			varchar(25)
);
CREATE TABLE mdm_cards	(
	id					int(11) AUTO_INCREMENT not null primary key,
	name				varchar(255),
	name_fr				varchar(255)
);
CREATE TABLE mdm_sets(	
	id					int(11) AUTO_INCREMENT not null primary key,
	name				varchar(255),
	name_fr				varchar(255),
	code				varchar(25), -- index
	date				date
);
CREATE INDEX IDX_SET_CODE
on mdm_sets (code);

CREATE TABLE mdm_cards_x_sets(
	id					int(11) AUTO_INCREMENT not null primary key,
	fk_card				int(11) not null references mdm_cards(id),
	fk_extension		int(11) not null references mdm_sets(id),
	card_number			varchar(255)
);

CREATE TABLE app_user_x_cards_extensions(
	id					int(11) AUTO_INCREMENT not null primary key,
	fk_user				int(11) not null references auth_users(id),
	fk_card_instance	int(11) not null references mdm_cards_x_sets(id),
	qty					int(11) default 0
);
	
