// =============================================
// DASHBOARD ÉTUDIANT - JavaScript
// =============================================

const API = '../php/api.php';

function showSection(name, link) {
  document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.sidebar-nav a').forEach(a => a.classList.remove('active'));
  document.getElementById('section-' + name)?.classList.add('active');
  if (link) link.classList.add('active');
  document.getElementById('notifPanel').style.display = 'none';

  const loaders = {
    'tableau_bord': loadTableauBord,
    'mes_formations': loadMesFormations,
    'mes_services': loadMesServices,
    'emploi_temps': loadEmploiTemps,
    'documents': loadDocuments,
    'paiements': loadPaiements,
  };
  if (loaders[name]) loaders[name]();
}

function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('mobile-open');
}

// ===== TABLEAU DE BORD =====
async function loadTableauBord() {
  // Charger stats
  const [formations, services, docs, notifs] = await Promise.all([
    apiCall(API + '?action=get_mes_formations'),
    apiCall(API + '?action=get_mes_services'),
    apiCall(API + '?action=get_documents'),
    apiCall(API + '?action=get_notifications'),
  ]);

  document.getElementById('myFormationsCount').textContent = formations.success ? formations.data.length : '-';
  document.getElementById('myServicesCount').textContent = services.success ? services.data.length : '-';
  document.getElementById('myDocsCount').textContent = docs.success ? docs.data.length : '-';
  document.getElementById('myNotifCount').textContent = notifs.success ? notifs.unread_count : '-';

  // Prochains cours
  const emploi = await apiCall(API + '?action=get_emploi_temps');
  const nextCont = document.getElementById('nextCourses');
  if (emploi.success && emploi.data.length > 0) {
    nextCont.innerHTML = emploi.data.slice(0,4).map(e => `
      <div style="padding:12px;border-bottom:1px solid rgba(255,255,255,.05);display:flex;gap:12px;align-items:center;">
        <div style="padding:8px 12px;background:rgba(212,160,23,.1);border-radius:var(--radius-sm);text-align:center;min-width:70px;">
          <div style="font-size:.75rem;color:var(--accent);font-weight:700;">${e.jour||'?'}</div>
          <div style="font-size:.7rem;color:var(--text-muted)">${e.heure_debut?.slice(0,5)||''}</div>
        </div>
        <div>
          <div style="font-weight:600;font-size:.88rem;color:var(--text-primary)">${e.titre}</div>
          <div style="font-size:.78rem;color:var(--text-muted)">${e.salle||''} ${e.formateur?'• '+e.formateur:''}</div>
        </div>
      </div>
    `).join('');
  } else {
    nextCont.innerHTML = '<div style="text-align:center;padding:20px;color:var(--text-muted);font-size:.85rem;">Aucun cours planifié</div>';
  }

  // Docs récents
  const recentCont = document.getElementById('recentDocs');
  if (docs.success && docs.data.length > 0) {
    recentCont.innerHTML = docs.data.slice(0,4).map(d => `
      <div style="padding:12px;border-bottom:1px solid rgba(255,255,255,.05);display:flex;gap:12px;align-items:center;">
        <span style="font-size:1.5rem;">📄</span>
        <div>
          <div style="font-weight:600;font-size:.85rem;color:var(--text-primary)">${d.titre}</div>
          <div style="font-size:.75rem;color:var(--text-muted)">${d.type_fichier||'PDF'} • ${formatDate(d.created_at)}</div>
        </div>
      </div>
    `).join('');
  } else {
    recentCont.innerHTML = '<div style="text-align:center;padding:20px;color:var(--text-muted);font-size:.85rem;">Aucun document disponible</div>';
  }
}

// ===== MES FORMATIONS =====
async function loadMesFormations() {
  const result = await apiCall(API + '?action=get_mes_formations');
  const grid = document.getElementById('mesFormationsGrid');
  
  if (!result.success || !result.data.length) {
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px;"><div style="font-size:4rem;margin-bottom:16px;">📚</div><p style="color:var(--text-muted);">Vous n\'êtes inscrit à aucune formation pour le moment.</p><a href="../formations.php" class="btn-primary" style="display:inline-flex;margin-top:16px;">Voir les formations</a></div>';
    return;
  }

  const icons = ['💻','🤖','🔐','🎨','📱','💼'];
  grid.innerHTML = result.data.map((f, i) => `
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);overflow:hidden;">
      <div style="height:150px;background:linear-gradient(135deg,#0a1628,#1a3a6b);display:flex;align-items:center;justify-content:center;font-size:3.5rem;position:relative;">
        ${icons[i%icons.length]}
        <div style="position:absolute;top:12px;right:12px;">${f.statut_inscription==='confirme'?'<span class="badge badge-success">✅ Confirmé</span>':'<span class="badge badge-warning">⏳ En attente</span>'}</div>
      </div>
      <div style="padding:20px;">
        <h3 style="font-weight:700;font-size:.95rem;margin-bottom:6px;">${f.titre}</h3>
        <p style="font-size:.82rem;color:var(--text-secondary);margin-bottom:12px;">${f.description_courte||''}</p>
        <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:12px;">
          📅 Inscrit le ${formatDate(f.date_inscription)}<br>
          🔖 Réf: <span style="color:var(--accent)">${f.reference_paiement||'-'}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding-top:12px;border-top:1px solid rgba(255,255,255,.06);">
          <span style="font-family:var(--font-display);font-size:1.1rem;font-weight:900;color:var(--accent)">${formatMontant(f.montant_paye||f.prix)}</span>
          <a href="../formations.php" class="btn-card" style="font-size:.8rem;padding:8px 14px;">Voir détails</a>
        </div>
      </div>
    </div>
  `).join('');
}

