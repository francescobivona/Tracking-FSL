<?php
/**
 * FILE: dashboardStudente.php
 * DESCRIZIONE: Dashboard principale dello studente
 * Verifica l'autenticazione dello studente e carica tutte le attività e dati personali
 * Utilizza fetch() per caricare dinamicamente i contenuti da altri file PHP
 */

session_start();
include 'config.php';

// ========== CONTROLLO DI ACCESSO ==========
// Verifica che uno studente sia loggato
// Se non è loggato, reindirizza al login
if (!isset($_SESSION['loggatoStudente']) || $_SESSION['loggatoStudente'] !== true) {
    header("Location: login.html");
    exit();
}

// ========== RECUPERO DATI DALLA SESSIONE ==========
// L'ID dello studente è salvato in sessione durante il login
$username = $_SESSION['username'];

// ========== QUERY PER RECUPERARE L'ID DELLO STUDENTE ==========
// Metodo classico con mysqli_query (coerente con lo stile del progetto)
$sql = "SELECT id_studente FROM studente WHERE username = '$username'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$idStudente = $row['id_studente'];

// ========== QUERY PER RECUPERARE LE ATTIVITÀ ==========
// JOIN COMPLESSO:
// - FROM attivita a: seleziona dalla tabella delle attività (alias 'a')
// - INNER JOIN partecipazione p: unisce con la tabella partecipazione (alias 'p')
//   Condizione: a.id_attivita = p.id_attivita (attività partecipate dallo studente)
// - WHERE p.id_studente = '$idStudente': filtra per lo studente loggato
// - ORDER BY a.data_inizio DESC: ordina dal più recente al più vecchio
$sql_att = "SELECT a.azienda, a.descrizione, a.data_inizio, a.data_fine, a.n_ore, a.ore_fatte, a.modalita, a.convenzione 
            FROM attivita a 
            INNER JOIN partecipazione p ON a.id_attivita = p.id_attivita 
            WHERE p.id_studente = '$idStudente' 
            ORDER BY a.data_inizio DESC";

// Esegue la query
$result_att = mysqli_query($conn, $sql_att);

// ========== GENERAZIONE DELLA LISTA DELLE ATTIVITÀ ==========
// Controlla se lo studente ha partecipato a attività usando mysqli_num_rows
if (mysqli_num_rows($result_att) == 0) {
    // Se non ci sono attività, mostra un messaggio segnaposto
    echo '<li class="muted placeholder">Il tutor non ha inserito nessuna attività ancora.</li>';
} else {
    // Cicla attraverso ogni attività e genera un elemento <li>
    while ($att = mysqli_fetch_assoc($result_att)) {
        echo '<li class="activity-item">';
        
        // Informazioni principali dell'attività (azienda è il titolo principale)
        echo '<strong>' . $att['azienda'] . '</strong>';
        
        // Descrizione dell'attività
        echo '<div class="muted">' . $att['descrizione'] . '</div>';
        
        // Informazioni su date e ore
        echo '<div class="muted">';
        echo 'Data: ' . $att['data_inizio'] . ' - ' . $att['data_fine'] . ' ';
        echo '| Ore previste: ' . $att['n_ore'] . ' ';
        echo '| Ore fatte: ' . $att['ore_fatte'];
        echo '</div>';
        
        // Modalità e convenzione
        echo '<div class="muted">';
        echo 'Modalità: ' . $att['modalita'] . ' ';
        echo '| Convenzione: ' . $att['convenzione'];
        echo '</div>';
        
        echo '</li>';
    }
}
?>