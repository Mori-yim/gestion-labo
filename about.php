<?php require_once 'php/config.php'; $isLoggedIn = isLoggedIn(); $userRole = $_SESSION['role'] ?? ''; $userName = isset($_SESSION['prenom']) ? $_SESSION['prenom'].' '.$_SESSION['nom'] : ''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>À Propos - LaboFormation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>">
<div class="loading-screen"><div class="loading-logo">L</div><div class="loading-spinner"></div></div>

<!-- NAVBAR -->
<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-logo">
    <div class="logo-icon">L</div>
    <div class="logo-text"><span class="logo-name">LaboFormation</span><span class="logo-tagline">Excellence Numérique</span></div>
  </a>
  <ul class="nav-links">
    <li><a href="index.php">Accueil</a></li>
    <li><a href="about.php" class="active">À propos</a></li>
    <li><a href="formations.php">Formations</a></li>
    <li><a href="services.php">Services</a></li>
    <li><a href="paiement.php">Paiement</a></li>
    <li><a href="contact.php">Contact</a></li>
    <?php if($isLoggedIn): ?><li><a href="<?= $userRole==='admin'?'admin/dashboard.php':'student/dashboard.php' ?>">Mon Espace</a></li><?php endif; ?>
  </ul>
  <div class="nav-actions">
    <?php if($isLoggedIn): ?>
      <span style="color:var(--accent);font-size:.88rem;font-weight:600;">👋 <?= htmlspecialchars($userName) ?></span>
      <a href="php/auth.php?action=logout" class="btn-login">Déconnexion</a>
    <?php else: ?>
      <button class="btn-login" id="loginBtn">Connexion</button>
      <button class="btn-primary" onclick="openModal('authModal')">S'inscrire ✨</button>
    <?php endif; ?>
    <div class="hamburger"><span></span><span></span><span></span></div>
  </div>
</nav>

<!-- PAGE HERO -->
<section class="page-hero">
  <div class="hero-grid"></div>
  <div class="page-hero-content">
    <div class="section-badge">🏛️ Notre Histoire</div>
    <h1 class="section-title" style="font-size:clamp(2rem,4vw,3.5rem);">À Propos de <span class="gold">LaboFormation</span></h1>
    <p style="color:var(--text-secondary);max-width:600px;margin:16px auto 0;">Centre d'excellence numérique au cœur de Douala, formant les talents tech d'Afrique depuis 2018.</p>
  </div>
</section>

<!-- MISSION -->
<section style="background:var(--bg-dark);padding:100px 5%;">
  <div class="about-grid" style="max-width:1200px;margin:0 auto;">
    <div class="about-visual">
      <div class="about-image">
        <span style="font-size:10rem;filter:drop-shadow(0 0 40px rgba(212,160,23,0.4))">🎓</span>
        <div style="position:absolute;inset:0;background:radial-gradient(circle at 50%,rgba(212,160,23,0.1),transparent 70%);"></div>
      </div>
      <div class="floating-card" style="position:absolute;bottom:-20px;right:-20px;padding:20px 24px;">
        <div style="font-size:0.78rem;color:var(--text-muted);margin-bottom:4px;">Fondé en</div>
        <div style="font-family:var(--font-display);font-size:2rem;font-weight:900;color:var(--accent);">2018</div>
      </div>
    </div>
    <div>
      <div class="section-badge">🎯 Notre Mission</div>
      <h2 class="section-title">Former les <span class="gold">Leaders Tech</span><br>de demain</h2>
      <p style="color:var(--text-secondary);line-height:1.9;margin-bottom:20px;">
        LaboFormation est né d'une vision simple mais ambitieuse : démocratiser l'accès aux compétences numériques de haut niveau en Afrique centrale. Depuis 2018, nous formons des professionnels du digital capables de relever les défis technologiques de leur époque.
      </p>
      <p style="color:var(--text-secondary);line-height:1.9;margin-bottom:32px;">
        Notre approche pédagogique unique combine théorie rigoureuse et pratique intensive sur des projets réels. Nous croyons que l'apprentissage le plus efficace se fait en faisant, en échouant, et en recommençant.
      </p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div style="padding:20px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
          <div style="font-size:2rem;font-family:var(--font-display);font-weight:900;color:var(--accent);">500+</div>
          <div style="font-size:0.85rem;color:var(--text-secondary);">Diplômés sur le marché</div>
        </div>
        <div style="padding:20px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
          <div style="font-size:2rem;font-family:var(--font-display);font-weight:900;color:var(--accent);">95%</div>
          <div style="font-size:0.85rem;color:var(--text-secondary);">Taux d'insertion pro</div>
        </div>
        <div style="padding:20px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
          <div style="font-size:2rem;font-family:var(--font-display);font-weight:900;color:var(--accent);">15+</div>
          <div style="font-size:0.85rem;color:var(--text-secondary);">Formateurs experts</div>
        </div>
        <div style="padding:20px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
          <div style="font-size:2rem;font-family:var(--font-display);font-weight:900;color:var(--accent);">200+</div>
          <div style="font-size:0.85rem;color:var(--text-secondary);">Entreprises partenaires</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- VALEURS -->
