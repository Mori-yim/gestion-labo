<?php
require_once 'php/config.php';
$isLoggedIn = isLoggedIn();
$userRole = $_SESSION['role'] ?? '';
$userName = isset($_SESSION['prenom']) ? $_SESSION['prenom'] . ' ' . $_SESSION['nom'] : '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LaboFormation - Centre d'Excellence Numérique</title>
  <meta name="description" content="Centre de formation professionnelle en informatique, IA, cybersécurité et digital. Formez-vous avec les meilleurs experts au Cameroun.">
  <link rel="stylesheet" href="css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
</head>
<body data-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>">

<!-- Loading Screen -->
<div class="loading-screen">
  <div class="loading-logo">L</div>
  <div class="loading-spinner"></div>
</div>

<!-- ===== NAVIGATION ===== -->
<nav class="navbar" id="navbar">
  <a href="index.php" class="nav-logo">
    <div class="logo-icon">L</div>
    <div class="logo-text">
      <span class="logo-name">LaboFormation</span>
      <span class="logo-tagline">Excellence Numérique</span>
    </div>
  </a>

  <ul class="nav-links">
    <li><a href="index.php" class="active">Accueil</a></li>
    <li><a href="about.php">À propos</a></li>
    <li><a href="formations.php">Formations</a></li>
    <li><a href="services.php">Services</a></li>
    <li><a href="paiement.php">Paiement</a></li>
    <li><a href="contact.php">Contact</a></li>
    <?php if ($isLoggedIn): ?>
      <li><a href="<?= $userRole === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php' ?>">Mon Espace</a></li>
    <?php endif; ?>
  </ul>

  <div class="nav-actions">
    <?php if ($isLoggedIn): ?>
      <span style="color:var(--accent);font-size:0.88rem;font-weight:600;">
         <?= htmlspecialchars($userName) ?>
      </span>
      <a href="php/auth.php?action=logout" class="btn-login">Déconnexion</a>
    <?php else: ?>
      <button class="btn-login" id="loginBtn">Connexion</button>
      <button class="btn-primary" onclick="openModal('authModal');setTimeout(()=>document.querySelector('[data-tab=register]').click(),100)">
        S'inscrire 
      </button>
    <?php endif; ?>
    <div class="hamburger">
      <span></span><span></span><span></span>
    </div>
  </div>
</nav>

<!-- ===== HERO SECTION ===== -->
<section class="hero" id="accueil">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="hero-particles"></div>

  <div class="hero-content">
    <div class="hero-text">
      <div class="hero-badge">
        <span></span> Centre Certifié d'Excellence Numérique
      </div>
      <h1 class="hero-title">
        Formez-vous aux<br>
        <span class="gold">Métiers du Futur</span><br>
        Digital & Tech
      </h1>
      <p class="hero-subtitle">
        LaboFormation vous propulse vers l'excellence numérique. 
        Développement web, IA, cybersécurité, design — des formations 
        de haut niveau par des experts certifiés.
      </p>
      <div class="hero-cta">
        <a href="formations.php" class="btn-primary">
           Voir les formations
        </a>
        <a href="about.php" class="btn-secondary">
           En savoir plus
        </a>
      </div>

      <div class="hero-stats">
        <div class="stat-item">
          <span class="stat-number" data-count="500" data-stat="inscriptions" data-suffix="+">500+</span>
          <span class="stat-label">Diplômés</span>
        </div>
        <div class="stat-item">
          <span class="stat-number" data-count="6" data-stat="formations" data-suffix="">6</span>
          <span class="stat-label">Formations</span>
        </div>
        <div class="stat-item">
          <span class="stat-number" data-count="98" data-suffix="%">98%</span>
          <span class="stat-label">Satisfaction</span>
        </div>
      </div>
    </div>

    <div class="hero-visual">
      <div class="hero-banner">
        <div class="hero-banner-bg"></div>
        <div class="hero-banner-content">
          <span class="hero-banner-emoji">🎓</span>
          <div class="hero-banner-text">
            L'Excellence<br>Numérique commence ici
          </div>
          <div class="hero-banner-sub">LaboFormation • Douala, Cameroun</div>
        </div>

        <div class="floating-card" style="top:12%;right:-30px;padding:14px 18px;">
          <div style="font-size:0.75rem;color:var(--text-muted);margin-bottom:4px;">Étudiants actifs</div>
          <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:900;color:var(--accent);" data-count="120" data-suffix="">120</div>
        </div>

        <div class="floating-card" style="bottom:15%;left:-30px;padding:14px 18px;">
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="font-size:1.5rem;">⭐</span>
            <div>
              <div style="font-weight:700;font-size:0.92rem;">4.9/5</div>
              <div style="font-size:0.72rem;color:var(--text-muted);">Note moyenne</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ===== CAROUSEL SECTION ===== -->
