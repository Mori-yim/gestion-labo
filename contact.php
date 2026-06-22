<?php require_once 'php/config.php'; $isLoggedIn=isLoggedIn(); $userRole=$_SESSION['role']??''; $userName=isset($_SESSION['prenom'])?$_SESSION['prenom'].' '.$_SESSION['nom']:''; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Contact - LaboFormation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body data-logged-in="<?= $isLoggedIn?'true':'false' ?>">
<div class="loading-screen"><div class="loading-logo">L</div><div class="loading-spinner"></div></div>

<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-logo"><div class="logo-icon">L</div><div class="logo-text"><span class="logo-name">LaboFormation</span><span class="logo-tagline">Excellence Numérique</span></div></a>
  <ul class="nav-links">
    <li><a href="index.php">Accueil</a></li><li><a href="about.php">À propos</a></li>
    <li><a href="formations.php">Formations</a></li><li><a href="services.php">Services</a></li>
    <li><a href="paiement.php">Paiement</a></li><li><a href="contact.php" class="active">Contact</a></li>
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
    <div class="section-badge">📞 Nous contacter</div>
    <h1 class="section-title" style="font-size:clamp(2rem,4vw,3.5rem);">Parlons de votre <span class="gold">Projet</span></h1>
    <p style="color:var(--text-secondary);max-width:600px;margin:16px auto 0;">Notre équipe est disponible pour répondre à toutes vos questions. N'hésitez pas à nous contacter.</p>
  </div>
</section>

<section class="contact-section" style="padding:100px 5%;">
  <div class="contact-grid">
    <!-- Infos -->
    <div>
      <div class="contact-info-card">
        <h3 style="font-family:var(--font-display);font-size:1.3rem;margin-bottom:28px;color:var(--text-primary);">Nos coordonnées</h3>
        <div class="contact-item">
          <div class="contact-icon">📍</div>
          <div><h4>Adresse</h4><p>Rue de la Joie, Quartier Akwa<br>Douala, Région du Littoral, Cameroun</p></div>
        </div>
        <div class="contact-item">
          <div class="contact-icon">📞</div>
          <div><h4>Téléphone</h4><p>+237 6 99 00 00 01<br>+237 6 77 00 00 02</p></div>
        </div>
        <div class="contact-item">
          <div class="contact-icon">📧</div>
          <div><h4>Email</h4><p>info@laboformation.cm<br>formations@laboformation.cm</p></div>
        </div>
        <div class="contact-item">
          <div class="contact-icon">🕐</div>
          <div><h4>Horaires d'ouverture</h4><p>Lundi - Vendredi: 8h00 - 18h00<br>Samedi: 9h00 - 14h00</p></div>
        </div>
      </div>

      <!-- Carte stylisée -->
      <div style="margin-top:24px;padding:32px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);text-align:center;">
        <div style="font-size:4rem;margin-bottom:12px;">🗺️</div>
        <h4 style="color:var(--text-primary);margin-bottom:8px;">Nous trouver</h4>
        <p style="font-size:.85rem;color:var(--text-secondary);">Douala, Cameroun — à 5 min du carrefour Akwa</p>
        <a href="https://maps.google.com/?q=Douala,Cameroun" target="_blank" class="btn-primary" style="display:inline-flex;margin-top:16px;">
          📍 Ouvrir dans Maps
        </a>
      </div>
    </div>

    <!-- Formulaire -->
    <div style="padding:40px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
      <h3 style="font-family:var(--font-display);font-size:1.4rem;margin-bottom:8px;color:var(--text-primary);">Envoyez-nous un message</h3>
      <p style="font-size:.88rem;color:var(--text-secondary);margin-bottom:28px;">Nous vous répondrons dans les 24 heures ouvrables.</p>
      <form id="contactForm">
        <div class="form-row">
          <div class="form-group"><label class="form-label">👤 Votre nom</label><input type="text" name="nom" class="form-input" placeholder="Jean Kamga" required></div>
          <div class="form-group"><label class="form-label">📧 Votre email</label><input type="email" name="email" class="form-input" placeholder="jean@email.com" required></div>
        </div>
        <div class="form-group">
          <label class="form-label">📋 Sujet</label>
          <select name="sujet" class="form-select">
            <option value="">-- Choisir un sujet --</option>
            <option value="Information formation">Information sur une formation</option>
            <option value="Inscription">Inscription et paiement</option>
            <option value="Service">Demande de service</option>
            <option value="Partenariat">Partenariat entreprise</option>
            <option value="Autre">Autre demande</option>
          </select>
        </div>
        <div class="form-group"><label class="form-label">✉️ Votre message</label><textarea name="message" class="form-textarea" placeholder="Décrivez votre demande en détail..." required style="min-height:150px;"></textarea></div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:16px;font-size:1rem;">
          📨 Envoyer le message
        </button>
      </form>
    </div>
  </div>