<section style="background:var(--primary);padding:100px 5%;">
  <div class="section-header" style="text-align:center;max-width:700px;margin:0 auto 60px;">
    <div class="section-badge">💎 Nos Valeurs</div>
    <h2 class="section-title">Ce qui nous <span class="gold">définit</span></h2>
  </div>
  <div class="features-grid" style="max-width:1200px;margin:0 auto;">
    <div class="feature-card"><div class="feature-icon">🔬</div><h3 class="feature-title">Excellence</h3><p class="feature-desc">Nous ne nous contentons pas du minimum. Chaque cours, chaque projet, chaque interaction vise l'excellence absolue.</p></div>
    <div class="feature-card"><div class="feature-icon">🤝</div><h3 class="feature-title">Intégrité</h3><p class="feature-desc">Honnêteté et transparence dans toutes nos relations : étudiants, partenaires, employeurs. La confiance est notre monnaie.</p></div>
    <div class="feature-card"><div class="feature-icon">🌍</div><h3 class="feature-title">Impact</h3><p class="feature-desc">Former un développeur, c'est potentiellement transformer une communauté entière. Nous mesurons notre succès à l'impact de nos diplômés.</p></div>
    <div class="feature-card"><div class="feature-icon">🔄</div><h3 class="feature-title">Innovation</h3><p class="feature-desc">Nos programmes évoluent constamment pour rester en phase avec les technologies et besoins du marché.</p></div>
  </div>
</section>

<!-- ÉQUIPE -->
<section style="background:var(--bg-dark);padding:100px 5%;">
  <div class="section-header" style="text-align:center;max-width:700px;margin:0 auto 60px;">
    <div class="section-badge">👨‍🏫 Notre Équipe</div>
    <h2 class="section-title">Les <span class="gold">experts</span> qui vous forment</h2>
  </div>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:28px;max-width:1100px;margin:0 auto;">
    <?php
    $team = [
      ['initials'=>'ET','name'=>'M. Tchouanga Eric','role'=>'Directeur & Lead Dev Web','desc'=>'10 ans d\'exp. Full Stack. Ex-Google Developer Expert.','color'=>'linear-gradient(135deg,#d4a017,#f0c040)'],
      ['initials'=>'FS','name'=>'Mme Fouda Sandra','role'=>'Formatrice JavaScript / React','desc'=>'Senior Engineer. 8 ans en développement frontend moderne.','color'=>'linear-gradient(135deg,#1a3a6b,#3060b0)'],
      ['initials'=>'MC','name'=>'Dr. Mbarga Claude','role'=>'Expert IA & Data Science','desc'=>'PhD en Machine Learning. Chercheur et praticien IA.','color'=>'linear-gradient(135deg,#1a0a40,#4a1a80)'],
      ['initials'=>'AP','name'=>'M. Atangana Pierre','role'=>'Expert Cybersécurité','desc'=>'CEH, CISSP certifié. Consultant sécurité pour grandes entreprises.','color'=>'linear-gradient(135deg,#0a2030,#1a5060)'],
      ['initials'=>'NI','name'=>'Mme Njoya Isabelle','role'=>'Designer UI/UX','desc'=>'10 ans en design digital. Projets pour Orange, MTN, Canal+.','color'=>'linear-gradient(135deg,#301a0a,#804020)'],
      ['initials'=>'NF','name'=>'M. Ndjike Franck','role'=>'Développeur Mobile','desc'=>'React Native & Flutter expert. 50+ apps publiées sur les stores.','color'=>'linear-gradient(135deg,#0a2040,#1a4080)'],
    ];
    foreach($team as $m): ?>
    <div class="card" style="text-align:center;">
      <div style="height:180px;background:<?= $m['color'] ?>;display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-size:3.5rem;font-weight:900;color:rgba(255,255,255,0.9);">
        <?= $m['initials'] ?>
      </div>
      <div class="card-body">
        <h3 class="card-title" style="font-size:1rem;"><?= $m['name'] ?></h3>
        <div class="card-category"><?= $m['role'] ?></div>
        <p class="card-desc" style="font-size:0.83rem;"><?= $m['desc'] ?></p>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- PARTENAIRES -->
<section style="background:var(--primary);padding:80px 5%;">
  <div style="text-align:center;max-width:900px;margin:0 auto;">
    <div class="section-badge">🤝 Partenaires</div>
    <h2 class="section-title">Ils font confiance à <span class="gold">nos diplômés</span></h2>
    <div style="display:flex;flex-wrap:wrap;gap:20px;justify-content:center;margin-top:48px;">
      <?php $partners = ['MTN 🇨🇲','Orange 🟠','Canal+ 📺','Afriland First Bank 🏦','Camtel 📡','CAMPOST 📮','Total Energies ⛽','BEAC 🏛️']; ?>
      <?php foreach($partners as $p): ?>
      <div style="padding:16px 28px;background:var(--bg-glass);border:1px solid var(--border);border-radius:50px;font-size:0.9rem;font-weight:600;color:var(--text-secondary);">
        <?= $p ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FOOTER (simplified) -->
<?php include_once 'includes/footer.php'; ?>

<!-- AUTH MODAL -->
<?php include_once 'includes/auth_modal.php'; ?>

<script src="js/main.js"></script>
</body>
</html>