<section class="carousel-section">
  <div style="max-width:1200px;margin:0 auto;padding:0 5%;">
    <div class="section-header">
      <div class="section-badge"> Notre Labo</div>
      <h2 class="section-title">Au cœur de <span class="gold">l'Innovation</span></h2>
      <p class="section-desc">Découvrez notre environnement d'apprentissage moderne et équipé.</p>
    </div>
    
    <div class="carousel-wrapper">
      <div class="carousel-track">
        
        <div class="carousel-slide">
          <div class="slide-bg" style="background:linear-gradient(135deg,#0a2040,#1a4080);"></div>
          <div class="slide-content">
            <span class="slide-icon"></span>
            <h3 class="slide-title">Salle Informatique Ultramoderne</h3>
            <p class="slide-sub">50 postes haute performance • Connexion fibre 1Gbps</p>
          </div>
        </div>

        <div class="carousel-slide">
          <div class="slide-bg" style="background:linear-gradient(135deg,#1a0a40,#4a1a80);"></div>
          <div class="slide-content">
            <span class="slide-icon">🤖</span>
            <h3 class="slide-title">Lab Intelligence Artificielle</h3>
            <p class="slide-sub">GPUs dédiés • TensorFlow • PyTorch • Kubernetes</p>
          </div>
        </div>

        <div class="carousel-slide">
          <div class="slide-bg" style="background:linear-gradient(135deg,#0a2030,#1a5060);"></div>
          <div class="slide-content">
            <span class="slide-icon">🔐</span>
            <h3 class="slide-title">Cyber Range - Sécurité</h3>
            <p class="slide-sub">Environnement CTF • Tests d'intrusion • Veille sécurité</p>
          </div>
        </div>

        <div class="carousel-slide">
          <div class="slide-bg" style="background:linear-gradient(135deg,#201a0a,#604020);"></div>
          <div class="slide-content">
            <span class="slide-icon"></span>
            <h3 class="slide-title">Studio Créatif & Design</h3>
            <p class="slide-sub">Tablettes graphiques • Figma • Adobe CC • Impression 3D</p>
          </div>
        </div>

      </div>
      <button class="carousel-btn prev">&#8592;</button>
      <button class="carousel-btn next">&#8594;</button>
    </div>
    <div class="carousel-dots">
      <div class="dot active"></div>
      <div class="dot"></div>
      <div class="dot"></div>
      <div class="dot"></div>
    </div>
  </div>
</section>

<!-- ===== FEATURES SECTION ===== -->
<section class="features-section">
  <div class="section-header" style="max-width:700px;margin:0 auto 60px;text-align:center;">
    <div class="section-badge"> Pourquoi nous choisir</div>
    <h2 class="section-title">Votre Réussite, <span class="gold">Notre Mission</span></h2>
    <p class="section-desc">Un environnement d'apprentissage pensé pour vous propulser vers le succès professionnel.</p>
  </div>
  
  <div class="features-grid" style="max-width:1200px;margin:0 auto;">
    <div class="feature-card">
      <div class="feature-icon">🏆</div>
      <h3 class="feature-title">Formateurs Experts</h3>
      <p class="feature-desc">Nos formateurs sont des professionnels actifs avec 10+ ans d'expérience dans leurs domaines respectifs.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"></div>
      <h3 class="feature-title">Formations Certifiantes</h3>
      <p class="feature-desc">Obtenez des certifications reconnues par les entreprises nationales et internationales.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"></div>
      <h3 class="feature-title">Projets Réels</h3>
      <p class="feature-desc">80% de pratique sur des projets concrets. Sortez avec un portfolio impressionnant.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">👥</div>
      <h3 class="feature-title">Accompagnement Emploi</h3>
      <p class="feature-desc">Réseau de 200+ entreprises partenaires. Stage et insertion professionnelle garantis.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon">⚡</div>
      <h3 class="feature-title">Équipements Top</h3>
      <p class="feature-desc">Matériel de dernière génération, connexion ultra-rapide, logiciels professionnels.</p>
    </div>
    <div class="feature-card">
      <div class="feature-icon"></div>
      <h3 class="feature-title">Financement Flexible</h3>
      <p class="feature-desc">Paiement en tranches, bourses disponibles, tarifs accessibles pour tous les profils.</p>
    </div>
  </div>
