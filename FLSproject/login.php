<?php
/**
 * FILE: login.php
 * DESCRIZIONE: Pagina di autenticazione del sistema
 * Gestisce il login per tre ruoli: ADMIN, TUTOR e STUDENTE
 * Verifica le credenziali e crea la sessione utente appropriata
 */

session_start();
include 'config.php';

// ========== LOGOUT LOGIC ==========
// Controlla se è stata richiesta una disconnessione (logout)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    session_unset();     // Rimuove tutte le variabili di sessione
    session_destroy();   // Distrugge la sessione attiva
    header("Location: login.html");
    exit;
}

// ========== REDIRECT PER UTENTI GIÀ LOGGATI ==========
// Se un utente è già loggato, lo reindirizza alla sua dashboard appropriata
if (isset($_SESSION['loggatoAdmin']) && $_SESSION['loggatoAdmin'] === true) {
    header("Location: registrazioneTutor.html");
    exit;
} elseif (isset($_SESSION['loggatoTutor']) && $_SESSION['loggatoTutor'] === true) {
    header("Location: registrazioneStudente.html");
    exit;
} elseif (isset($_SESSION['loggatoStudente']) && $_SESSION['loggatoStudente'] === true) {
    header("Location: dashboardStudente.html");
    exit;
}

// ========== AUTENTICAZIONE ==========
// Verifica che il form sia stato inviato via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanifica gli input per prevenire SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // --- CONTROLLO ADMIN ---
    // Credenziali hardcoded per l'amministratore
    // ADMIN: username = ADMIN, password = admin
    if ($username === 'ADMIN' && $password === 'admin') {
        $_SESSION['loggatoAdmin'] = true;
        $_SESSION['username'] = $username;
        header("Location: registrazioneTutor.html");
        exit;
    }

    // --- CONTROLLO TUTOR ---
    // Ricerca il tutor nel database usando username
    $sqlTutor = "SELECT * FROM tutor WHERE username = '$username'";
    $resultTutor = mysqli_query($conn, $sqlTutor);
    
    if ($resultTutor && mysqli_num_rows($resultTutor) > 0) {
        $tutorData = mysqli_fetch_assoc($resultTutor);
        // Verifica la password usando password_verify (confronto sicuro degli hash)
        if (verificaPassword($password, $tutorData['access_password'])) {
            $_SESSION['loggatoTutor'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['id_tutor'] = $tutorData['id_tutor'];
            header("Location: registrazioneStudente.html");
            exit;
        }
    }

    // --- CONTROLLO STUDENTE ---
    // Ricerca lo studente nel database usando username
    $sqlStudente = "SELECT * FROM studente WHERE username = '$username'";
    $resultStudente = mysqli_query($conn, $sqlStudente);
    
    if ($resultStudente && mysqli_num_rows($resultStudente) > 0) {
        $studenteData = mysqli_fetch_assoc($resultStudente);
        // Verifica la password usando password_verify (confronto sicuro degli hash)
        if (verificaPassword($password, $studenteData['access_password'])) {
            $_SESSION['loggatoStudente'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['id_studente'] = $studenteData['id_studente'];
            header("Location: dashboardStudente.html");
            exit;
        }
    }

    // Se nessun controllo è andato a buon fine, le credenziali sono errate
    echo "<script>alert('Username o password errati!'); window.location.href='login.html';</script>";
    exit;
} else {
    // Se non è una richiesta POST, reindirizza al login
    header("Location: login.html");
    exit;
}
?>