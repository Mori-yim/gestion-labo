<?php require_once 'php/config.php'; $isLoggedIn=isLoggedIn(); $userRole=$_SESSION['role']??''; $userName=isset($_SESSION['prenom'])?$_SESSION['prenom'].' '.$_SESSION['nom']:''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Paiement - LaboFormation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="<?= $isLoggedIn?'true':'false' ?>">
<div class="loading-screen"><div class="loading-logo">L</div><div class="loading-spinner"></div></div>

<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-logo"><div class="logo-icon">L</div><div class="logo-text"><span class="logo-name">LaboFormation</span><span class="logo-tagline">Excellence Numérique</span></div></a>
  <ul class="nav-links">
    <li><a href="index.php">Accueil</a></li><li><a href="about.php">À propos</a></li>
    <li><a href="formations.php">Formations</a></li><li><a href="services.php">Services</a></li>
    <li><a href="paiement.php" class="active">Paiement</a></li><li><a href="contact.php">Contact</a></li>
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
    <div class="section-badge">💳 Paiement Sécurisé</div>
    <h1 class="section-title" style="font-size:clamp(2rem,4vw,3.5rem);">Options de <span class="gold">Paiement</span></h1>
    <p style="color:var(--text-secondary);max-width:600px;margin:16px auto 0;">Plusieurs méthodes de paiement disponibles pour votre confort. Simple, rapide et sécurisé.</p>
  </div>
</section>

<section style="background:var(--bg-dark);padding:100px 5%;">
  <div style="max-width:1100px;margin:0 auto;">
    
    <!-- Méthodes de paiement -->
    <div class="section-header" style="text-align:center;max-width:700px;margin:0 auto 60px;">
      <div class="section-badge">🔒 Méthodes acceptées</div>
      <h2 class="section-title">Payez comme vous <span class="gold">préférez</span></h2>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:24px;margin-bottom:60px;">
      <?php $methods=[
        ['📱','MTN Mobile Money','Envoyez votre paiement au numéro Mobile Money. Rapide et disponible 24h/24.','#f0c040','Numéro: 6 99 00 00 01'],
        ['🟠','Orange Money','Paiement instantané via Orange Money. Confirmé en quelques secondes.','#ff7a00','Numéro: 6 77 00 00 02'],
        ['🏦','Virement Bancaire','Virement depuis votre compte bancaire. Délai de traitement: 1-2 jours ouvrables.','#3b82f6','AFRILAND FIRST BANK'],
        ['💵','Espèces au Bureau','Payez directement à nos bureaux à Douala. Du lundi au samedi 8h-18h.','#22c55e','Bureaux: Akwa, Douala'],
      ]; ?>
      <?php foreach($methods as $m): ?>
      <div style="padding:32px 24px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);text-align:center;transition:var(--transition);" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
        <div style="font-size:3.5rem;margin-bottom:16px;"><?= $m[0] ?></div>
        <h3 style="font-family:var(--font-display);font-size:1.1rem;font-weight:700;color:var(--text-primary);margin-bottom:8px;"><?= $m[1] ?></h3>
        <p style="font-size:.85rem;color:var(--text-secondary);margin-bottom:16px;"><?= $m[2] ?></p>
        <div style="padding:8px 16px;background:rgba(212,160,23,.1);border:1px solid var(--border);border-radius:var(--radius-sm);font-size:.82rem;font-weight:600;color:var(--accent);"><?= $m[3] ?></div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Guide paiement Mobile Money -->
    <div style="padding:40px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);margin-bottom:40px;">
      <h3 style="font-family:var(--font-display);font-size:1.3rem;margin-bottom:24px;color:var(--text-primary);">📱 Comment payer via Mobile Money?</h3>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
        <?php $steps=[['1','Composez *126#','Accédez au menu Mobile Money MTN sur votre téléphone.'],['2','Choisissez "Paiement"','Sélectionnez l\'option "Paiement marchand".'],['3','Entrez le numéro','Saisissez: 699 000 001'],['4','Montant & confirmation','Entrez le montant exact et confirmez avec votre PIN.'],['5','Notez la référence','Conservez le code de transaction pour vos dossiers.'],['6','Envoyez la référence','Envoyez votre numéro de transaction à info@laboformation.cm.']]; ?>
        <?php foreach($steps as $s): ?>
        <div style="display:flex;gap:12px;align-items:flex-start;">
          <div style="width:32px;height:32px;background:var(--gradient-gold);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:900;color:var(--primary);flex-shrink:0;font-size:.85rem;"><?= $s[0] ?></div>
          <div><h4 style="font-size:.9rem;font-weight:600;color:var(--text-primary);margin-bottom:2px;"><?= $s[1] ?></h4><p style="font-size:.8rem;color:var(--text-secondary);"><?= $s[2] ?></p></div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Politique de remboursement -->
    <div style="padding:40px;background:rgba(212,160,23,.05);border:1px solid var(--border);border-radius:var(--radius);">
      <h3 style="font-family:var(--font-display);font-size:1.2rem;margin-bottom:20px;color:var(--text-primary);">📋 Politique de remboursement</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div><h4 style="color:var(--success);margin-bottom:8px;">✅ Remboursement possible</h4>
          <ul style="font-size:.85rem;color:var(--text-secondary);line-height:2;padding-left:16px;">
            <li>Annulation 7+ jours avant le début: remboursement intégral</li>
            <li>Annulation 3-7 jours: remboursement à 50%</li>
            <li>Formation annulée par le centre: remboursement intégral</li>
          </ul>
        </div>
        <div><h4 style="color:var(--error);margin-bottom:8px;">❌ Non remboursable</h4>
          <ul style="font-size:.85rem;color:var(--text-secondary);line-height:2;padding-left:16px;">
            <li>Annulation moins de 72h avant le début</li>
            <li>Absence non justifiée aux cours</li>
            <li>Frais d'inscription non remboursables</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include_once 'includes/footer.php'; ?>
<?php include_once 'includes/auth_modal.php'; ?>
<script src="js/main.js"></script>
</body>
</html>