</section>

<!-- ===== FORMATIONS APERÇU ===== -->
<section style="background:var(--bg-dark);padding:100px 5%;">
  <div class="section-header" style="max-width:700px;margin:0 auto 60px;text-align:center;">
    <div class="section-badge"> Nos Formations</div>
    <h2 class="section-title">Choisissez votre <span class="gold">Spécialité</span></h2>
    <p class="section-desc">Des programmes complets et actualisés pour maîtriser les technologies les plus demandées.</p>
  </div>
  <div class="cards-grid" id="formationsGrid">
    <!-- Chargé par JS -->
  </div>
  <div style="text-align:center;margin-top:40px;">
    <a href="formations.php" class="btn-primary">Voir toutes les formations →</a>
  </div>
</section>

<!-- ===== TESTIMONIALS ===== -->
<section class="testimonials-section">
  <div class="section-header" style="max-width:700px;margin:0 auto 60px;text-align:center;">
    <div class="section-badge">💬 Témoignages</div>
    <h2 class="section-title">Ils nous font <span class="gold">confiance</span></h2>
  </div>
  <div class="testimonials-grid" style="max-width:1100px;margin:0 auto;">
    
    <div class="testimonial-card">
      <div class="quote-mark">"</div>
      <div class="stars">★★★★★</div>
      <p class="testimonial-text">La formation en développement web m'a permis de décrocher un emploi dès la fin du programme. Les formateurs sont exceptionnels et les cours très pratiques.</p>
      <div class="testimonial-author">
        <div class="author-avatar">JK</div>
        <div>
          <div class="author-name">Jean Kamga</div>
          <div class="author-role">Développeur Web Junior • Orange Cameroun</div>
        </div>
      </div>
    </div>

    <div class="testimonial-card">
      <div class="quote-mark">"</div>
      <div class="stars">★★★★★</div>
      <p class="testimonial-text">L'environnement d'apprentissage est incroyable. J'ai pu apprendre l'IA dans un vrai lab avec des GPUs. Mes compétences ont explosé en 4 mois!</p>
      <div class="testimonial-author">
        <div class="author-avatar">MN</div>
        <div>
          <div class="author-name">Marie Nkomo</div>
          <div class="author-role">Data Scientist • MTN Cameroun</div>
        </div>
      </div>
    </div>

    <div class="testimonial-card">
      <div class="quote-mark">"</div>
      <div class="stars">★★★★★</div>
      <p class="testimonial-text">Après la formation en cybersécurité, j'ai lancé ma propre startup de conseil en sécurité. LaboFormation m'a donné les outils et la confiance nécessaires.</p>
      <div class="testimonial-author">
        <div class="author-avatar">PB</div>
        <div>
          <div class="author-name">Patrick Bello</div>
          <div class="author-role">CEO • SecureIT Cameroun</div>
        </div>
      </div>
    </div>

  </div>
</section>

