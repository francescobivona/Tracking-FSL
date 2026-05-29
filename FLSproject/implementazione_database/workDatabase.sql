CREATE DATABASE TrackingFSL;

CREATE TABLE IF NOT EXISTS tutor(
	id_tutor int AUTO_INCREMENT PRIMARY KEY,
    nome varchar(50) not null,
    cognome varchar(50) not null,
    email varchar(50) not null,
    data_nascita date not null,
    luogo_nascita varchar(30) not null,
    sesso ENUM('M', 'F', 'Altro') NOT null,
    materia varchar(20) not null,
    scuola varchar(60) not null,
    titolo_studio varchar(20) not null,
    access_password varchar(255) not null,
    username varchar(16) not null
);

CREATE TABLE IF NOT EXISTS studente(
	id_studente int AUTO_INCREMENT PRIMARY KEY,
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
    n_ore_fsl smallint(3) DEFAULT(150) not null,
    n_ore_fatte decimal(4,1) DEFAULT(0.0) not null,
    access_password varchar(255) not null,
    username varchar(16) not null,
    id_tutor int not null,
    CONSTRAINT fk_studente_tutor FOREIGN KEY(id_tutor) REFERENCES tutor(id_tutor) ON DELETE RESTRICT ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS attivita(
	id_attivita int AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(50) NOT NULL,
    descrizione text not null,
    data_inizio date DEFAULT(CURRENT_DATE) not null,
    data_fine date DEFAULT(CURRENT_DATE) not null,
    n_ore smallint(3) not null,
    modalita ENUM('Presenza', 'Online') NOT null,
    azienda varchar(50) not null,
    ore_fatte decimal(4,1) DEFAULT(0.0) not null,
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

INSERT INTO tutor(id_tutor, nome, cognome, email, data_nascita, luogo_nascita, sesso, materia, scuola, titolo_studio, access_password, username) 
VALUES
(658974, 'Marco', 'Rossi', 'mario.rosso@example.com', '1989-05-15', 'Catania', 'M', 'Informatica', 'Abramo Lincoln', 'laurea', '$2y$10$YourHashedPasswordHere', 'ADMIN')

