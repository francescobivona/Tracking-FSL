
<?php
session_start();
include 'config.php';

/**
 * FILE: dashboardTutor.php
 * DESCRIZIONE: Dashboard principale del tutor
 * Verifica l'autenticazione del tutor e carica le attività associate
 * Recupera sia i dati del tutor che la lista delle sue attività
 */

// ========== CONTROLLO DI ACCESSO ==========
// Verifica che un tutor sia loggato
if (!isset($_SESSION['loggatoTutor']) || $_SESSION['loggatoTutor'] !== true) {
    header("Location: login.html");
    exit();
}

// ========== RECUPERO USERNAME DALLA SESSIONE ==========
$username = $_SESSION['username'];

// ========== QUERY 1: RECUPERARE I DATI DEL TUTOR ==========
// Seleziona TUTTI i dati del tutor utilizzando il metodo classico con mysqli_query
$sqlTutor = "SELECT * FROM tutor WHERE username = '$username'";
$resultTutor = mysqli_query($conn, $sqlTutor);
$tutor = mysqli_fetch_assoc($resultTutor);

// Estrae l'ID del tutor per usarlo nelle query successive
$idTutor = $tutor['id_tutor'];

// Inizializza variabili con placeholder nel caso i dati non siano disponibili nel DB
$nomeTutor = isset($tutor['nome']) ? $tutor['nome'] : $username;
$materiaTutor = isset($tutor['materia']) ? $tutor['materia'] : 'Materia';
$emailTutor = isset($tutor['email']) ? $tutor['email'] : 'Email';

// ========== QUERY 2: RECUPERARE LE ATTIVITÀ DEL TUTOR ==========
// Seleziona tutte le attività create da questo tutor ordinate per data (più recenti prima)
$sqlAtt = "SELECT * FROM attivita WHERE id_tutor = '$idTutor' ORDER BY data_inizio DESC";
$resultAtt = mysqli_query($conn, $sqlAtt);
?>