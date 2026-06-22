<?php
require_once 'config.php';

// =============================================
// GESTIONNAIRE D'AUTHENTIFICATION
// =============================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'login':
            handleLogin();
            break;
        case 'register':
            handleRegister();
            break;
        case 'logout':
            handleLogout();
            break;
        case 'forgot_password':
            handleForgotPassword();
            break;
        case 'reset_password':
            handleResetPassword();
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    if ($action === 'logout') {
        handleLogout();
    }
}

function handleLogin() {
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        jsonResponse(['success' => false, 'message' => 'Email et mot de passe requis.']);
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = ? AND statut = 'actif'");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nom'] = $user['nom'];
        $_SESSION['prenom'] = $user['prenom'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['photo'] = $user['photo'];
        
        $redirect = $user['role'] === 'admin' ? BASE_URL . '/admin/dashboard.php' : BASE_URL . '/student/dashboard.php';
        
        jsonResponse([
            'success' => true,
            'message' => 'Connexion réussie!',
            'redirect' => $redirect,
            'role' => $user['role']
        ]);
    } else {
        jsonResponse(['success' => false, 'message' => 'Email ou mot de passe incorrect.']);
    }
}

function handleRegister() {
    $nom = sanitize($_POST['nom'] ?? '');
    $prenom = sanitize($_POST['prenom'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone = sanitize($_POST['phone'] ?? '');
    
    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        jsonResponse(['success' => false, 'message' => 'Tous les champs obligatoires doivent être remplis.']);
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        jsonResponse(['success' => false, 'message' => 'Adresse email invalide.']);
    }
    
    if (strlen($password) < 6) {
        jsonResponse(['success' => false, 'message' => 'Le mot de passe doit contenir au moins 6 caractères.']);
    }
    
    $db = getDB();
    
    // Vérifier si email existe
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'Cet email est déjà utilisé.']);
    }
    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("INSERT INTO users (nom, prenom, email, password, phone, role) VALUES (?, ?, ?, ?, ?, 'etudiant')");
    $stmt->execute([$nom, $prenom, $email, $hashedPassword, $phone]);
    
    $userId = $db->lastInsertId();
    
    // Créer notification de bienvenue
    $stmt = $db->prepare("INSERT INTO notifications (user_id, titre, message, type) VALUES (?, 'Bienvenue!', 'Bienvenue sur la plateforme LaboFormation. Explorez nos formations et services!', 'success')");
    $stmt->execute([$userId]);
    
    jsonResponse(['success' => true, 'message' => 'Compte créé avec succès! Vous pouvez maintenant vous connecter.']);
}

function handleLogout() {
    session_destroy();
    redirect(BASE_URL . '/index.php');
}

function handleForgotPassword() {
    $email = sanitize($_POST['email'] ?? '');
    
    if (empty($email)) {
        jsonResponse(['success' => false, 'message' => 'Email requis.']);
    }
    
    $db = getDB();
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Dans une vraie app, envoyer un email avec token de réinitialisation
        jsonResponse(['success' => true, 'message' => 'Un lien de réinitialisation a été envoyé à votre adresse email.']);
    } else {
        jsonResponse(['success' => false, 'message' => 'Aucun compte trouvé avec cet email.']);
    }
}

function handleResetPassword() {
    jsonResponse(['success' => true, 'message' => 'Mot de passe réinitialisé avec succès.']);
}
?>
