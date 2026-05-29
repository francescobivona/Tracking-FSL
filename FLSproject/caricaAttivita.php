<?php
/**
 * FILE: caricaAttivita.php
 * DESCRIZIONE: Carica e visualizza tutte le attività dal database per l'admin
 * Genera dinamicamente elementi <li> che contengono le attività
 * Questi elementi vengono poi inseriti nel DOM tramite JavaScript fetch
 */

session_start();
include 'config.php';

// ========== CONTROLLO SICUREZZA ==========
// Verifica che solo l'admin possa accedere a questo file
if (!isset($_SESSION['loggatoAdmin'])) {
    echo "<li class='muted'>Accesso negato.</li>";
    exit();
}

// ========== CARICAMENTO ATTIVITÀ ==========
// Query per estrarre tutte le attività ordinate per data (più recente prima)
$sql = "SELECT titolo, data_inizio, azienda, n_ore, modalita FROM attivita ORDER BY data_inizio DESC";
$result = mysqli_query($conn, $sql);

// ========== GENERAZIONE DELL'OUTPUT HTML ==========
if ($result && mysqli_num_rows($result) > 0) {
    // Ciclo attraverso ogni attività e genera un elemento <li>
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
    // Se non ci sono attività nel database
    echo "<li class='muted'>Nessuna attività registrata al momento.</li>";
}
?>