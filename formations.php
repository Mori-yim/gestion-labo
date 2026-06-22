<?php require_once 'php/config.php'; $isLoggedIn=isLoggedIn(); $userRole=$_SESSION['role']??''; $userName=isset($_SESSION['prenom'])?$_SESSION['prenom'].' '.$_SESSION['nom']:''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Formations - LaboFormation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="<?= $isLoggedIn?'true':'false' ?>">
<div class="loading-screen"><div class="loading-logo">L</div><div class="loading-spinner"></div></div>

<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-logo"><div class="logo-icon">L</div><div class="logo-text"><span class="logo-name">LaboFormation</span><span class="logo-tagline">Excellence Numérique</span></div></a>
  <ul class="nav-links">
    <li><a href="index.php">Accueil</a></li><li><a href="about.php">À propos</a></li>
    <li><a href="formations.php" class="active">Formations</a></li><li><a href="services.php">Services</a></li>
    <li><a href="paiement.php">Paiement</a></li><li><a href="contact.php">Contact</a></li>
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

<section class="page-hero">
  <div class="hero-grid"></div>
  <div class="page-hero-content">
    <div class="section-badge">📚 Catalogue</div>
    <h1 class="section-title" style="font-size:clamp(2rem,4vw,3.5rem);">Nos <span class="gold">Formations</span></h1>
    <p style="color:var(--text-secondary);max-width:600px;margin:16px auto 0;">Des programmes complets, certifiants et orientés vers l'emploi. Choisissez votre spécialité et transformez votre carrière.</p>
  </div>
</section>

<!-- FILTRES -->
<section style="background:var(--primary);padding:40px 5%;">
  <div style="max-width:1200px;margin:0 auto;">
    <div style="display:flex;gap:12px;flex-wrap:wrap;justify-content:center;" id="filterBtns">
      <button class="filter-btn active" data-filter="all" onclick="filterFormations('all',this)">Toutes</button>
      <button class="filter-btn" data-filter="Informatique" onclick="filterFormations('Informatique',this)">💻 Informatique</button>
      <button class="filter-btn" data-filter="IA & Data" onclick="filterFormations('IA & Data',this)">🤖 IA & Data</button>
      <button class="filter-btn" data-filter="Sécurité" onclick="filterFormations('Sécurité',this)">🔐 Sécurité</button>
      <button class="filter-btn" data-filter="Design" onclick="filterFormations('Design',this)">🎨 Design</button>
      <button class="filter-btn" data-filter="Mobile" onclick="filterFormations('Mobile',this)">📱 Mobile</button>
      <button class="filter-btn" data-filter="Gestion" onclick="filterFormations('Gestion',this)">💼 Gestion</button>
    </div>
  </div>
</section>

<!-- FORMATIONS GRID -->
<section style="background:var(--bg-dark);padding:60px 5%;">
  <div class="cards-grid" id="formationsGrid" style="max-width:1200px;margin:0 auto;"></div>
</section>

<!-- WHY SECTION -->
<section style="background:var(--primary);padding:80px 5%;">
  <div style="max-width:1100px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;">
    <div>
      <div class="section-badge">🎯 Notre méthode</div>
      <h2 class="section-title">Comment se passe <span class="gold">votre formation?</span></h2>
      <div style="display:flex;flex-direction:column;gap:20px;margin-top:28px;">
        <?php $steps=[['1','Choisissez votre formation','Parcourez notre catalogue et sélectionnez la formation qui correspond à vos objectifs.'],['2','Inscrivez-vous & payez','Créez votre compte, sélectionnez votre formation et effectuez le paiement sécurisé.'],['3','Accédez à votre espace','Consultez l\'emploi du temps, téléchargez les supports et suivez vos cours.'],['4','Obtenez votre certificat','Validez votre formation et recevez votre certificat reconnu par nos partenaires.']]; ?>
        <?php foreach($steps as $s): ?>
        <div style="display:flex;gap:16px;align-items:flex-start;">
          <div style="width:40px;height:40px;background:var(--gradient-gold);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:900;color:var(--primary);flex-shrink:0;font-family:var(--font-display);"><?= $s[0] ?></div>
          <div><h4 style="color:var(--text-primary);font-weight:700;margin-bottom:4px;"><?= $s[1] ?></h4><p style="font-size:.85rem;color:var(--text-secondary);"><?= $s[2] ?></p></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
    <div style="padding:40px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
      <h3 style="font-family:var(--font-display);font-size:1.3rem;margin-bottom:24px;color:var(--text-primary);">📋 Ce que chaque formation inclut</h3>
      <?php $includes=['✅ Support de cours PDF téléchargeable','✅ Accès aux vidéos de cours','✅ Projets pratiques guidés','✅ Sessions de questions-réponses live','✅ Accès à la salle informatique','✅ Mentorat personnalisé','✅ Certificat de réussite','✅ Aide à l\'insertion professionnelle']; ?>
      <?php foreach($includes as $inc): ?>
      <div style="padding:10px 0;border-bottom:1px solid rgba(255,255,255,0.05);font-size:.88rem;color:var(--text-secondary);"><?= $inc ?></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include_once 'includes/footer.php'; ?>