<!-- ===== CTA SECTION ===== -->
<section class="cta-section">
  <div style="max-width:800px;margin:0 auto;text-align:center;">
    <div class="section-badge"> Commencez aujourd'hui</div>
    <h2 class="section-title">Prêt à transformer<br><span class="gold">votre carrière?</span></h2>
    <p class="section-desc" style="margin-bottom:40px;">
      Rejoignez plus de 500 diplômés qui ont bâti leur avenir numérique avec LaboFormation.
    </p>
    <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
      <a href="formations.php" class="btn-primary" style="font-size:1rem;padding:16px 36px;">
         S'inscrire maintenant
      </a>
      <a href="contact.php" class="btn-secondary" style="font-size:1rem;padding:16px 36px;">
        📞 Nous contacter
      </a>
    </div>
  </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
  <div class="footer-grid">
    <div class="footer-about">
      <a href="index.php" class="nav-logo" style="justify-content:flex-start;">
        <div class="logo-icon">L</div>
        <div class="logo-text">
          <span class="logo-name">LaboFormation</span>
          <span class="logo-tagline">Excellence Numérique</span>
        </div>
      </a>
      <p>Centre de formation professionnelle spécialisé dans les technologies de l'information et du numérique. Votre partenaire pour une carrière tech réussie.</p>
      <div class="footer-social">
        <a href="#" class="social-btn" title="Facebook">📘</a>
        <a href="#" class="social-btn" title="Twitter">🐦</a>
        <a href="#" class="social-btn" title="LinkedIn">💼</a>
        <a href="#" class="social-btn" title="YouTube">▶️</a>
        <a href="#" class="social-btn" title="Instagram">📸</a>
      </div>
    </div>

    <div>
      <h4 class="footer-heading">Navigation</h4>
      <ul class="footer-links">
        <li><a href="index.php">→ Accueil</a></li>
        <li><a href="about.php">→ À propos</a></li>
        <li><a href="formations.php">→ Formations</a></li>
        <li><a href="services.php">→ Services</a></li>
        <li><a href="contact.php">→ Contact</a></li>
      </ul>
    </div>

    <div>
      <h4 class="footer-heading">Formations</h4>
      <ul class="footer-links">
        <li><a href="formations.php">→ Développement Web</a></li>
        <li><a href="formations.php">→ Intelligence Artificielle</a></li>
        <li><a href="formations.php">→ Cybersécurité</a></li>
        <li><a href="formations.php">→ Design UI/UX</a></li>
        <li><a href="formations.php">→ Mobile Dev</a></li>
      </ul>
    </div>

    <div>
      <h4 class="footer-heading">Contact</h4>
      <div class="footer-contact-item">
        <span>📍</span>
        <span>Rue de la Joie, Akwa<br>Douala, Cameroun</span>
      </div>
      <div class="footer-contact-item">
        <span>📞</span>
        <span>+237 6 99 00 00 01<br>+237 6 77 00 00 02</span>
      </div>
      <div class="footer-contact-item">
        <span>📧</span>
        <span>info@laboformation.cm</span>
      </div>
      <div class="footer-contact-item">
        <span>🕐</span>
        <span>Lun - Sam: 8h - 18h</span>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    <span>© 2024 LaboFormation. Tous droits réservés.</span>
    <span>Fait avec ❤️ au Cameroun 🇨🇲</span>
  </div>
</footer>

