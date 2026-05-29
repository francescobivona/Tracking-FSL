<?php
/**
 * FILE: tutorDinamici.php
 * DESCRIZIONE: Carica dinamicamente la lista dei tutor disponibili
 * Genera elementi <option> da inserire in una <select> tramite JavaScript fetch
 * Utile per il form di registrazione studente dove deve essere selezionato il tutor
 * 
 * UTILIZZO: Questo file viene chiamato via fetch() da JavaScript in registrazioneStudente.html
 */

include 'config.php';

// ========== QUERY PER ESTRARRE TUTTI I TUTOR ==========
// Seleziona id_tutor, nome e cognome di ogni tutor nel database
$sql = "SELECT id_tutor, nome, cognome FROM tutor";

// Esegue la query
$result = mysqli_query($conn, $sql);

// ========== GENERAZIONE DELLE OPTION ==========
// Cicla attraverso ogni tutor e genera un elemento <option>
// Ogni option ha:
// - value = id_tutor (quello che viene inviato nel form)
// - testo = "Nome Cognome" (quello che vede l'utente)
while($row = mysqli_fetch_assoc($result)) {
    echo "<option value='" . $row['id_tutor'] . "'>" . $row['nome'] . " " . $row['cognome'] . "</option>";
}
?>