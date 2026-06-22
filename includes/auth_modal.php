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
    <div class="auth-panel" id="loginPanel">
      <form id="loginForm">
        <div class="form-group"><label class="form-label">📧 Email</label><input type="email" name="email" class="form-input" placeholder="votre@email.com" required></div>
        <div class="form-group"><label class="form-label">🔒 Mot de passe</label><input type="password" name="password" class="form-input" placeholder="••••••••" required></div>
        <div style="margin-bottom:20px;text-align:right;"><a href="#" style="color:var(--accent);font-size:.83rem;" onclick="document.querySelector('[data-tab=forgot]').click();return false;">Mot de passe oublié?</a></div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">Se connecter →</button>
        <div style="text-align:center;margin-top:12px;font-size:.78rem;color:var(--text-muted);"><strong style="color:var(--accent);">Admin:</strong> admin@laboformation.cm / password &nbsp;|&nbsp; <strong style="color:var(--accent);">Étudiant:</strong> jean.kamga@email.com / password</div>
      </form>
    </div>
    <div class="auth-panel" id="registerPanel" style="display:none;">
      <form id="registerForm">
        <div class="form-row">
          <div class="form-group"><label class="form-label">Prénom</label><input type="text" name="prenom" class="form-input" placeholder="Jean" required></div>
          <div class="form-group"><label class="form-label">Nom</label><input type="text" name="nom" class="form-input" placeholder="Kamga" required></div>
        </div>
        <div class="form-group"><label class="form-label">📧 Email</label><input type="email" name="email" class="form-input" placeholder="votre@email.com" required></div>
        <div class="form-group"><label class="form-label">📱 Téléphone</label><input type="tel" name="phone" class="form-input" placeholder="+237 6XX XXX XXX"></div>
        <div class="form-group"><label class="form-label">🔒 Mot de passe</label><input type="password" name="password" class="form-input" placeholder="••••••••" required minlength="6"></div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">Créer mon compte ✨</button>
      </form>
    </div>
    <div class="auth-panel" id="forgotPanel" style="display:none;">
      <form id="forgotForm">
        <p style="font-size:.88rem;color:var(--text-secondary);margin-bottom:20px;">Entrez votre email pour recevoir un lien de réinitialisation.</p>
        <div class="form-group"><label class="form-label">📧 Email</label><input type="email" name="email" class="form-input" placeholder="votre@email.com" required></div>
        <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">Envoyer le lien 📨</button>
      </form>
    </div>
  </div>
</div>

<div class="modal-overlay" id="paymentModal">
  <div class="modal" style="max-width:480px;">
    <button class="modal-close">✕</button>
    <div style="text-align:center;margin-bottom:24px;"><span style="font-size:3rem;">💳</span><h2 class="modal-title">Finaliser le paiement</h2></div>
    <div style="padding:20px;background:rgba(212,160,23,0.08);border:1px solid var(--border);border-radius:var(--radius-sm);margin-bottom:24px;">
      <div style="font-size:.85rem;color:var(--text-muted);margin-bottom:4px;">Sélection</div>
      <div style="font-weight:700;color:var(--text-primary);margin-bottom:8px;" id="paymentItemTitle">-</div>
      <div style="font-family:var(--font-display);font-size:1.8rem;font-weight:900;color:var(--accent);" id="paymentItemPrice">-</div>
    </div>
    <input type="hidden" id="paymentItemType">
    <input type="hidden" id="paymentItemId">
    <form id="paymentForm">
      <div class="form-group">
        <label class="form-label">💳 Méthode de paiement</label>
        <select name="methode" class="form-select">
          <option value="MTN Mobile Money">📱 MTN Mobile Money</option>
          <option value="Orange Money">🟠 Orange Money</option>
          <option value="Virement Bancaire">🏦 Virement Bancaire</option>
          <option value="Espèces">💵 Espèces (au bureau)</option>
        </select>
      </div>
      <div class="form-group"><label class="form-label">📞 N° transaction (optionnel)</label><input type="text" name="transaction" class="form-input" placeholder="Ex: TRX123456789"></div>
      <button type="submit" class="btn-primary" style="width:100%;justify-content:center;padding:14px;">✅ Confirmer le paiement</button>
    </form>
  </div>
</div>