<!-- ===== MODAL AUTH ===== -->
<div class="modal-overlay" id="authModal">
  <div class="modal" style="max-width:480px;">
    <button class="modal-close">✕</button>
    
    <div style="text-align:center;margin-bottom:24px;">
      <div style="width:60px;height:60px;background:var(--gradient-gold);border-radius:16px;display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-weight:900;font-size:1.8rem;color:var(--primary);margin:0 auto 12px;">L</div>
      <h2 class="modal-title">Bienvenue!</h2>
      <p class="modal-subtitle">Connectez-vous ou créez votre compte</p>
    </div>

    <div class="auth-tabs">
      <button class="auth-tab active" data-tab="login">Connexion</button>
      <button class="auth-tab" data-tab="register">Inscription</button>
      <button class="auth-tab" data-tab="forgot">Mot de passe</button>
    </div>

    <!-- Login Panel -->
    <div class="auth-panel" id="loginPanel">
      <form id="loginForm">
        <div class="form-group">
          <label class="form-label">📧 Adresse email</label>
          <input type="email" name="email" class="form-input" placeholder="votre@email.com" required>
        </div>
        <div class="form-group">
          <label class="form-label">🔒 Mot de passe</label>
          <input type="password" name="password" class="form-input" placeholder="••••••••" required>
        </div>
        <div style="margin-bottom:20px;text-align:right;">
          <a href="#" style="color:var(--accent);font-size:0.83rem;" onclick="document.querySelector('[data-tab=forgot]').click();return false;">Mot de passe oublié?</a>
        </div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">
          Se connecter →
        </button>
        <div style="text-align:center;margin-top:16px;font-size:0.82rem;color:var(--text-muted);">
          <strong style="color:var(--accent);">Démo Admin:</strong> admin@laboformation.cm / password<br>
          <strong style="color:var(--accent);">Démo Étudiant:</strong> jean.kamga@email.com / password
        </div>
      </form>
    </div>

    <!-- Register Panel -->
    <div class="auth-panel" id="registerPanel" style="display:none;">
      <form id="registerForm">
        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-input" placeholder="Jean" required>
          </div>
          <div class="form-group">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-input" placeholder="Kamga" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">📧 Email</label>
          <input type="email" name="email" class="form-input" placeholder="votre@email.com" required>
        </div>
        <div class="form-group">
          <label class="form-label">📱 Téléphone</label>
          <input type="tel" name="phone" class="form-input" placeholder="+237 6XX XXX XXX">
        </div>
        <div class="form-group">
          <label class="form-label">🔒 Mot de passe (min. 6 caractères)</label>
          <input type="password" name="password" class="form-input" placeholder="••••••••" required minlength="6">
        </div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">
          Créer mon compte 
        </button>
      </form>
    </div>

    <!-- Forgot Panel -->
    <div class="auth-panel" id="forgotPanel" style="display:none;">
      <form id="forgotForm">
        <p style="font-size:0.88rem;color:var(--text-secondary);margin-bottom:20px;">
          Entrez votre email et nous vous enverrons un lien de réinitialisation.
        </p>
        <div class="form-group">
          <label class="form-label">📧 Votre email</label>
          <input type="email" name="email" class="form-input" placeholder="votre@email.com" required>
        </div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">
          Envoyer le lien 📨
        </button>
      </form>
    </div>
  </div>
</div>

<!-- ===== MODAL PAIEMENT ===== -->
<div class="modal-overlay" id="paymentModal">
  <div class="modal" style="max-width:480px;">
    <button class="modal-close">✕</button>
    <div style="text-align:center;margin-bottom:24px;">
      <span style="font-size:3rem;">💳</span>
      <h2 class="modal-title">Finaliser le paiement</h2>
    </div>
    
    <div style="padding:20px;background:rgba(212,160,23,0.08);border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:24px;">
      <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:4px;">Formation / Service sélectionné</div>
      <div style="font-weight:700;color:var(--text-primary);margin-bottom:8px;" id="paymentItemTitle">-</div>
      <div style="font-family:var(--font-display);font-size:1.8rem;font-weight:900;color:var(--accent);" id="paymentItemPrice">-</div>
    </div>

    <input type="hidden" id="paymentItemType">
    <input type="hidden" id="paymentItemId">

    <form id="paymentForm">
      <div class="form-group">
        <label class="form-label">💳 Méthode de paiement</label>
        <select name="methode" class="form-select">
          <option value="MTN Mobile Money"> MTN Mobile Money</option>
          <option value="Orange Money"> Orange Money</option>
          <option value="Virement Bancaire"> Virement Bancaire</option>
          <option value="Espèces"> Espèces (au bureau)</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">📞 Numéro de transaction (optionnel)</label>
        <input type="text" name="transaction" class="form-input" placeholder="Ex: TRX123456789">
      </div>
      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">
         Confirmer le paiement
      </button>
    </form>
  </div>
</div>

<script src="js/main.js"></script>
<script>
// Payment form handler
document.getElementById('paymentForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = this.querySelector('button[type="submit"]');
  btn.disabled = true;
  btn.textContent = 'Traitement...';
  
  const type = document.getElementById('paymentItemType').value;
  const id = document.getElementById('paymentItemId').value;
  const methode = this.querySelector('[name="methode"]').value;
  
  const action = type === 'formation' ? 'souscrire_formation' : 'souscrire_service';
  const bodyKey = type === 'formation' ? 'formation_id' : 'service_id';
  
  const result = await apiCall('api.php?action=' + action, 'POST', {
    [bodyKey]: id,
    methode: methode
  });
  
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) {
    closeModal('paymentModal');
  }
  btn.disabled = false;
  btn.textContent = ' Confirmer le paiement';
});
</script>
</body>
</html>
