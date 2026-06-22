<?php
require_once 'php/config.php';
if (isLoggedIn()) {
    redirect(isAdmin() ? BASE_URL.'/admin/dashboard.php' : BASE_URL.'/student/dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Connexion - LaboFormation</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="loading-screen"><div class="loading-logo">L</div><div class="loading-spinner"></div></div>

<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--gradient-hero);padding:20px;position:relative;overflow:hidden;">
  <div class="hero-grid" style="position:absolute;inset:0;"></div>
  <div style="position:absolute;inset:0;background:radial-gradient(ellipse at 50%,rgba(212,160,23,0.08),transparent 70%);"></div>

  <div style="width:100%;max-width:460px;position:relative;z-index:1;">
    <!-- Logo -->
    <div style="text-align:center;margin-bottom:32px;">
      <a href="index.php" style="text-decoration:none;display:inline-block;">
        <div style="width:70px;height:70px;background:var(--gradient-gold);border-radius:20px;display:flex;align-items:center;justify-content:center;font-family:var(--font-display);font-weight:900;font-size:2rem;color:var(--primary);margin:0 auto 12px;box-shadow:var(--shadow-gold);">L</div>
        <div style="font-family:var(--font-display);font-size:1.4rem;font-weight:900;color:var(--text-primary);">LaboFormation</div>
        <div style="font-size:0.78rem;color:var(--accent);letter-spacing:.1em;text-transform:uppercase;">Excellence Numérique</div>
      </a>
    </div>

    <!-- Card -->
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:24px;padding:40px;box-shadow:var(--shadow-card);">
      <div class="auth-tabs" style="margin-bottom:28px;">
        <button class="auth-tab active" data-tab="login" id="tabLogin">🔑 Connexion</button>
        <button class="auth-tab" data-tab="register" id="tabRegister">✨ Inscription</button>
      </div>

      <!-- LOGIN -->
      <div class="auth-panel" id="loginPanel">
        <form id="loginForm">
          <div class="form-group">
            <label class="form-label">📧 Adresse email</label>
            <input type="email" name="email" class="form-input" placeholder="votre@email.com" required autocomplete="email">
          </div>
          <div class="form-group">
            <label class="form-label">🔒 Mot de passe</label>
            <div style="position:relative;">
              <input type="password" name="password" id="loginPassword" class="form-input" placeholder="••••••••" required>
              <button type="button" onclick="togglePass('loginPassword')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);">👁️</button>
            </div>
          </div>
          <div style="text-align:right;margin-bottom:24px;">
            <a href="#" onclick="document.querySelector('[data-tab=forgot]')?.click()||showForgot()" style="color:var(--accent);font-size:.83rem;">Mot de passe oublié?</a>
          </div>
          <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:15px;font-size:1rem;">
            Se connecter →
          </button>
          <div style="text-align:center;margin-top:20px;padding:16px;background:rgba(212,160,23,.06);border:1px solid var(--border);border-radius:var(--radius-sm);">
            <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:6px;">Comptes de démonstration</div>
            <div style="font-size:.8rem;"><strong style="color:var(--accent);">Admin:</strong> admin@laboformation.cm</div>
            <div style="font-size:.8rem;"><strong style="color:var(--accent);">Étudiant:</strong> jean.kamga@email.com</div>
            <div style="font-size:.78rem;color:var(--text-muted);margin-top:4px;">Mot de passe: <strong>password</strong></div>
          </div>
        </form>
      </div>

      <!-- REGISTER -->
      <div class="auth-panel" id="registerPanel" style="display:none;">
        <form id="registerForm">
          <div class="form-row">
            <div class="form-group"><label class="form-label">Prénom *</label><input type="text" name="prenom" class="form-input" placeholder="Jean" required></div>
            <div class="form-group"><label class="form-label">Nom *</label><input type="text" name="nom" class="form-input" placeholder="Kamga" required></div>
          </div>
          <div class="form-group"><label class="form-label">📧 Email *</label><input type="email" name="email" class="form-input" placeholder="votre@email.com" required></div>
          <div class="form-group"><label class="form-label">📱 Téléphone</label><input type="tel" name="phone" class="form-input" placeholder="+237 6XX XXX XXX"></div>
          <div class="form-group">
            <label class="form-label">🔒 Mot de passe *</label>
            <div style="position:relative;">
              <input type="password" name="password" id="regPassword" class="form-input" placeholder="Minimum 6 caractères" required minlength="6">
              <button type="button" onclick="togglePass('regPassword')" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-muted);">👁️</button>
            </div>
          </div>
          <div style="margin-bottom:20px;font-size:.82rem;color:var(--text-muted);">
            En créant un compte, vous acceptez nos <a href="#" style="color:var(--accent);">conditions d'utilisation</a>.
          </div>
          <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:15px;font-size:1rem;">
            Créer mon compte ✨
          </button>
        </form>
      </div>

      <!-- FORGOT (hidden by default, shown on demand) -->
      <div class="auth-panel" id="forgotPanel" style="display:none;">
        <p style="font-size:.88rem;color:var(--text-secondary);margin-bottom:20px;">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        <form id="forgotForm">
          <div class="form-group"><label class="form-label">📧 Votre email</label><input type="email" name="email" class="form-input" placeholder="votre@email.com" required></div>
          <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:15px;">📨 Envoyer le lien</button>
          <div style="text-align:center;margin-top:16px;"><a href="#" onclick="showLogin()" style="color:var(--accent);font-size:.83rem;">← Retour à la connexion</a></div>
        </form>
      </div>
    </div>

    <div style="text-align:center;margin-top:20px;font-size:.85rem;color:var(--text-muted);">
      <a href="index.php" style="color:var(--accent);">← Retour à l'accueil</a>
    </div>
  </div>
