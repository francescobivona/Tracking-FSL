CREATE DATABASE TrackingFSL;

CREATE TABLE IF NOT EXISTS utente(
	id_utente int AUTO_INCREMENT PRIMARY KEY,
    nome varchar(50) NOT null,
    cognome varchar(50) NOT null,
    codice_fiscale char(16) NOT null,
    email varchar(50) NOT null,
    data_nascita date not null,
    sesso ENUM('M', 'F', 'Altro') NOT null,
    luogo varchar(20) NOT null,
    classe ENUM('III', 'IV', 'V') null,
    scuola varchar(60) null,
    sezione char(1) null,
    username varchar(16) NOT null,
    wordpass varchar(10) NOT null,
    materia varchar(20) null,
    titolo_studio ENUM('Licenza media', 'diploma', 'laurea', 'laurea triennale', 'dottorato') null,
    indirizzo_studio varchar(12) null,
    data_creazione date NOT null,
    attivo boolean null
);

CREATE TABLE IF NOT EXISTS attivita(
	id_attivita int AUTO_INCREMENT PRIMARY KEY,
    data_inizio date DEFAULT(CURRENT_DATE),
    data_fine date DEFAULT(CURRENT_DATE),
    n_ore decimal(4,1) NOT null,
    modalita ENUM('Presenza', 'Online') NOT null,
    azienda varchar(50) not null,
    descrizione text not null,
    ore_totali decimal(4,1) NOT null,
    id_utente int NOT null,
    CONSTRAINT fk_attivita_utente FOREIGN KEY(id_utente) REFERENCES utente(id_utente) ON DELETE RESTRICT ON UPDATE RESTRICT
);

CREATE TABLE IF NOT EXISTS ruolo(
	id_ruolo int AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('Tutor', 'Studente', 'Admin') NOT null,
    creazione boolean NOT null,
    modifica boolean NOT null,
    cancellazione boolean NOT null,
    visualizzazione boolean NOT null,
    id_utente int NOT null,
    CONSTRAINT fk_ruolo_utente FOREIGN KEY(id_utente) REFERENCES utente(id_utente) ON DELETE RESTRICT ON UPDATE RESTRICT
);