// ===== MES SERVICES =====
async function loadMesServices() {
  const result = await apiCall(API + '?action=get_mes_services');
  const grid = document.getElementById('mesServicesGrid');
  
  if (!result.success || !result.data.length) {
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px;"><div style="font-size:4rem;margin-bottom:16px;">⚙️</div><p style="color:var(--text-muted);">Vous n\'avez souscrit à aucun service.</p><a href="../services.php" class="btn-primary" style="display:inline-flex;margin-top:16px;">Voir les services</a></div>';
    return;
  }

  grid.innerHTML = result.data.map(s => `
    <div style="background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);padding:24px;">
      <div style="font-size:2.5rem;margin-bottom:12px;">⚙️</div>
      <h3 style="font-weight:700;font-size:.95rem;margin-bottom:6px;">${s.titre}</h3>
      <p style="font-size:.82rem;color:var(--text-secondary);margin-bottom:12px;">${s.description_courte||''}</p>
      <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:16px;">
        📅 Souscrit le ${formatDate(s.date_souscription)}<br>
        🔖 Réf: <span style="color:var(--accent)">${s.reference_paiement||'-'}</span>
      </div>
      <div style="display:flex;justify-content:space-between;align-items:center;">
        <span style="font-family:var(--font-display);font-size:1.1rem;font-weight:900;color:var(--accent)">${formatMontant(s.montant_paye||s.prix)}</span>
        ${s.statut_souscription==='confirme'?'<span class="badge badge-success">✅ Confirmé</span>':'<span class="badge badge-warning">⏳ En attente</span>'}
      </div>
    </div>
  `).join('');
}

// ===== EMPLOI DU TEMPS =====
async function loadEmploiTemps() {
  const result = await apiCall(API + '?action=get_emploi_temps');
  const container = document.getElementById('emploiTempsContainer');

  if (!result.success || !result.data.length) {
    container.innerHTML = '<div style="text-align:center;padding:60px;"><div style="font-size:4rem;margin-bottom:16px;">📅</div><p style="color:var(--text-muted);">Aucun cours planifié pour vos formations.</p></div>';
    return;
  }

  // Grouper par jour
  const jours = ['Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'];
  const byJour = {};
  result.data.forEach(e => {
    const j = e.jour || 'Non défini';
    if (!byJour[j]) byJour[j] = [];
    byJour[j].push(e);
  });

  container.innerHTML = jours.filter(j => byJour[j]).map(jour => `
    <div style="margin-bottom:24px;">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
        <div style="padding:6px 16px;background:var(--gradient-gold);border-radius:50px;font-size:.85rem;font-weight:700;color:var(--primary);">${jour}</div>
        <div style="flex:1;height:1px;background:var(--border);"></div>
      </div>
      <div class="schedule-grid">
        ${byJour[jour].map(e => `
          <div class="schedule-card">
            <div class="schedule-time">
              <div>${e.heure_debut?.slice(0,5)||'?'}</div>
              <div style="font-size:.7rem;margin-top:4px;opacity:.7">→</div>
              <div>${e.heure_fin?.slice(0,5)||'?'}</div>
            </div>
            <div class="schedule-info">
              <h4>${e.titre}</h4>
              <p>${e.formation_titre ? '📚 '+e.formation_titre : '📢 Cours général'}</p>
              <p style="margin-top:4px;">${e.salle?'🏫 '+e.salle:''} ${e.formateur?'👨‍🏫 '+e.formateur:''}</p>
              ${e.date_debut?`<p style="margin-top:4px;font-size:.75rem;color:var(--text-muted);">Du ${formatDate(e.date_debut)} au ${formatDate(e.date_fin)}</p>`:''}
            </div>
          </div>
        `).join('')}
      </div>
    </div>
  `).join('');
}

