<?php
require_once 'config.php';

// =============================================
// API PRINCIPALE - FORMATIONS, SERVICES, PAIEMENTS
// =============================================

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    // === PUBLICS ===
    case 'get_formations':
        getFormations();
        break;
    case 'get_services':
        getServices();
        break;
    case 'contact_submit':
        submitContact();
        break;
    case 'get_stats':
        getPublicStats();
        break;
        
    // === ÉTUDIANT ===
    case 'souscrire_formation':
        requireLogin();
        souscireFormation();
        break;
    case 'souscrire_service':
        requireLogin();
        souscireService();
        break;
    case 'get_mes_formations':
        requireLogin();
        getMesFormations();
        break;
    case 'get_mes_services':
        requireLogin();
        getMesServices();
        break;
    case 'get_emploi_temps':
        requireLogin();
        getEmploiDuTemps();
        break;
    case 'get_documents':
        requireLogin();
        getDocuments();
        break;
    case 'get_notifications':
        requireLogin();
        getNotifications();
        break;
    case 'mark_notification_read':
        requireLogin();
        markNotificationRead();
        break;
    case 'update_profile':
        requireLogin();
        updateProfile();
        break;
    case 'get_my_paiements':
        requireLogin();
        getMyPaiements();
        break;
        
    // === ADMIN ===
    case 'admin_get_stats':
        requireAdmin();
        getAdminStats();
        break;
    case 'admin_get_users':
        requireAdmin();
        getAdminUsers();
        break;
    case 'admin_update_user':
        requireAdmin();
        updateUser();
        break;
    case 'admin_delete_user':
        requireAdmin();
        deleteUser();
        break;
    case 'admin_create_formation':
        requireAdmin();
        createFormation();
        break;
    case 'admin_update_formation':
        requireAdmin();
        updateFormation();
        break;
    case 'admin_delete_formation':
        requireAdmin();
        deleteFormation();
        break;
    case 'admin_create_service':
        requireAdmin();
        createService();
        break;
    case 'admin_delete_service':
        requireAdmin();
        deleteService();
        break;
    case 'admin_create_emploi':
        requireAdmin();
        createEmploiDuTemps();
        break;
    case 'admin_delete_emploi':
        requireAdmin();
        deleteEmploi();
        break;
    case 'admin_get_paiements':
        requireAdmin();
        getAllPaiements();
        break;
    case 'admin_update_paiement':
        requireAdmin();
        updatePaiement();
        break;
    case 'admin_get_emplois':
        requireAdmin();
        getAllEmplois();
        break;
    case 'admin_upload_document':
        requireAdmin();
        uploadDocument();
        break;
    case 'admin_delete_document':
        requireAdmin();
        deleteDocument();
        break;
    case 'admin_send_notification':
        requireAdmin();
        sendNotificationToUser();
        break;
    default:
        jsonResponse(['error' => 'Action non reconnue'], 400);
}

// =============================================
// FONCTIONS PUBLIQUES
// =============================================

function getFormations() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM formations WHERE statut = 'actif' ORDER BY created_at DESC");
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

