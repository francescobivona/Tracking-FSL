<?php
/**
 * FILE: caricaAttivitaStudente.php
 * DESCRIZIONE: Carica e visualizza le attività assegnate a uno studente specifico
 * Le attività mostrate sono quelle del tutor a cui lo studente è associato
 * Utilizza un JOIN per recuperare solo le attività pertinenti
 */

session_start();
include 'config.php';

// ========== CONTROLLO SICUREZZA ==========
// Verifica che solo uno studente loggato possa accedere a questo file
if (!isset($_SESSION['loggatoStudente'])) {
    echo "<li class='muted'>Accesso negato.</li>";
    exit();
}

// ========== RECUPERO DATI DELLO STUDENTE ==========
// Recupera l'ID dello studente dalla sessione
$id_studente = $_SESSION['id_studente'];

// ========== QUERY PRINCIPALE CON JOIN ==========
/* 
 * LOGICA DELLA QUERY:
 * - Seleziona le colonne necesarie dalla tabella 'attivita' (a)
 * - Esegue un INNER JOIN con la tabella 'studente' (s)
 *   Condizione: a.id_tutor = s.id_tutor (lo stesso tutor)
 * - Filtra solo le righe dove lo studente loggato (WHERE s.id_studente = '$id_studente')
 * - Ordina per data più recente prima (ORDER BY a.data_inizio DESC)
 * 
 * Questo significa che lo studente vede TUTTE le attività del suo tutor
 */
$sql = "SELECT a.titolo, a.data_inizio, a.azienda, a.n_ore, a.modalita 
        FROM attivita a
        INNER JOIN studente s ON a.id_tutor = s.id_tutor
        WHERE s.id_studente = '$id_studente'
        ORDER BY a.data_inizio DESC";

$result = mysqli_query($conn, $sql);

// ========== GENERAZIONE DELL'OUTPUT HTML ==========
if ($result && mysqli_num_rows($result) > 0) {
    // Ciclo attraverso ogni attività del tutor dello studente
    while ($row = mysqli_fetch_assoc($result)) {
        // Sanificazione dell'output HTML per prevenire XSS
        // htmlspecialchars() converte i caratteri speciali in entità HTML sicure
        $titolo = htmlspecialchars($row['titolo']);
        $azienda = htmlspecialchars($row['azienda']);
        $dataInizio = htmlspecialchars($row['data_inizio']);
        $ore = htmlspecialchars($row['n_ore']);
        $modalita = htmlspecialchars($row['modalita']);

        // Genera i nodi <li> che JavaScript inserirà dentro <ul id="fsl-activities">
        echo "<li>";
        echo "<strong>{$titolo}</strong> presso <em>{$azienda}</em> <br>";
        echo "<span class='muted'>Inizio: {$dataInizio} | Ore previste: {$ore} ({$modalita})</span>";
        echo "</li>";
    }
} else {
    // Se il tutor non ha ancora inserito attività
    echo "<li class='muted'>Il tuo tutor non ha ancora inserito nessuna attività.</li>";
}
?>