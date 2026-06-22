<?php
// =============================================
// CONFIGURATION BASE DE DONNÉES
// =============================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'labo_formation');
define('BASE_URL', 'http://localhost/labo-formation');

// Connexion PDO
function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Erreur de connexion à la base de données: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

// Démarrer la session si pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonctions utilitaires
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isEtudiant() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'etudiant';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateReference() {
    return 'REF-' . date('Y') . '-' . strtoupper(uniqid());
}

function formatMontant($montant) {
    return number_format($montant, 0, ',', ' ') . ' FCFA';
}

function jsonResponse($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

// Vérifier si admin
function requireAdmin() {
    if (!isLoggedIn() || !isAdmin()) {
        redirect(BASE_URL . '/login.php');
    }
}

// Vérifier si connecté
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        redirect(BASE_URL . '/login.php');
    }
}
?>