function getServices() {
    $db = getDB();
    $stmt = $db->query("SELECT * FROM services WHERE statut = 'actif' ORDER BY created_at DESC");
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

function submitContact() {
    $nom = sanitize($_POST['nom'] ?? '');
    $email = sanitize($_POST['email'] ?? '');
    $sujet = sanitize($_POST['sujet'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    
    if (empty($nom) || empty($email) || empty($message)) {
        jsonResponse(['success' => false, 'message' => 'Champs requis manquants.']);
    }
    
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO contacts (nom, email, sujet, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nom, $email, $sujet, $message]);
    
    jsonResponse(['success' => true, 'message' => 'Message envoyé avec succès! Nous vous répondrons sous 24h.']);
}

function getPublicStats() {
    $db = getDB();
    $stats = [
        'formations' => $db->query("SELECT COUNT(*) FROM formations WHERE statut='actif'")->fetchColumn(),
        'etudiants' => $db->query("SELECT COUNT(*) FROM users WHERE role='etudiant' AND statut='actif'")->fetchColumn(),
        'services' => $db->query("SELECT COUNT(*) FROM services WHERE statut='actif'")->fetchColumn(),
        'inscriptions' => $db->query("SELECT COUNT(*) FROM inscriptions WHERE statut='confirme'")->fetchColumn(),
    ];
    jsonResponse(['success' => true, 'data' => $stats]);
}

// =============================================
// FONCTIONS ÉTUDIANT
// =============================================

function souscireFormation() {
    $formationId = (int)($_POST['formation_id'] ?? 0);
    $methode = sanitize($_POST['methode'] ?? 'Mobile Money');
    $userId = $_SESSION['user_id'];
    
    $db = getDB();
    
    // Vérifier si déjà inscrit
    $stmt = $db->prepare("SELECT id FROM inscriptions WHERE user_id = ? AND formation_id = ?");
    $stmt->execute([$userId, $formationId]);
    if ($stmt->fetch()) {
        jsonResponse(['success' => false, 'message' => 'Vous êtes déjà inscrit à cette formation.']);
    }
    
    // Récupérer prix
    $stmt = $db->prepare("SELECT prix, titre FROM formations WHERE id = ?");
    $stmt->execute([$formationId]);
    $formation = $stmt->fetch();
    
    if (!$formation) {
        jsonResponse(['success' => false, 'message' => 'Formation introuvable.']);
    }
    
    $reference = generateReference();
    
    // Créer paiement
    $stmt = $db->prepare("INSERT INTO paiements (user_id, type_item, item_id, montant, methode_paiement, reference_paiement, statut) VALUES (?, 'formation', ?, ?, ?, ?, 'confirme')");
    $stmt->execute([$userId, $formationId, $formation['prix'], $methode, $reference]);
    $paiementId = $db->lastInsertId();
    
    // Créer inscription
    $stmt = $db->prepare("INSERT INTO inscriptions (user_id, formation_id, paiement_id, statut) VALUES (?, ?, ?, 'confirme')");
    $stmt->execute([$userId, $formationId, $paiementId]);
    
    // Notifier l'étudiant
    $stmt = $db->prepare("INSERT INTO notifications (user_id, titre, message, type) VALUES (?, 'Inscription confirmée', ?, 'success')");
    $stmt->execute([$userId, "Votre inscription à la formation \"{$formation['titre']}\" a été confirmée. Référence: $reference"]);
    
    jsonResponse(['success' => true, 'message' => "Inscription réussie! Référence: $reference", 'reference' => $reference]);
}

function souscireService() {
    $serviceId = (int)($_POST['service_id'] ?? 0);
    $methode = sanitize($_POST['methode'] ?? 'Mobile Money');
    $userId = $_SESSION['user_id'];
    
    $db = getDB();
    
    $stmt = $db->prepare("SELECT prix, titre FROM services WHERE id = ?");
    $stmt->execute([$serviceId]);
    $service = $stmt->fetch();
    
    if (!$service) {
        jsonResponse(['success' => false, 'message' => 'Service introuvable.']);
    }
    
    $reference = generateReference();
    
    $stmt = $db->prepare("INSERT INTO paiements (user_id, type_item, item_id, montant, methode_paiement, reference_paiement, statut) VALUES (?, 'service', ?, ?, ?, ?, 'confirme')");
    $stmt->execute([$userId, $serviceId, $service['prix'], $methode, $reference]);
    $paiementId = $db->lastInsertId();
    
    $stmt = $db->prepare("INSERT INTO souscriptions_services (user_id, service_id, paiement_id, statut) VALUES (?, ?, ?, 'confirme')");
    $stmt->execute([$userId, $serviceId, $paiementId]);
    
    $stmt = $db->prepare("INSERT INTO notifications (user_id, titre, message, type) VALUES (?, 'Service souscrit', ?, 'success')");
    $stmt->execute([$userId, "Votre souscription au service \"{$service['titre']}\" a été confirmée. Référence: $reference"]);
    
    jsonResponse(['success' => true, 'message' => "Souscription réussie! Référence: $reference", 'reference' => $reference]);
}

function getMesFormations() {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT f.*, i.statut as statut_inscription, i.date_inscription, p.reference_paiement, p.montant as montant_paye
        FROM inscriptions i 
        JOIN formations f ON i.formation_id = f.id
        LEFT JOIN paiements p ON i.paiement_id = p.id
        WHERE i.user_id = ?
        ORDER BY i.date_inscription DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

function getMesServices() {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT s.*, ss.statut as statut_souscription, ss.date_souscription, p.reference_paiement, p.montant as montant_paye
        FROM souscriptions_services ss 
        JOIN services s ON ss.service_id = s.id
        LEFT JOIN paiements p ON ss.paiement_id = p.id
        WHERE ss.user_id = ?
        ORDER BY ss.date_souscription DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

function getEmploiDuTemps() {
    $db = getDB();
    
    // Récupérer emplois du temps des formations de l'étudiant
    $stmt = $db->prepare("
        SELECT edt.*, f.titre as formation_titre
        FROM emploi_du_temps edt
        LEFT JOIN formations f ON edt.formation_id = f.id
        WHERE edt.formation_id IN (
            SELECT formation_id FROM inscriptions WHERE user_id = ? AND statut = 'confirme'
        ) OR edt.formation_id IS NULL
        ORDER BY FIELD(edt.jour, 'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'), edt.heure_debut
    ");
    $stmt->execute([$_SESSION['user_id']]);
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

function getDocuments() {
    $db = getDB();
    $stmt = $db->prepare("
        SELECT d.*, f.titre as formation_titre
        FROM documents d
        LEFT JOIN formations f ON d.formation_id = f.id
        WHERE d.acces = 'tous' OR d.formation_id IN (
            SELECT formation_id FROM inscriptions WHERE user_id = ? AND statut = 'confirme'
        )
        ORDER BY d.created_at DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

function getNotifications() {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
    $stmt->execute([$_SESSION['user_id']]);
    $notifs = $stmt->fetchAll();
    
    $unread = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND lu = 0");
    $unread->execute([$_SESSION['user_id']]);
    
    jsonResponse(['success' => true, 'data' => $notifs, 'unread_count' => (int)$unread->fetchColumn()]);
}

function markNotificationRead() {
    $id = (int)($_POST['id'] ?? 0);
    $db = getDB();
    
    if ($id === 0) {
        $stmt = $db->prepare("UPDATE notifications SET lu = 1 WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
    } else {
        $stmt = $db->prepare("UPDATE notifications SET lu = 1 WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
    }
    
    jsonResponse(['success' => true]);
}

function updateProfile() {
    $userId = $_SESSION['user_id'];
    $nom = sanitize($_POST['nom'] ?? '');
    $prenom = sanitize($_POST['prenom'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $adresse = sanitize($_POST['adresse'] ?? '');
    
    $db = getDB();
    $stmt = $db->prepare("UPDATE users SET nom=?, prenom=?, phone=?, adresse=?, updated_at=NOW() WHERE id=?");
    $stmt->execute([$nom, $prenom, $phone, $adresse, $userId]);
    
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    
    // Changer mot de passe si fourni
    if (!empty($_POST['new_password'])) {
        $newPass = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->execute([$newPass, $userId]);
    }
    
    jsonResponse(['success' => true, 'message' => 'Profil mis à jour avec succès.']);
}

function getMyPaiements() {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM paiements WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

// =============================================
// FONCTIONS ADMIN
// =============================================

function getAdminStats() {
    $db = getDB();
    $stats = [
        'total_etudiants' => (int)$db->query("SELECT COUNT(*) FROM users WHERE role='etudiant'")->fetchColumn(),
        'total_formations' => (int)$db->query("SELECT COUNT(*) FROM formations")->fetchColumn(),
        'total_services' => (int)$db->query("SELECT COUNT(*) FROM services")->fetchColumn(),
        'total_paiements' => (int)$db->query("SELECT COUNT(*) FROM paiements")->fetchColumn(),
        'revenus_totaux' => (float)$db->query("SELECT COALESCE(SUM(montant),0) FROM paiements WHERE statut='confirme'")->fetchColumn(),
        'inscriptions_confirmees' => (int)$db->query("SELECT COUNT(*) FROM inscriptions WHERE statut='confirme'")->fetchColumn(),
        'messages_non_lus' => (int)$db->query("SELECT COUNT(*) FROM contacts WHERE statut='non_lu'")->fetchColumn(),
        'paiements_en_attente' => (int)$db->query("SELECT COUNT(*) FROM paiements WHERE statut='en_attente'")->fetchColumn(),
        'derniers_inscrits' => $db->query("SELECT nom, prenom, email, created_at FROM users WHERE role='etudiant' ORDER BY created_at DESC LIMIT 5")->fetchAll(),
        'derniers_paiements' => $db->query("SELECT p.*, u.nom, u.prenom FROM paiements p JOIN users u ON p.user_id=u.id ORDER BY p.created_at DESC LIMIT 5")->fetchAll(),
    ];
    jsonResponse(['success' => true, 'data' => $stats]);
}

function getAdminUsers() {
    $db = getDB();
    $role = $_GET['role'] ?? '';
    
    $sql = "SELECT id, nom, prenom, email, phone, role, statut, created_at FROM users";
    $params = [];
    if ($role) {
        $sql .= " WHERE role = ?";
        $params[] = $role;
    }
    $sql .= " ORDER BY created_at DESC";
    
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

function updateUser() {
    $id = (int)($_POST['id'] ?? 0);
    $statut = sanitize($_POST['statut'] ?? '');
    $role = sanitize($_POST['role'] ?? '');
    
    $db = getDB();
    $stmt = $db->prepare("UPDATE users SET statut=?, role=? WHERE id=?");
    $stmt->execute([$statut, $role, $id]);
    jsonResponse(['success' => true, 'message' => 'Utilisateur mis à jour.']);
}

function deleteUser() {
    $id = (int)($_POST['id'] ?? 0);
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM users WHERE id=? AND role != 'admin'");
    $stmt->execute([$id]);
    jsonResponse(['success' => true, 'message' => 'Utilisateur supprimé.']);
}

function createFormation() {
    $titre = sanitize($_POST['titre'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $description_courte = sanitize($_POST['description_courte'] ?? '');
    $duree = sanitize($_POST['duree'] ?? '');
    $prix = (float)($_POST['prix'] ?? 0);
    $niveau = sanitize($_POST['niveau'] ?? 'Débutant');
    $categorie = sanitize($_POST['categorie'] ?? '');
    $places = (int)($_POST['places_disponibles'] ?? 20);
    
    if (empty($titre) || empty($description) || $prix <= 0) {
        jsonResponse(['success' => false, 'message' => 'Données invalides.']);
    }
    
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO formations (titre, description, description_courte, duree, prix, niveau, categorie, places_disponibles) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $description_courte, $duree, $prix, $niveau, $categorie, $places]);
    
    jsonResponse(['success' => true, 'message' => 'Formation créée avec succès.', 'id' => $db->lastInsertId()]);
}

function updateFormation() {
    $id = (int)($_POST['id'] ?? 0);
    $titre = sanitize($_POST['titre'] ?? '');
    $prix = (float)($_POST['prix'] ?? 0);
    $statut = sanitize($_POST['statut'] ?? 'actif');
    $description = sanitize($_POST['description'] ?? '');
    
    $db = getDB();
    $stmt = $db->prepare("UPDATE formations SET titre=?, prix=?, statut=?, description=? WHERE id=?");
    $stmt->execute([$titre, $prix, $statut, $description, $id]);
    jsonResponse(['success' => true, 'message' => 'Formation mise à jour.']);
}

function deleteFormation() {
    $id = (int)($_POST['id'] ?? 0);
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM formations WHERE id=?");
    $stmt->execute([$id]);
    jsonResponse(['success' => true, 'message' => 'Formation supprimée.']);
}

function createService() {
    $titre = sanitize($_POST['titre'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $description_courte = sanitize($_POST['description_courte'] ?? '');
    $prix = (float)($_POST['prix'] ?? 0);
    $categorie = sanitize($_POST['categorie'] ?? '');
    
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO services (titre, description, description_courte, prix, categorie) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $description, $description_courte, $prix, $categorie]);
    jsonResponse(['success' => true, 'message' => 'Service créé.', 'id' => $db->lastInsertId()]);
}

function deleteService() {
    $id = (int)($_POST['id'] ?? 0);
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM services WHERE id=?");
    $stmt->execute([$id]);
    jsonResponse(['success' => true]);
}

function createEmploiDuTemps() {
    $titre = sanitize($_POST['titre'] ?? '');
    $formation_id = (int)($_POST['formation_id'] ?? 0) ?: null;
    $jour = sanitize($_POST['jour'] ?? '');
    $heure_debut = sanitize($_POST['heure_debut'] ?? '');
    $heure_fin = sanitize($_POST['heure_fin'] ?? '');
    $salle = sanitize($_POST['salle'] ?? '');
    $formateur = sanitize($_POST['formateur'] ?? '');
    $date_debut = sanitize($_POST['date_debut'] ?? '');
    $date_fin = sanitize($_POST['date_fin'] ?? '');
    
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO emploi_du_temps (titre, formation_id, jour, heure_debut, heure_fin, salle, formateur, date_debut, date_fin) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $formation_id, $jour, $heure_debut, $heure_fin, $salle, $formateur, $date_debut ?: null, $date_fin ?: null]);
    jsonResponse(['success' => true, 'message' => 'Cours ajouté à l\'emploi du temps.']);
}

function deleteEmploi() {
    $id = (int)($_POST['id'] ?? 0);
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM emploi_du_temps WHERE id=?");
    $stmt->execute([$id]);
    jsonResponse(['success' => true]);
}

function getAllPaiements() {
    $db = getDB();
    $stmt = $db->query("
        SELECT p.*, u.nom, u.prenom, u.email
        FROM paiements p
        JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC
    ");
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

function updatePaiement() {
    $id = (int)($_POST['id'] ?? 0);
    $statut = sanitize($_POST['statut'] ?? '');
    $db = getDB();
    $stmt = $db->prepare("UPDATE paiements SET statut=? WHERE id=?");
    $stmt->execute([$statut, $id]);
    jsonResponse(['success' => true, 'message' => 'Paiement mis à jour.']);
}

function getAllEmplois() {
    $db = getDB();
    $stmt = $db->query("
        SELECT edt.*, f.titre as formation_titre
        FROM emploi_du_temps edt
        LEFT JOIN formations f ON edt.formation_id = f.id
        ORDER BY FIELD(edt.jour,'Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'), edt.heure_debut
    ");
    jsonResponse(['success' => true, 'data' => $stmt->fetchAll()]);
}

function uploadDocument() {
    $titre = sanitize($_POST['titre'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $formation_id = (int)($_POST['formation_id'] ?? 0) ?: null;
    $acces = sanitize($_POST['acces'] ?? 'tous');
    $userId = $_SESSION['user_id'];
    
    // Simuler upload fichier
    $fichier = 'document-' . time() . '.pdf';
    
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO documents (titre, description, fichier, type_fichier, formation_id, uploaded_by, acces) VALUES (?, ?, ?, 'PDF', ?, ?, ?)");
    $stmt->execute([$titre, $description, $fichier, $formation_id, $userId, $acces]);
    
    // Notifier tous les étudiants
    if ($acces === 'tous') {
        $etudiants = $db->query("SELECT id FROM users WHERE role='etudiant' AND statut='actif'")->fetchAll();
        $notifStmt = $db->prepare("INSERT INTO notifications (user_id, titre, message, type) VALUES (?, 'Nouveau document', ?, 'info')");
        foreach ($etudiants as $e) {
            $notifStmt->execute([$e['id'], "Un nouveau document \"$titre\" est disponible."]);
        }
    }
    
    jsonResponse(['success' => true, 'message' => 'Document mis en ligne.']);
}

function deleteDocument() {
    $id = (int)($_POST['id'] ?? 0);
    $db = getDB();
    $stmt = $db->prepare("DELETE FROM documents WHERE id=?");
    $stmt->execute([$id]);
    jsonResponse(['success' => true]);
}

function sendNotificationToUser() {
    $userId = (int)($_POST['user_id'] ?? 0);
    $titre = sanitize($_POST['titre'] ?? '');
    $message = sanitize($_POST['message'] ?? '');
    $type = sanitize($_POST['type'] ?? 'info');
    
    $db = getDB();
    
    if ($userId === 0) {
        // Envoyer à tous
        $users = $db->query("SELECT id FROM users WHERE role='etudiant' AND statut='actif'")->fetchAll();
        $stmt = $db->prepare("INSERT INTO notifications (user_id, titre, message, type) VALUES (?, ?, ?, ?)");
        foreach ($users as $u) {
            $stmt->execute([$u['id'], $titre, $message, $type]);
        }
        jsonResponse(['success' => true, 'message' => 'Notification envoyée à tous les étudiants.']);
    } else {
        $stmt = $db->prepare("INSERT INTO notifications (user_id, titre, message, type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $titre, $message, $type]);
        jsonResponse(['success' => true, 'message' => 'Notification envoyée.']);
    }
}
?>