</div>

<script src="js/main.js"></script>
<script>
function togglePass(id) {
  const input = document.getElementById(id);
  input.type = input.type === 'password' ? 'text' : 'password';
}

function showForgot() {
  ['loginPanel','registerPanel'].forEach(p => document.getElementById(p).style.display='none');
  document.getElementById('forgotPanel').style.display = 'block';
  document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
}

function showLogin() {
  ['forgotPanel','registerPanel'].forEach(p => document.getElementById(p).style.display='none');
  document.getElementById('loginPanel').style.display = 'block';
  document.querySelector('[data-tab="login"]').classList.add('active');
}

// Override auth modal init for this page
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.auth-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.tab;
      document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      document.querySelectorAll('.auth-panel').forEach(p => p.style.display = 'none');
      document.getElementById(target + 'Panel').style.display = 'block';
    });
  });

  document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    btn.disabled = true; btn.textContent = 'Connexion...';
    const result = await apiCall('php/auth.php', 'POST', {
      action: 'login',
      email: e.target.querySelector('[name="email"]').value,
      password: e.target.querySelector('[name="password"]').value
    });
    if (result.success) { toast(result.message, 'success'); setTimeout(() => window.location.href = result.redirect, 900); }
    else { toast(result.message, 'error'); btn.disabled=false; btn.textContent='Se connecter →'; }
  });

  document.getElementById('registerForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    btn.disabled = true;
    const result = await apiCall('php/auth.php', 'POST', {
      action: 'register',
      nom: e.target.querySelector('[name="nom"]').value,
      prenom: e.target.querySelector('[name="prenom"]').value,
      email: e.target.querySelector('[name="email"]').value,
      password: e.target.querySelector('[name="password"]').value,
      phone: e.target.querySelector('[name="phone"]')?.value || ''
    });
    toast(result.message, result.success ? 'success' : 'error');
    if (result.success) document.querySelector('[data-tab="login"]').click();
    btn.disabled = false;
  });

  document.getElementById('forgotForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const result = await apiCall('php/auth.php', 'POST', { action: 'forgot_password', email: e.target.querySelector('[name="email"]').value });
    toast(result.message, result.success ? 'success' : 'error');
  });
});
</script>
</body>
</html>
