<?php
/**
 * FILE: inserimentoAttivita.php
 * DESCRIZIONE: Elabora l'inserimento di una nuova attività nel database
 * Verifica l'autenticazione, valida e sanifica i dati, quindi esegue l'inserimento
 * Accessibile solo da admin e tutor loggati
 */

session_start();
include 'config.php';

// ========== CONTROLLO DI ACCESSO ==========
// Verifica che solo un admin o un tutor loggato possa accedere
// Reindirizza al login se nessuno dei due è loggato
if (!isset($_SESSION['loggatoAdmin']) && !isset($_SESSION['loggatoTutor'])) {
    header("Location: login.html");
    exit();
}

// ========== CONTROLLO DEL METODO HTTP ==========
// Verifica che la richiesta sia una POST (form submission)
// Se è GET, reindirizza al form di inserimento
if($_SERVER['REQUEST_METHOD'] != 'POST'){
    header("Location: inserimentoAttivita.html");
    exit();
}

// ========== RECUPERO DEI DATI DAL FORM ==========
// Recupera tutti i campi del form, sanificandoli con mysqli_real_escape_string
// per prevenire SQL Injection

$titolo = mysqli_real_escape_string($conn, $_POST['titolo']);
$descrizione = mysqli_real_escape_string($conn, $_POST['descrizione']);
$data_inizio = mysqli_real_escape_string($conn, $_POST['data_inizio']);
$data_fine = mysqli_real_escape_string($conn, $_POST['data_fine']);
$ore = mysqli_real_escape_string($conn, $_POST['ore']);
$modalita = mysqli_real_escape_string($conn, $_POST['modalita']);
$azienda = mysqli_real_escape_string($conn, $_POST['azienda']);
$convenzione = mysqli_real_escape_string($conn, $_POST['convenzione']);
$id_tutor = mysqli_real_escape_string($conn, $_POST['id_tutor']);

// ========== VALORE DI DEFAULT ==========
// Inizializza le ore fatte a 0 (l'attività è appena stata creata)
$ore_fatte = 0;

// ========== LOGICA: DETERMINAZIONE DEL TUTOR ==========
// Se l'admin non ha selezionato un tutor, usa il tutor loggato (se è un tutor)
// Questo consente ai tutor di inserire attività senza doversi esporre come admin
if(empty($id_tutor)){
    $username = $_SESSION['username'];
    
    // Query per trovare l'ID del tutor usando il username
    $sqlTutor = "SELECT id_tutor FROM tutor WHERE username = '$username'";
    $resultTutor = mysqli_query($conn, $sqlTutor);
    
    // Se il tutor è trovato, recupera il suo ID
    if ($resultTutor && mysqli_num_rows($resultTutor) > 0) {
        $rowTutor = mysqli_fetch_assoc($resultTutor);
        $id_tutor = $rowTutor['id_tutor'];
    }
}

// ========== INSERIMENTO NEL DATABASE ==========
// Costruisce la query SQL di inserimento
$sqlInsert = "INSERT INTO attivita (
    titolo,
    descrizione, 
    data_inizio, 
    data_fine, 
    n_ore, 
    modalita, 
    azienda, 
    ore_fatte, 
    convenzione, 
    id_tutor
) VALUES (
    '$titolo',
    '$descrizione', 
    '$data_inizio', 
    '$data_fine', 
    '$ore', 
    '$modalita', 
    '$azienda', 
    '$ore_fatte', 
    '$convenzione', 
    '$id_tutor'
)";

// ========== ESECUZIONE QUERY E GESTIONE RISULTATI ==========
// Esegue la query e reindirizza con un alert (stile login.php)
if (mysqli_query($conn, $sqlInsert)) {
    // Inserimento riuscito - mostra alert di successo e reindirizza al form
    echo "<script>alert('Attività inserita con successo!'); window.location.href='inserimentoAttivita.html';</script>";
    exit();
} else {
    // Inserimento fallito - mostra errore MySQL e torna al form
    echo "<script>alert('Errore durante l\'inserimento dell\'attività: " . mysqli_error($conn) . "'); window.location.href='inserimentoAttivita.html';</script>";
    exit();
}
?>