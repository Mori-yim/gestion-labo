<?php require_once 'php/config.php'; $isLoggedIn=isLoggedIn(); $userRole=$_SESSION['role']??''; $userName=isset($_SESSION['prenom'])?$_SESSION['prenom'].' '.$_SESSION['nom']:''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Services - LaboFormation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="<?= $isLoggedIn?'true':'false' ?>">
<div class="loading-screen"><div class="loading-logo">L</div><div class="loading-spinner"></div></div>

<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-logo"><div class="logo-icon">L</div><div class="logo-text"><span class="logo-name">LaboFormation</span><span class="logo-tagline">Excellence Numérique</span></div></a>
  <ul class="nav-links">
    <li><a href="index.php">Accueil</a></li><li><a href="about.php">À propos</a></li>
    <li><a href="formations.php">Formations</a></li><li><a href="services.php" class="active">Services</a></li>
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
    <div class="section-badge">⚙️ Notre Expertise</div>
    <h1 class="section-title" style="font-size:clamp(2rem,4vw,3.5rem);">Nos <span class="gold">Services</span></h1>
    <p style="color:var(--text-secondary);max-width:600px;margin:16px auto 0;">Solutions numériques professionnelles pour entreprises et particuliers. Expertise, qualité et résultats garantis.</p>
  </div>
</section>

<section style="background:var(--bg-dark);padding:80px 5%;">
  <div class="cards-grid" id="servicesGrid" style="max-width:1200px;margin:0 auto;"></div>
</section>

<!-- PROCESSUS -->
<section style="background:var(--primary);padding:80px 5%;">
  <div style="max-width:1100px;margin:0 auto;">
    <div class="section-header" style="text-align:center;max-width:700px;margin:0 auto 60px;">
      <div class="section-badge">🔄 Notre Processus</div>
      <h2 class="section-title">Comment nous <span class="gold">travaillons</span></h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:28px;">
      <?php $process=[['🎯','Consultation','Analyse de vos besoins et définition des objectifs du projet.'],['📋','Proposition','Devis détaillé, planning et méthodologie adaptés.'],['⚙️','Réalisation','Développement agile avec points d\'avancement réguliers.'],['✅','Livraison','Tests, validation et déploiement. Support post-livraison inclus.']]; ?>
      <?php foreach($process as $i=>$p): ?>
      <div style="text-align:center;padding:32px 24px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);position:relative;">
        <div style="width:50px;height:50px;background:var(--gradient-gold);border-radius:50%;display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-weight:900;color:var(--primary);margin:0 auto 16px;font-size:1.1rem;"><?= $i+1 ?></div>
        <div style="font-size:2rem;margin-bottom:12px;"><?= $p[0] ?></div>
        <h4 style="font-weight:700;color:var(--text-primary);margin-bottom:8px;"><?= $p[1] ?></h4>
        <p style="font-size:.85rem;color:var(--text-secondary);"><?= $p[2] ?></p>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include_once 'includes/footer.php'; ?>
<?php include_once 'includes/auth_modal.php'; ?>

<script src="js/main.js"></script>
<script>
async function loadServices() {
  const container = document.getElementById('servicesGrid');
  container.innerHTML = '<div style="text-align:center;padding:60px;color:var(--text-muted);grid-column:1/-1;">Chargement...</div>';
  const result = await apiCall('php/api.php?action=get_services');
  if (result.success) {
    const icons=['🌐','📱','🎓','🔧','💡','🎨'];
    const gradients=['#0a1628,#1a3a6b','#0a2040,#1a4080','#1a0a40,#4a1a80','#0a2030,#1a5060','#301a0a,#804020','#102030,#205040'];
    container.innerHTML = result.data.map((s,i)=>`
      <div class="card">
        <div class="card-image" style="background:linear-gradient(135deg,${gradients[i%gradients.length]})">
          <span style="font-size:4rem">${icons[i%icons.length]}</span>
          <div class="card-image-overlay"></div>
          <span class="card-badge">${s.categorie||'Service'}</span>
        </div>
        <div class="card-body">
          <div class="card-category">${s.categorie||'Service'}</div>
          <h3 class="card-title">${s.titre}</h3>
          <p class="card-desc">${s.description}</p>
          <div class="card-footer">
            <div><div class="card-price">${formatMontant(s.prix)}</div><span class="card-price-sub">Service professionnel</span></div>
            <button class="btn-card" onclick="handleSouscrire('service',${s.id},'${s.titre.replace(/'/g,"\\'")}',${s.prix})">Obtenir →</button>
          </div>
        </div>
      </div>
    `).join('');
  }
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

loadServices();
</script>
</body>
</html>
