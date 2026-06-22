<?php
require_once '../php/config.php';
requireLogin();
if (isAdmin()) redirect(BASE_URL.'/admin/dashboard.php');
$userName = $_SESSION['prenom'].' '.$_SESSION['nom'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Mon Espace - LaboFormation</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>
<div class="loading-screen"><div class="loading-logo">L</div><div class="loading-spinner"></div></div>

<nav class="navbar" style="position:fixed;z-index:200;">
  <a href="../index.php" class="nav-logo"><div class="logo-icon">L</div><div class="logo-text"><span class="logo-name">LaboFormation</span><span class="logo-tagline">Espace Étudiant</span></div></a>
  <ul class="nav-links">
    <li><a href="../index.php">Accueil</a></li>
    <li><a href="../formations.php">Formations</a></li>
    <li><a href="../services.php">Services</a></li>
    <li><a href="../contact.php">Contact</a></li>
  </ul>
  <div class="nav-actions">
    <div style="position:relative;">
      <button id="notifBtn" onclick="toggleNotifPanel()" style="background:none;border:none;color:var(--text-secondary);font-size:1.3rem;cursor:pointer;position:relative;">
        🔔<span id="notifBadge" style="position:absolute;top:-5px;right:-5px;background:var(--error);color:white;border-radius:50%;width:18px;height:18px;font-size:.65rem;display:flex;align-items:center;justify-content:center;display:none;">0</span>
      </button>
      <!-- Notif panel -->
      <div id="notifPanel" style="display:none;position:absolute;right:0;top:50px;width:360px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-card);z-index:9999;max-height:400px;overflow-y:auto;">
        <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
          <span style="font-weight:700;">Notifications</span>
          <button onclick="markAllRead()" style="background:none;border:none;color:var(--accent);cursor:pointer;font-size:.82rem;">Tout lire</button>
        </div>
        <div id="notifList"></div>
      </div>
    </div>
    <span style="color:var(--accent);font-size:.88rem;font-weight:600;">🎓 <?= htmlspecialchars($userName) ?></span>
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
      <div class="sidebar-role">🎓 Étudiant</div>
    </div>

    <div class="sidebar-section-label">Mon Espace</div>
    <ul class="sidebar-nav">
      <li><a href="#" class="active" onclick="showSection('tableau_bord',this)"><span class="nav-icon">🏠</span>Tableau de bord</a></li>
      <li><a href="#" onclick="showSection('profil',this)"><span class="nav-icon">👤</span>Mon Profil</a></li>
    </ul>

    <div class="sidebar-section-label">Mes Activités</div>
    <ul class="sidebar-nav">
      <li><a href="#" onclick="showSection('mes_formations',this)"><span class="nav-icon">📚</span>Mes Formations</a></li>
      <li><a href="#" onclick="showSection('mes_services',this)"><span class="nav-icon">⚙️</span>Mes Services</a></li>
      <li><a href="#" onclick="showSection('emploi_temps',this)"><span class="nav-icon">📅</span>Emploi du Temps</a></li>
      <li><a href="#" onclick="showSection('documents',this)"><span class="nav-icon">📁</span>Documents</a></li>
    </ul>

    <div class="sidebar-section-label">Finance</div>
    <ul class="sidebar-nav">
      <li><a href="#" onclick="showSection('paiements',this)"><span class="nav-icon">💳</span>Mes Paiements</a></li>
    </ul>

    <div class="sidebar-divider"></div>
    <ul class="sidebar-nav">
      <li><a href="../formations.php"><span class="nav-icon">🔍</span>Découvrir Formations</a></li>
      <li><a href="../php/auth.php?action=logout" style="color:var(--error)!important;"><span class="nav-icon">🚪</span>Déconnexion</a></li>
    </ul>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="main-content">

    <!-- TABLEAU DE BORD -->
    <div class="page-section active" id="section-tableau_bord">
      <div style="margin-bottom:32px;">
        <h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Bonjour, <?= htmlspecialchars($_SESSION['prenom']) ?>! 👋</h1>
        <p style="color:var(--text-secondary);">Bienvenue dans votre espace personnel LaboFormation</p>
      </div>

      <div class="stats-grid">
        <div class="stat-card"><div class="stat-card-label">Mes Formations</div><div class="stat-card-value" id="myFormationsCount">-</div><div class="stat-card-change">Inscriptions actives</div><div class="stat-card-icon" style="background:rgba(59,130,246,.15);">📚</div></div>
        <div class="stat-card"><div class="stat-card-label">Mes Services</div><div class="stat-card-value" id="myServicesCount">-</div><div class="stat-card-change">Souscrits</div><div class="stat-card-icon" style="background:rgba(212,160,23,.15);">⚙️</div></div>
        <div class="stat-card"><div class="stat-card-label">Documents dispo</div><div class="stat-card-value" id="myDocsCount">-</div><div class="stat-card-change">Supports de cours</div><div class="stat-card-icon" style="background:rgba(34,197,94,.15);">📁</div></div>
        <div class="stat-card"><div class="stat-card-label">Notifications</div><div class="stat-card-value" id="myNotifCount">-</div><div class="stat-card-change">Non lues</div><div class="stat-card-icon" style="background:rgba(245,158,11,.15);">🔔</div></div>
      </div>

      <!-- Prochains cours -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-top:8px;">
        <div class="table-container">
          <div class="table-header"><span class="table-title">📅 Prochains cours</span></div>
          <div id="nextCourses" style="padding:16px;"></div>
        </div>
        <div class="table-container">
          <div class="table-header"><span class="table-title">📁 Documents récents</span></div>
          <div id="recentDocs" style="padding:16px;"></div>
        </div>
      </div>
    </div>

    <!-- PROFIL -->
    <div class="page-section" id="section-profil">
      <div style="margin-bottom:32px;"><h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Mon Profil 👤</h1></div>
      <div style="max-width:600px;">
        <div style="padding:40px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
          <div style="text-align:center;margin-bottom:32px;">
            <div style="width:90px;height:90px;background:var(--gradient-gold);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:900;color:var(--primary);margin:0 auto 12px;font-family:var(--font-display);"><?= strtoupper(substr($_SESSION['prenom'],0,1).substr($_SESSION['nom'],0,1)) ?></div>
            <div style="font-size:1.1rem;font-weight:700;"><?= htmlspecialchars($userName) ?></div>
            <div class="badge badge-info" style="margin-top:6px;">🎓 Étudiant</div>
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

    <!-- MES FORMATIONS -->
    <div class="page-section" id="section-mes_formations">
      <div style="margin-bottom:32px;">
        <h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Mes Formations 📚</h1>
        <p style="color:var(--text-secondary);">Formations pour lesquelles vous êtes inscrit(e)</p>
      </div>
      <div id="mesFormationsGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;"></div>
      <div style="margin-top:32px;text-align:center;">
        <a href="../formations.php" class="btn-primary">🔍 Découvrir d'autres formations →</a>
      </div>
    </div>

    <!-- MES SERVICES -->
    <div class="page-section" id="section-mes_services">
      <div style="margin-bottom:32px;">
        <h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Mes Services ⚙️</h1>
      </div>
      <div id="mesServicesGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;"></div>
      <div style="margin-top:32px;text-align:center;">
        <a href="../services.php" class="btn-primary">🔍 Voir tous les services →</a>
      </div>
    </div>

    <!-- EMPLOI DU TEMPS -->
    <div class="page-section" id="section-emploi_temps">
      <div style="margin-bottom:32px;">
        <h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Emploi du Temps 📅</h1>
      </div>
      <div id="emploiTempsContainer"></div>
    </div>

    <!-- DOCUMENTS -->
    <div class="page-section" id="section-documents">
      <div style="margin-bottom:32px;">
        <h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Documents 📁</h1>
      </div>
      <div id="documentsStudentGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;"></div>
    </div>

    <!-- PAIEMENTS -->
    <div class="page-section" id="section-paiements">
      <div style="margin-bottom:32px;">
        <h1 style="font-family:var(--font-display);font-size:2rem;font-weight:900;">Mes Paiements 💳</h1>
      </div>
      <div class="table-container">
        <div class="table-header"><span class="table-title">Historique des paiements</span></div>
        <table><thead><tr><th>Référence</th><th>Type</th><th>Montant</th><th>Méthode</th><th>Statut</th><th>Date</th></tr></thead>
        <tbody id="paiementsStudentTable"><tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">Chargement...</td></tr></tbody></table>
      </div>
    </div>

  </main>
</div>

<script src="../js/main.js"></script>
<script src="../js/dashboard-student.js"></script>
</body>
</html>
