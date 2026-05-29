<?php
/**
 * FILE: caricaAttivitaTutor.php
 * DESCRIZIONE: Carica e visualizza solo le attività create da un tutor specifico
 * Genera dinamicamente elementi <li> che contengono le attività del tutor loggato
 * Questi elementi vengono poi inseriti nel DOM tramite JavaScript fetch
 */

session_start();
include 'config.php';

// ========== CONTROLLO SICUREZZA ==========
// Verifica che solo un tutor loggato possa accedere a questo file
if (!isset($_SESSION['loggatoTutor'])) {
    echo "<li class='muted'>Accesso negato.</li>";
    exit();
}

// ========== RECUPERO ID TUTOR ==========
// Recupera l'ID del tutor dalla sessione (assegnato al login)
$id_tutor = $_SESSION['id_tutor'];

// ========== CARICAMENTO ATTIVITÀ DEL TUTOR ==========
// Query per estrarre solo le attività create da questo tutor
// WHERE id_tutor = '$id_tutor' filtra per il tutor loggato
// ORDER BY data_inizio DESC mostra le attività più recenti prima
$sql = "SELECT titolo, data_inizio, azienda, n_ore, modalita FROM attivita WHERE id_tutor = '$id_tutor' ORDER BY data_inizio DESC";
$result = mysqli_query($conn, $sql);

// ========== GENERAZIONE DELL'OUTPUT HTML ==========
if ($result && mysqli_num_rows($result) > 0) {
    // Ciclo attraverso ogni attività del tutor e genera un elemento <li>
    while ($row = mysqli_fetch_assoc($result)) {
        // Sanificazione dell'output HTML per prevenire XSS
        // htmlspecialchars() converte i caratteri speciali in entità HTML sicure
        $titolo = htmlspecialchars($row['titolo']);
        $azienda = htmlspecialchars($row['azienda']);
        $dataInizio = htmlspecialchars($row['data_inizio']);
        $ore = htmlspecialchars($row['n_ore']);
        $modalita = htmlspecialchars($row['modalita']);

        // Genera l'elemento HTML della singola attività
        echo "<li>";
        echo "<strong>{$titolo}</strong> presso <em>{$azienda}</em> <br>";
        echo "<span class='muted'>Inizio: {$dataInizio} | Ore previste: {$ore} ({$modalita})</span>";
        echo "</li>";
    }
} else {
    // Se il tutor non ha ancora inserito nessuna attività
    echo "<li class='muted'>Nessuna attività registrata al momento.</li>";
}
?>