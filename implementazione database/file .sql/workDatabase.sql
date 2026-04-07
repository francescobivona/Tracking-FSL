CREATE DATABASE TrackingFSL;

CREATE TABLE IF NOT EXISTS tutor(
	id_tutor int PRIMARY KEY,
    nome varchar(50) not null,
    cognome varchar(50) not null,
    email varchar(50) not null,
    data_nascita date not null,
    luogo_nascita varchar(30) not null,
    sesso ENUM('M', 'F', 'Altro') NOT null,
    materia varchar(20) not null,
    scuola varchar(60) not null,
    is_admin boolean DEFAULT(false) not null,
    titolo_studio ENUM('Licenza media', 'diploma', 'laurea', 'laurea triennale', 'dottorato') null,
    access_password varchar(10) not null,
    username varchar(16) not null
);

CREATE TABLE IF NOT EXISTS studente(
	id_studente int PRIMARY KEY,
    nome varchar(50) not null,
    cognome varchar(50) not null,
    data_nascita date not null,
    luogo_nascita varchar(30) not null,
    sesso ENUM('M', 'F', 'Altro') NOT null,
    email varchar(50) not null,
    scuola varchar(60) not null,
    classe ENUM('III', 'IV', 'V') not null,
    sezione ENUM('A', 'B', 'C', 'D') not null,
    indirizzo_studio varchar(30) not null,
    n_ore_fsl tinyint(3) not null,
    n_ore_fatte decimal(4,1) DEFAULT(0) not null,
    access_password varchar(10) not null,
    username varchar(16) not null,
    id_tutor int not null,
    CONSTRAINT fk_studente_tutor FOREIGN KEY(id_tutor) REFERENCES tutor(id_tutor) ON DELETE RESTRICT ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS attivita(
	id_attivita int PRIMARY KEY,
    descrizione text not null,
    data_inizio date DEFAULT(CURRENT_DATE) not null,
    data_fine date DEFAULT(CURRENT_DATE) not null,
    n_ore tinyint(3) not null,
    modalita ENUM('Presenza', 'Online') NOT null,
    azienda varchar(50) not null,
    ore_fatte decimal(4,1) DEFAULT(0) not null,
    convenzione varchar(7) not null,
    id_tutor int not null,
    CONSTRAINT fk_attivita_tutor FOREIGN KEY(id_tutor) REFERENCES tutor(id_tutor) ON DELETE RESTRICT ON UPDATE RESTRICT
);

CREATE TABLE IF not EXISTS partecipazione(
    id_studente int not null,
    id_attivita int not null,
    PRIMARY KEY(id_studente, id_attivita),
    CONSTRAINT fk_partecipazione_studente FOREIGN KEY(id_studente) REFERENCES studente(id_studente) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_partecipazione_attivita FOREIGN KEY(id_attivita) REFERENCES attivita(id_attivita) ON DELETE RESTRICT ON UPDATE RESTRICT
);

INSERT INTO tutor(id_tutor, nome, cognome, email, data_nascita, luogo_nascita, sesso, materia, scuola, is_admin, titolo_studio, access_password, username) 
VALUES

INSERT INTO studente(id_studente, nome, cognome, data_nascita, luogo_nascita, sesso, email, scuola, classe, sezione, indirizzo_studio, n_ore_fsl, n_ore_fatte, access_password, username, id_tutor) 
VALUES

INSERT INTO attivita(id_attivita, descrizione, data_inizio, data_fine, n_ore, modalita, azienda, ore_fatte, convenzione, id_tutor) 
VALUES 

INSERT INTO partecipazione(id_studente, id_attivita) 
VALUES