// ===== DOCUMENTS =====
async function loadDocuments() {
  const result = await apiCall(API + '?action=get_documents');
  const grid = document.getElementById('documentsStudentGrid');

  if (!result.success || !result.data.length) {
    grid.innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:60px;"><div style="font-size:4rem;margin-bottom:16px;">📁</div><p style="color:var(--text-muted);">Aucun document disponible pour le moment.</p></div>';
    return;
  }

  grid.innerHTML = result.data.map(d => `
    <div style="padding:24px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);transition:var(--transition);" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--border)'">
      <div style="font-size:2.5rem;margin-bottom:12px;">📄</div>
      <h4 style="font-weight:700;color:var(--text-primary);margin-bottom:4px;">${d.titre}</h4>
      <p style="font-size:.82rem;color:var(--text-secondary);margin-bottom:12px;">${d.description||'Document mis à disposition par l\'administration'}</p>
      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
        <span class="badge badge-info">${d.type_fichier||'PDF'}</span>
        ${d.formation_titre?`<span class="badge badge-gold">📚 ${d.formation_titre}</span>`:'<span class="badge badge-success">👥 Tous</span>'}
      </div>
      <div style="font-size:.75rem;color:var(--text-muted);margin-bottom:16px;">Mis en ligne le ${formatDate(d.created_at)}</div>
      <a href="#" class="btn-primary" style="width:100%;justify-content:center;padding:10px;">⬇️ Télécharger</a>
    </div>
  `).join('');
}

// ===== PAIEMENTS =====
async function loadPaiements() {
  const result = await apiCall(API + '?action=get_my_paiements');
  const tbody = document.getElementById('paiementsStudentTable');

  if (!result.success || !result.data.length) {
    tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">Aucun paiement enregistré</td></tr>';
    return;
  }

  const statusMap = {
    'confirme': '<span class="badge badge-success">✅ Confirmé</span>',
    'en_attente': '<span class="badge badge-warning">⏳ En attente</span>',
    'echoue': '<span class="badge badge-error">❌ Échoué</span>',
    'rembourse': '<span class="badge badge-info">↩️ Remboursé</span>',
  };

  tbody.innerHTML = result.data.map(p => `
    <tr>
      <td style="font-size:.78rem;font-family:monospace;color:var(--accent)">${p.reference_paiement||'-'}</td>
      <td><span class="badge ${p.type_item==='formation'?'badge-info':'badge-gold'}">${p.type_item}</span></td>
      <td style="font-weight:700;color:var(--accent)">${formatMontant(p.montant)}</td>
      <td style="font-size:.82rem">${p.methode_paiement}</td>
      <td>${statusMap[p.statut]||p.statut}</td>
      <td style="font-size:.8rem;color:var(--text-muted)">${formatDate(p.created_at)}</td>
    </tr>
  `).join('');
}

// ===== NOTIFICATIONS =====
async function loadNotifications() {
  const result = await apiCall(API + '?action=get_notifications');
  if (!result.success) return;

  const badge = document.getElementById('notifBadge');
  if (badge) {
    badge.textContent = result.unread_count;
    badge.style.display = result.unread_count > 0 ? 'flex' : 'none';
  }

  document.getElementById('myNotifCount').textContent = result.unread_count;

  const list = document.getElementById('notifList');
  if (!list) return;

  if (!result.data.length) {
    list.innerHTML = '<div style="padding:20px;text-align:center;color:var(--text-muted);font-size:.85rem;">Aucune notification</div>';
    return;
  }

  const typeMap = { info: 'ℹ️', success: '✅', warning: '⚠️', error: '❌' };
  list.innerHTML = result.data.map(n => `
    <div style="padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.05);${!n.lu?'background:rgba(212,160,23,.04)':''}">
      <div style="display:flex;gap:8px;align-items:flex-start;">
        <span>${typeMap[n.type]||'🔔'}</span>
        <div style="flex:1;">
          <div style="font-size:.85rem;font-weight:${!n.lu?'700':'500'};color:var(--text-primary);margin-bottom:2px;">${n.titre||'Notification'}</div>
          <div style="font-size:.8rem;color:var(--text-secondary)">${n.message}</div>
          <div style="font-size:.72rem;color:var(--text-muted);margin-top:4px;">${formatDate(n.created_at)}</div>
        </div>
        ${!n.lu?`<button onclick="markRead(${n.id})" style="background:none;border:none;color:var(--accent);cursor:pointer;font-size:.7rem;">Lire</button>`:''}
      </div>
    </div>
  `).join('');
}

function toggleNotifPanel() {
  const panel = document.getElementById('notifPanel');
  panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
  loadNotifications();
}

async function markRead(id) {
  await apiCall(API + '?action=mark_notification_read', 'POST', { id });
  loadNotifications();
}

async function markAllRead() {
  await apiCall(API + '?action=mark_notification_read', 'POST', { id: 0 });
  loadNotifications();
  toast('Toutes les notifications marquées comme lues', 'success');
}

// Profil
document.getElementById('profileForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  const result = await apiCall(API + '?action=update_profile', 'POST', data);
  toast(result.message, result.success ? 'success' : 'error');
});

// Fermer panel notif en cliquant ailleurs
document.addEventListener('click', function(e) {
  const panel = document.getElementById('notifPanel');
  const btn = document.getElementById('notifBtn');
  if (panel && !panel.contains(e.target) && !btn?.contains(e.target)) {
    panel.style.display = 'none';
  }
});

// Init
document.addEventListener('DOMContentLoaded', () => {
  loadTableauBord();
  loadNotifications();
});
