<?php
    /**
     * FILE: config.php
     * DESCRIZIONE: File di configurazione centrale del progetto FSL (Formazione e Sviluppo Locale)
     * Gestisce la connessione al database MySQL e fornisce funzioni di sicurezza per la gestione delle password
     */

    // ========== CONFIGURAZIONE DATABASE ==========
    $mysql_host = "localhost";    // Host del server MySQL (localhost per sviluppo)
    $mysql_user = "root";          // Utente MySQL
    $mysql_pass = "";              // Password MySQL (vuota in ambiente di sviluppo)
    $mysql_db = "TrackingFSL";     // Nome del database
    
    // Stabilisce la connessione al database MySQL
    $conn = mysqli_connect($mysql_host, $mysql_user, $mysql_pass, $mysql_db);
    
    // Verifica la riuscita della connessione, altrimenti termina con messaggio di errore
    if(!$conn){
        die("Connection failed: " . mysqli_connect_error());
    }

    // ========== FUNZIONI DI SICUREZZA PER LE PASSWORD ==========
    
    /**
     * Cripta una password utilizzando l'algoritmo bcrypt (password_hash)
     * bcrypt è un algoritmo sicuro e lento, resistente agli attacchi brute-force
     * 
     * @param string $password Password in chiaro da crittare
     * @return string Password crittografata con hash bcrypt
     */
    function criptaPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Verifica una password confrontandola con l'hash memorizzato nel database
     * Usa la funzione password_verify() per una comparazione sicura degli hash
     * 
     * @param string $password Password in chiaro inserita dall'utente
     * @param string $hash Hash memorizzato nel database
     * @return bool True se la password è corretta, False altrimenti
     */
    function verificaPassword($password, $hash) {
        return password_verify($password, $hash);
    }
?>