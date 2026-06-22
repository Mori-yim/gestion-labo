<?php
require_once '../php/config.php';
requireAdmin();
$userName = $_SESSION['prenom'].' '.$_SESSION['nom'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Dashboard Admin - LaboFormation</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<div class="loading-screen"><div class="loading-logo">L</div><div class="loading-spinner"></div></div>

<!-- TOPBAR -->
<nav class="navbar" style="position:fixed;z-index:200;">
  <a href="../index.php" class="nav-logo"><div class="logo-icon">L</div><div class="logo-text"><span class="logo-name">LaboFormation</span><span class="logo-tagline">Administration</span></div></a>
  <ul class="nav-links">
    <li><a href="../index.php">Accueil</a></li>
    <li><a href="../formations.php">Formations</a></li>
    <li><a href="../services.php">Services</a></li>
    <li><a href="../contact.php">Contact</a></li>
  </ul>
  <div class="nav-actions" style="gap:16px;">
    <div style="position:relative;">
      <button onclick="loadNotifications()" style="background:none;border:none;color:var(--text-secondary);font-size:1.3rem;cursor:pointer;position:relative;">
        🔔<span id="notifBadge" style="position:absolute;top:-5px;right:-5px;background:var(--error);color:white;border-radius:50%;width:18px;height:18px;font-size:.65rem;display:flex;align-items:center;justify-content:center;display:none;">0</span>
      </button>
    </div>
    <span style="color:var(--accent);font-size:.88rem;font-weight:600;">👑 <?= htmlspecialchars($userName) ?></span>
    <a href="../php/auth.php?action=logout" class="btn-login">Déconnexion</a>
    <button class="hamburger" onclick="toggleSidebar()"><span></span><span></span><span></span></button>
  </div>
</nav>

<div class="dashboard-layout">
  <!-- SIDEBAR -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-user">
      <div class="sidebar-avatar"><?= strtoupper(substr($_SESSION['prenom'],0,1).substr($_SESSION['nom'],0,1)) ?></div>
      <div class="sidebar-name"><?= htmlspecialchars($userName) ?></div>
      <div class="sidebar-role">👑 Administrateur</div>
    </div>

    <div class="sidebar-section-label">Principal</div>
    <ul class="sidebar-nav">
      <li><a href="#" class="active" onclick="showSection('dashboard',this)"><span class="nav-icon">📊</span>Dashboard</a></li>
      <li><a href="#" onclick="showSection('profil',this)"><span class="nav-icon">👤</span>Mon Profil</a></li>
    </ul>

    <div class="sidebar-section-label">Gestion</div>
    <ul class="sidebar-nav">
      <li><a href="#" onclick="showSection('utilisateurs',this)"><span class="nav-icon">👥</span>Utilisateurs</a></li>
      <li><a href="#" onclick="showSection('formations_admin',this)"><span class="nav-icon">📚</span>Formations</a></li>
      <li><a href="#" onclick="showSection('services_admin',this)"><span class="nav-icon">⚙️</span>Services</a></li>
      <li><a href="#" onclick="showSection('emploi_temps',this)"><span class="nav-icon">📅</span>Emploi du temps</a></li>
      <li><a href="#" onclick="showSection('documents',this)"><span class="nav-icon">📁</span>Documents</a></li>
    </ul>

    <div class="sidebar-section-label">Finance</div>
    <ul class="sidebar-nav">
      <li><a href="#" onclick="showSection('paiements_admin',this)"><span class="nav-icon">💳</span>Paiements</a></li>
    </ul>

    <div class="sidebar-section-label">Communication</div>
    <ul class="sidebar-nav">
      <li><a href="#" onclick="showSection('notifications_admin',this)"><span class="nav-icon">🔔</span>Notifications</a></li>
    </ul>

    <div class="sidebar-divider"></div>
    <ul class="sidebar-nav">
      <li><a href="../php/auth.php?action=logout" style="color:var(--error)!important;"><span class="nav-icon">🚪</span>Déconnexion</a></li>
    </ul>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content">

    <!-- ===== DASHBOARD ===== -->
    <div class="page-section active" id="section-dashboard">
      <div style="margin-bottom:32px;">
        <h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Tableau de Bord 📊</h1>
        <p style="color:var(--text-secondary);">Vue d'ensemble de la plateforme LaboFormation</p>
      </div>

      <div class="stats-grid" id="dashboardStats">
        <div class="stat-card"><div class="stat-card-label">Étudiants</div><div class="stat-card-value" id="statEtudiants">-</div><div class="stat-card-change">↑ Total inscrits</div><div class="stat-card-icon" style="background:rgba(59,130,246,.15);">👥</div></div>
        <div class="stat-card"><div class="stat-card-label">Formations</div><div class="stat-card-value" id="statFormations">-</div><div class="stat-card-change">↑ Actives</div><div class="stat-card-icon" style="background:rgba(212,160,23,.15);">📚</div></div>
        <div class="stat-card"><div class="stat-card-label">Revenus Totaux</div><div class="stat-card-value" id="statRevenus">-</div><div class="stat-card-change">FCFA confirmés</div><div class="stat-card-icon" style="background:rgba(34,197,94,.15);">💰</div></div>
        <div class="stat-card"><div class="stat-card-label">Paiements en attente</div><div class="stat-card-value" id="statAttente">-</div><div class="stat-card-change">À traiter</div><div class="stat-card-icon" style="background:rgba(245,158,11,.15);">⏳</div></div>
        <div class="stat-card"><div class="stat-card-label">Messages</div><div class="stat-card-value" id="statMessages">-</div><div class="stat-card-change">Non lus</div><div class="stat-card-icon" style="background:rgba(239,68,68,.15);">✉️</div></div>
        <div class="stat-card"><div class="stat-card-label">Inscriptions</div><div class="stat-card-value" id="statInscriptions">-</div><div class="stat-card-change">Confirmées</div><div class="stat-card-icon" style="background:rgba(168,85,247,.15);">✅</div></div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
        <div class="table-container">
          <div class="table-header"><span class="table-title">👥 Derniers inscrits</span></div>
          <table><thead><tr><th>Étudiant</th><th>Email</th><th>Date</th></tr></thead>
          <tbody id="lastUsersTable"><tr><td colspan="3" style="text-align:center;padding:30px;color:var(--text-muted);">Chargement...</td></tr></tbody></table>
        </div>
        <div class="table-container">
          <div class="table-header"><span class="table-title">💳 Derniers paiements</span></div>
          <table><thead><tr><th>Étudiant</th><th>Montant</th><th>Statut</th></tr></thead>
          <tbody id="lastPaiementsTable"><tr><td colspan="3" style="text-align:center;padding:30px;color:var(--text-muted);">Chargement...</td></tr></tbody></table>
        </div>
      </div>
    </div>

    <!-- ===== PROFIL ADMIN ===== -->
    <div class="page-section" id="section-profil">
      <div style="margin-bottom:32px;"><h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Mon Profil 👤</h1></div>
      <div style="max-width:600px;">
        <div style="padding:40px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
          <div style="text-align:center;margin-bottom:32px;">
            <div style="width:90px;height:90px;background:var(--gradient-gold);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:900;color:var(--primary);margin:0 auto 12px;font-family:var(--font-display);"><?= strtoupper(substr($_SESSION['prenom'],0,1).substr($_SESSION['nom'],0,1)) ?></div>
            <div style="font-size:1.1rem;font-weight:700;"><?= htmlspecialchars($userName) ?></div>
            <div class="badge badge-gold" style="margin-top:6px;">👑 Administrateur</div>
          </div>
          <form id="profileForm">
            <div class="form-row">
              <div class="form-group"><label class="form-label">Prénom</label><input type="text" name="prenom" class="form-input" value="<?= htmlspecialchars($_SESSION['prenom']) ?>"></div>
              <div class="form-group"><label class="form-label">Nom</label><input type="text" name="nom" class="form-input" value="<?= htmlspecialchars($_SESSION['nom']) ?>"></div>
            </div>
            <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-input" value="<?= htmlspecialchars($_SESSION['email']) ?>" readonly style="opacity:.6;"></div>
            <div class="form-group"><label class="form-label">Téléphone</label><input type="tel" name="phone" class="form-input" placeholder="+237 6XX XXX XXX"></div>
            <div class="form-group"><label class="form-label">Adresse</label><input type="text" name="adresse" class="form-input" placeholder="Votre adresse"></div>
            <div class="form-group"><label class="form-label">Nouveau mot de passe (laisser vide si inchangé)</label><input type="password" name="new_password" class="form-input" placeholder="••••••••" minlength="6"></div>
            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">💾 Sauvegarder</button>
          </form>
        </div>
      </div>
    </div>

    <!-- ===== UTILISATEURS ===== -->
    <div class="page-section" id="section-utilisateurs">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <div><h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Utilisateurs 👥</h1></div>
        <div style="display:flex;gap:12px;">
          <select id="userRoleFilter" class="form-select" style="width:180px;" onchange="loadUsers()">
            <option value="">Tous les rôles</option>
            <option value="etudiant">Étudiants</option>
            <option value="admin">Admins</option>
          </select>
        </div>
      </div>
      <div class="table-container">
        <div class="table-header"><span class="table-title">Liste des utilisateurs</span><span id="usersCount" style="font-size:.85rem;color:var(--text-muted);"></span></div>
        <table><thead><tr><th>Nom & Email</th><th>Téléphone</th><th>Rôle</th><th>Statut</th><th>Inscrit le</th><th>Actions</th></tr></thead>
        <tbody id="usersTable"><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">Chargement...</td></tr></tbody></table>
      </div>
    </div>

    <!-- ===== FORMATIONS ADMIN ===== -->
    <div class="page-section" id="section-formations_admin">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <div><h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Formations 📚</h1></div>
        <button class="btn-primary" onclick="openModal('createFormationModal')">+ Nouvelle formation</button>
      </div>
      <div class="table-container">
        <div class="table-header"><span class="table-title">Toutes les formations</span></div>
        <table><thead><tr><th>Titre</th><th>Catégorie</th><th>Prix</th><th>Niveau</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody id="formationsAdminTable"><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">Chargement...</td></tr></tbody></table>
      </div>
    </div>

    <!-- ===== SERVICES ADMIN ===== -->
    <div class="page-section" id="section-services_admin">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <div><h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Services ⚙️</h1></div>
        <button class="btn-primary" onclick="openModal('createServiceModal')">+ Nouveau service</button>
      </div>
      <div class="table-container">
        <div class="table-header"><span class="table-title">Tous les services</span></div>
        <table><thead><tr><th>Titre</th><th>Catégorie</th><th>Prix</th><th>Statut</th><th>Actions</th></tr></thead>
        <tbody id="servicesAdminTable"><tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">Chargement...</td></tr></tbody></table>
      </div>
    </div>

    <!-- ===== EMPLOI DU TEMPS ===== -->
    <div class="page-section" id="section-emploi_temps">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <div><h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Emploi du Temps 📅</h1></div>
        <button class="btn-primary" onclick="openModal('createEmploiModal')">+ Ajouter un cours</button>
      </div>
      <div class="table-container">
        <div class="table-header"><span class="table-title">Planning des cours</span></div>
        <table><thead><tr><th>Titre</th><th>Formation</th><th>Jour</th><th>Horaires</th><th>Salle</th><th>Formateur</th><th>Actions</th></tr></thead>
        <tbody id="emploiAdminTable"><tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">Chargement...</td></tr></tbody></table>
      </div>
    </div>

    <!-- ===== DOCUMENTS ===== -->
    <div class="page-section" id="section-documents">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:32px;">
        <div><h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Documents 📁</h1></div>
        <button class="btn-primary" onclick="openModal('uploadDocModal')">+ Mettre en ligne</button>
      </div>
      <div id="documentsGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;"></div>
    </div>

    <!-- ===== PAIEMENTS ===== -->
    <div class="page-section" id="section-paiements_admin">
      <div style="margin-bottom:32px;"><h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Paiements 💳</h1></div>
      <div class="table-container">
        <div class="table-header"><span class="table-title">Tous les paiements</span></div>
        <table><thead><tr><th>Référence</th><th>Étudiant</th><th>Type</th><th>Montant</th><th>Méthode</th><th>Statut</th><th>Date</th><th>Action</th></tr></thead>
        <tbody id="paiementsAdminTable"><tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">Chargement...</td></tr></tbody></table>
      </div>
    </div>

    <!-- ===== NOTIFICATIONS ADMIN ===== -->
    <div class="page-section" id="section-notifications_admin">
      <div style="margin-bottom:32px;"><h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Notifications 🔔</h1></div>
      <div style="max-width:700px;">
        <div style="padding:36px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
          <h3 style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:24px;">Envoyer une notification</h3>
          <form id="notifAdminForm">
            <div class="form-group">
              <label class="form-label">Destinataires</label>
              <select name="user_id" id="notifUserId" class="form-select">
                <option value="0">📢 Tous les étudiants</option>
              </select>
            </div>
            <div class="form-group"><label class="form-label">Titre</label><input type="text" name="titre" class="form-input" placeholder="Ex: Rappel de cours" required></div>
            <div class="form-group"><label class="form-label">Message</label><textarea name="message" class="form-textarea" placeholder="Votre message..." required></textarea></div>
            <div class="form-group">
              <label class="form-label">Type</label>
              <select name="type" class="form-select">
                <option value="info">ℹ️ Information</option>
                <option value="success">✅ Succès</option>
                <option value="warning">⚠️ Avertissement</option>
                <option value="error">❌ Erreur</option>
              </select>
            </div>
            <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">📨 Envoyer la notification</button>
          </form>
        </div>
      </div>
    </div>

  </main>
</div>

<!-- MODALS -->
<!-- Créer Formation -->
<div class="modal-overlay" id="createFormationModal">
  <div class="modal"><button class="modal-close">✕</button>
    <h2 class="modal-title">➕ Nouvelle Formation</h2>
    <form id="createFormationForm" style="margin-top:20px;">
      <div class="form-group"><label class="form-label">Titre *</label><input type="text" name="titre" class="form-input" required></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Prix (FCFA) *</label><input type="number" name="prix" class="form-input" required min="0"></div>
        <div class="form-group"><label class="form-label">Durée</label><input type="text" name="duree" class="form-input" placeholder="Ex: 3 mois"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Niveau</label><select name="niveau" class="form-select"><option>Débutant</option><option>Intermédiaire</option><option>Avancé</option></select></div>
        <div class="form-group"><label class="form-label">Catégorie</label><input type="text" name="categorie" class="form-input" placeholder="Ex: Informatique"></div>
      </div>
      <div class="form-group"><label class="form-label">Description courte</label><input type="text" name="description_courte" class="form-input" placeholder="Description en une phrase"></div>
      <div class="form-group"><label class="form-label">Description complète *</label><textarea name="description" class="form-textarea" required></textarea></div>
      <div class="form-group"><label class="form-label">Places disponibles</label><input type="number" name="places_disponibles" class="form-input" value="20" min="1"></div>
      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">✅ Créer la formation</button>
    </form>
  </div>
</div>

<!-- Créer Service -->
<div class="modal-overlay" id="createServiceModal">
  <div class="modal"><button class="modal-close">✕</button>
    <h2 class="modal-title">➕ Nouveau Service</h2>
    <form id="createServiceForm" style="margin-top:20px;">
      <div class="form-group"><label class="form-label">Titre *</label><input type="text" name="titre" class="form-input" required></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Prix (FCFA) *</label><input type="number" name="prix" class="form-input" required min="0"></div>
        <div class="form-group"><label class="form-label">Catégorie</label><input type="text" name="categorie" class="form-input" placeholder="Ex: Développement"></div>
      </div>
      <div class="form-group"><label class="form-label">Description courte</label><input type="text" name="description_courte" class="form-input"></div>
      <div class="form-group"><label class="form-label">Description complète *</label><textarea name="description" class="form-textarea" required></textarea></div>
      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">✅ Créer le service</button>
    </form>
  </div>
</div>

<!-- Créer Emploi du Temps -->
<div class="modal-overlay" id="createEmploiModal">
  <div class="modal"><button class="modal-close">✕</button>
    <h2 class="modal-title">📅 Ajouter un cours</h2>
    <form id="createEmploiForm" style="margin-top:20px;">
      <div class="form-group"><label class="form-label">Titre du cours *</label><input type="text" name="titre" class="form-input" required></div>
      <div class="form-group"><label class="form-label">Formation liée</label><select name="formation_id" id="emploiFormationSelect" class="form-select"><option value="">-- Générale (tous) --</option></select></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Jour</label><select name="jour" class="form-select"><option>Lundi</option><option>Mardi</option><option>Mercredi</option><option>Jeudi</option><option>Vendredi</option><option>Samedi</option><option>Dimanche</option></select></div>
        <div class="form-group"><label class="form-label">Salle</label><input type="text" name="salle" class="form-input" placeholder="Ex: Salle A01"></div>
      </div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Heure début</label><input type="time" name="heure_debut" class="form-input"></div>
        <div class="form-group"><label class="form-label">Heure fin</label><input type="time" name="heure_fin" class="form-input"></div>
      </div>
      <div class="form-group"><label class="form-label">Formateur</label><input type="text" name="formateur" class="form-input" placeholder="Nom du formateur"></div>
      <div class="form-row">
        <div class="form-group"><label class="form-label">Date début</label><input type="date" name="date_debut" class="form-input"></div>
        <div class="form-group"><label class="form-label">Date fin</label><input type="date" name="date_fin" class="form-input"></div>
      </div>
      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">✅ Ajouter au planning</button>
    </form>
  </div>
</div>

<!-- Upload Document -->
<div class="modal-overlay" id="uploadDocModal">
  <div class="modal"><button class="modal-close">✕</button>
    <h2 class="modal-title">📤 Mettre en ligne un document</h2>
    <form id="uploadDocForm" style="margin-top:20px;">
      <div class="form-group"><label class="form-label">Titre du document *</label><input type="text" name="titre" class="form-input" required></div>
      <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-textarea" style="min-height:80px;"></textarea></div>
      <div class="form-group"><label class="form-label">Formation liée (optionnel)</label><select name="formation_id" id="docFormationSelect" class="form-select"><option value="">-- Disponible pour tous --</option></select></div>
      <div class="form-group">
        <label class="form-label">Accès</label>
        <select name="acces" class="form-select">
          <option value="tous">👥 Tous les étudiants</option>
          <option value="formation_specifique">🎯 Formation spécifique uniquement</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Fichier (simulation)</label>
        <input type="text" name="fichier_nom" class="form-input" placeholder="Ex: support-cours-html.pdf" required>
        <small style="color:var(--text-muted);font-size:.75rem;">Dans une vraie installation, utilisez un input file pour uploader.</small>
      </div>
      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">📤 Publier le document</button>
    </form>
  </div>
</div>

<script src="../js/main.js"></script>
<script src="../js/dashboard-admin.js"></script>
</body>
</html>
