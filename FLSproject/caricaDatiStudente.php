<?php
/**
 * FILE: caricaDatiStudente.php
 * DESCRIZIONE: Carica e visualizza i dati personali dello studente loggato
 * Genera dinamicamente i dati che vengono inseriti nel DOM tramite JavaScript fetch
 * Mostra: Nome completo, Classe, Sezione e Email
 */

session_start();
include 'config.php';

// ========== CONTROLLO SICUREZZA ==========
// Verifica che uno studente sia loggato
// Controlla sia la presenza del flag 'loggatoStudente' che il suo valore boolean
if (!isset($_SESSION['loggatoStudente']) || $_SESSION['loggatoStudente'] !== true) {
    echo "<h2 class='student-name'>Accesso negato.</h2>";
    exit();
}

// ========== RECUPERO USERNAME DALLA SESSIONE ==========
// L'username viene salvato in sessione durante il login
$username = $_SESSION['username'];

// ========== QUERY PER RECUPERARE I DATI DELLO STUDENTE ==========
// Seleziona TUTTI i dati dello studente dalla tabella 'studente'
// Filtra per username (che è univoco per ogni studente)
$sqlStudente = "SELECT * FROM studente WHERE username = '$username'";
$resultStudente = mysqli_query($conn, $sqlStudente);

// Recupera il risultato come array associativo
$studente = mysqli_fetch_assoc($resultStudente);

// ========== GENERAZIONE DELL'OUTPUT HTML ==========
if ($studente) {
    // Sanificazione degli output HTML per prevenire XSS
    // htmlspecialchars() converte i caratteri speciali in entità HTML sicure
    
    // Nome e cognome vengono concatenati con uno spazio
    $nomeCompleto = htmlspecialchars($studente['nome'] . " " . $studente['cognome']);
    
    // Classe e sezione vengono concatenate (es. "3A")
    $classeSezione = htmlspecialchars($studente['classe'] . " " . $studente['sezione']);
    
    // Email sanificata
    $email = htmlspecialchars($studente['email']);

    // Genera gli elementi HTML che verranno inseriti nel DOM
    echo "<h2 class='student-name' id='student-name'>{$nomeCompleto}</h2>";
    echo "<div class='student-meta' id='student-classe'>{$classeSezione}</div>";
    echo "<div class='student-meta' id='student-email'>{$email}</div>";
} else {
    // Se lo studente non viene trovato nel database
    echo "<h2 class='student-name'>Studente non trovato.</h2>";
}
?>