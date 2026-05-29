<?php
/**
 * FILE: registrazioneStudente.php
 * DESCRIZIONE: Pagina di registrazione per nuovi studenti
 * Gestisce il form di registrazione, validazione e inserimento dati nel database
 * Accessibile solo dai tutor loggati
 */

session_start();
include 'config.php';

// ========== CONTROLLO DI ACCESSO ==========
// Verifica che l'utente sia un tutor loggato (altrimenti reindirizza alla loro dashboard)
if(isset($_SESSION['loggatoAdmin'])||isset($_SESSION['loggatoStudente'])){
    if($_SESSION['loggatoAdmin']){
        header("Location: registrazioneTutor.html");
        exit;
    }else if($_SESSION['loggatoStudente']){
        header("Location: dashboardStudente.html");
        exit;
    }
}

// ========== ELABORAZIONE FORM ==========
// Controlla se il form è stato inviato via POST
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Recupera tutti i dati dal form, usando isset() per gestire valori mancanti
    $nome = isset($_POST['nome']) ? $_POST['nome'] : '';
    $cognome = isset($_POST['cognome']) ? $_POST['cognome'] : '';
    $data_nascita = isset($_POST['data_nascita']) ? $_POST['data_nascita'] : '';
    $luogo_nascita = isset($_POST['luogo_nascita']) ? $_POST['luogo_nascita'] : '';
    $sesso = isset($_POST['sesso']) ? $_POST['sesso'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $scuola = isset($_POST['scuola']) ? $_POST['scuola'] : '';
    $classe = isset($_POST['classe']) ? $_POST['classe'] : '';
    $sezione = isset($_POST['sezione']) ? $_POST['sezione'] : '';
    $indirizzo_studio = isset($_POST['indirizzo_studio']) ? $_POST['indirizzo_studio'] : '';
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $id_tutor = isset($_POST['id_tutor']) ? $_POST['id_tutor'] : '';
    
    // ========== VALIDAZIONE DEI DATI ==========
    // Controlla che i campi obbligatori non siano vuoti
    if(empty($nome) || empty($cognome) || empty($email) || empty($username) || empty($password)){
        echo "<script>alert('Errore: Tutti i campi sono obbligatori!'); window.history.back();</script>";
        exit;
    }
    
    // ========== SICUREZZA DELLA PASSWORD ==========
    // Cripta la password usando bcrypt prima di salvarla nel database
    $password_crittata = criptaPassword($password);
    
    // ========== SANIFICAZIONE DEGLI INPUT ==========
    // Escapa i dati per prevenire SQL Injection - NOTA: ideale usare prepared statements
    $nome = mysqli_real_escape_string($conn, $nome);
    $cognome = mysqli_real_escape_string($conn, $cognome);
    $data_nascita = mysqli_real_escape_string($conn, $data_nascita);
    $luogo_nascita = mysqli_real_escape_string($conn, $luogo_nascita);
    $sesso = mysqli_real_escape_string($conn, $sesso);
    $email = mysqli_real_escape_string($conn, $email);
    $scuola = mysqli_real_escape_string($conn, $scuola);
    $classe = mysqli_real_escape_string($conn, $classe);
    $sezione = mysqli_real_escape_string($conn, $sezione);
    $indirizzo_studio = mysqli_real_escape_string($conn, $indirizzo_studio);
    $username = mysqli_real_escape_string($conn, $username);
    $id_tutor = mysqli_real_escape_string($conn, $id_tutor);
    
    // ========== INSERIMENTO NEL DATABASE ==========
    // Query SQL per inserire il nuovo studente nella tabella 'studente'
    $sql = "INSERT INTO studente (nome, cognome, data_nascita, luogo_nascita, sesso, email, scuola, classe, sezione, indirizzo_studio, username, access_password, id_tutor) 
            VALUES ('$nome', '$cognome', '$data_nascita', '$luogo_nascita', '$sesso', '$email', '$scuola', '$classe', '$sezione', '$indirizzo_studio', '$username', '$password_crittata', '$id_tutor')";
    
    // Esegue la query e cattura il risultato
    $result = mysqli_query($conn, $sql);
    
    // ========== GESTIONE DEL RISULTATO ==========
    if($result){
        // Registrazione avvenuta con successo - reindirizza al login
        echo "<script>alert('Registrazione studente avvenuta con successo!'); window.location.href='login.php';</script>";
    }else{
        // Registrazione fallita - mostra l'errore MySQL
        echo "<script>alert('Errore: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}
?>