<?php include_once 'includes/auth_modal.php'; ?>

<style>
.filter-btn{padding:10px 22px;background:var(--bg-glass);border:1px solid var(--border);color:var(--text-secondary);border-radius:50px;cursor:pointer;font-size:.88rem;font-weight:600;transition:var(--transition);}
.filter-btn:hover,.filter-btn.active{background:var(--gradient-gold);color:var(--primary);border-color:var(--accent);}
</style>

<script src="js/main.js"></script>
<script>
let allFormations = [];

async function loadFormations() {
  const container = document.getElementById('formationsGrid');
  container.innerHTML = '<div style="text-align:center;padding:60px;color:var(--text-muted);grid-column:1/-1;">Chargement des formations...</div>';
  const result = await apiCall('php/api.php?action=get_formations');
  if (result.success) {
    allFormations = result.data;
    renderFormations(allFormations);
  }
}

const icons = ['💻','🤖','🔐','🎨','📱','💼'];
const gradients = ['#0a1628,#1a3a6b','#1a0a40,#4a1a80','#0a2030,#1a5060','#301a0a,#804020','#0a1628,#203060','#102030,#205040'];

function renderFormations(data) {
  const container = document.getElementById('formationsGrid');
  if (!data.length) { container.innerHTML = '<div style="text-align:center;padding:60px;color:var(--text-muted);grid-column:1/-1;">Aucune formation dans cette catégorie.</div>'; return; }
  container.innerHTML = data.map((f, i) => `
    <div class="card" data-category="${f.categorie}">
      <div class="card-image" style="background:linear-gradient(135deg,${gradients[i%gradients.length]})">
        <span style="font-size:4rem;filter:drop-shadow(0 0 20px rgba(212,160,23,.3))">${icons[i%icons.length]}</span>
        <div class="card-image-overlay"></div>
        <span class="card-badge">${f.niveau}</span>
      </div>
      <div class="card-body">
        <div class="card-category">${f.categorie||'Formation'}</div>
        <h3 class="card-title">${f.titre}</h3>
        <p class="card-desc">${f.description}</p>
        <div class="card-meta">
          ${f.duree?`<span class="meta-item"><span>⏱️</span>${f.duree}</span>`:''}
          <span class="meta-item"><span>👥</span>${f.places_disponibles} places</span>
          <span class="meta-item"><span>📊</span>${f.niveau}</span>
        </div>
        <div class="card-footer">
          <div><div class="card-price">${formatMontant(f.prix)}</div><span class="card-price-sub">Formation complète</span></div>
          <button class="btn-card" onclick="handleSouscrire('formation',${f.id},'${f.titre.replace(/'/g,"\\'")}',${f.prix})">Souscrire →</button>
        </div>
      </div>
    </div>
  `).join('');
}

function filterFormations(cat, btn) {
  document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  const filtered = cat==='all' ? allFormations : allFormations.filter(f=>f.categorie===cat);
  renderFormations(filtered);
}

document.getElementById('paymentForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn=this.querySelector('button[type="submit"]');btn.disabled=true;btn.textContent='Traitement...';
  const type=document.getElementById('paymentItemType').value;
  const id=document.getElementById('paymentItemId').value;
  const methode=this.querySelector('[name="methode"]').value;
  const action=type==='formation'?'souscrire_formation':'souscrire_service';
  const bodyKey=type==='formation'?'formation_id':'service_id';
  const result=await apiCall('php/api.php?action='+action,'POST',{[bodyKey]:id,methode});
  toast(result.message,result.success?'success':'error');
  if(result.success)closeModal('paymentModal');
  btn.disabled=false;btn.textContent='✅ Confirmer le paiement';
});

loadFormations();
</script>
</body>
</html>