</section>

<!-- FAQ -->
<section style="background:var(--primary);padding:80px 5%;">
  <div style="max-width:900px;margin:0 auto;">
    <div class="section-header" style="text-align:center;max-width:700px;margin:0 auto 48px;">
      <div class="section-badge">❓ FAQ</div>
      <h2 class="section-title">Questions <span class="gold">fréquentes</span></h2>
    </div>
    <div id="faqContainer">
      <?php $faqs=[
        ['Comment s\'inscrire à une formation?','Créez un compte sur notre plateforme, choisissez votre formation, et effectuez le paiement via Mobile Money, Orange Money ou virement bancaire. Votre inscription est confirmée immédiatement.'],
        ['Quels sont les prérequis?','La plupart de nos formations débutant ne nécessitent aucune connaissance préalable. Pour les niveaux intermédiaire et avancé, des bases en informatique sont recommandées.'],
        ['Les formations sont-elles certifiantes?','Oui, chaque formation se conclut par un certificat reconnu par nos entreprises partenaires et valable sur le marché de l\'emploi national et régional.'],
        ['Peut-on payer en plusieurs fois?','Oui, nous offrons des facilités de paiement en 2 ou 3 tranches pour la majorité de nos formations. Contactez-nous pour en savoir plus.'],
        ['Y a-t-il des cours en ligne?','Nos formations sont principalement en présentiel dans nos locaux. Des supports numériques (PDF, vidéos) sont disponibles sur la plateforme pour compléter les cours.'],
        ['Quelle est la taille des groupes?','Nos groupes sont limités à 15-20 étudiants maximum pour garantir un suivi personnalisé et une qualité d\'apprentissage optimale.'],
      ]; ?>
      <?php foreach($faqs as $i=>$faq): ?>
      <div class="faq-item" style="margin-bottom:12px;">
        <div class="faq-question" onclick="toggleFaq(<?= $i ?>)" style="padding:20px 24px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius-sm);cursor:pointer;display:flex;justify-content:space-between;align-items:center;transition:var(--transition);">
          <span style="font-weight:600;color:var(--text-primary);"><?= $faq[0] ?></span>
          <span id="faqIcon<?= $i ?>" style="color:var(--accent);font-size:1.2rem;transition:transform .3s;">+</span>
        </div>
        <div id="faqAnswer<?= $i ?>" style="display:none;padding:20px 24px;background:rgba(255,255,255,.02);border:1px solid var(--border);border-top:none;border-radius:0 0 var(--radius-sm) var(--radius-sm);">
          <p style="font-size:.88rem;color:var(--text-secondary);line-height:1.8;"><?= $faq[1] ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include_once 'includes/footer.php'; ?>
<?php include_once 'includes/auth_modal.php'; ?>

<script src="js/main.js"></script>
<script>
function toggleFaq(i) {
  const answer = document.getElementById('faqAnswer'+i);
  const icon = document.getElementById('faqIcon'+i);
  const isOpen = answer.style.display === 'block';
  answer.style.display = isOpen ? 'none' : 'block';
  icon.textContent = isOpen ? '+' : '−';
  icon.style.transform = isOpen ? 'rotate(0)' : 'rotate(45deg)';
}
</script>
</body>
</html>
