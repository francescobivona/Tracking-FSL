<?php
/**
 * FILE: caricaDatiTutor.php
 * DESCRIZIONE: Carica e visualizza i dati personali del tutor loggato
 * Genera dinamicamente i dati che vengono inseriti nel DOM tramite JavaScript fetch
 * Mostra: Nome completo, Materia insegnata e Email
 */

session_start();
include 'config.php';

// ========== CONTROLLO SICUREZZA ==========
// Verifica che un tutor sia loggato
// Controlla sia la presenza del flag 'loggatoTutor' che il suo valore boolean
if (!isset($_SESSION['loggatoTutor']) || $_SESSION['loggatoTutor'] !== true) {
    echo "<h2 class='tutor-name'>Accesso negato.</h2>";
    exit();
}

// ========== RECUPERO USERNAME DALLA SESSIONE ==========
// L'username viene salvato in sessione durante il login
$username = $_SESSION['username'];

// ========== QUERY PER RECUPERARE I DATI DEL TUTOR ==========
// Seleziona TUTTI i dati del tutor dalla tabella 'tutor'
// Filtra per username (che è univoco per ogni tutor)
$sqlTutor = "SELECT * FROM tutor WHERE username = '$username'";
$resultTutor = mysqli_query($conn, $sqlTutor);

// Recupera il risultato come array associativo
$tutor = mysqli_fetch_assoc($resultTutor);

// ========== GENERAZIONE DELL'OUTPUT HTML ==========
if ($tutor) {
    // Sanificazione degli output HTML per prevenire XSS
    // htmlspecialchars() converte i caratteri speciali in entità HTML sicure
    
    // Nome e cognome vengono concatenati con uno spazio
    $nomeCompleto = htmlspecialchars($tutor['nome'] . " " . $tutor['cognome']);
    
    // Materia insegnata dal tutor
    $materia = htmlspecialchars($tutor['materia']);
    
    // Email sanificata
    $email = htmlspecialchars($tutor['email']);

    // Genera gli elementi HTML che verranno inseriti nel DOM
    echo "<h2 class='tutor-name' id='tutor-name'>{$nomeCompleto}</h2>";
    echo "<div class='tutor-meta' id='tutor-materia'>{$materia}</div>";
    echo "<div class='tutor-meta' id='tutor-email'>{$email}</div>";
} else {
    // Se il tutor non viene trovato nel database
    echo "<h2 class='tutor-name'>Tutor non trovato.</h2>";
}
?>