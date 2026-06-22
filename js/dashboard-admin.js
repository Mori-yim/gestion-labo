// =============================================
// DASHBOARD ADMIN - JavaScript
// =============================================

const API = '../php/api.php';

function showSection(name, link) {
  document.querySelectorAll('.page-section').forEach(s => s.classList.remove('active'));
  document.querySelectorAll('.sidebar-nav a').forEach(a => a.classList.remove('active'));
  document.getElementById('section-' + name)?.classList.add('active');
  if (link) link.classList.add('active');

  // Charger données à la demande
  const loaders = {
    'dashboard': loadDashboardStats,
    'utilisateurs': loadUsers,
    'formations_admin': loadFormationsAdmin,
    'services_admin': loadServicesAdmin,
    'emploi_temps': loadEmploiAdmin,
    'documents': loadDocumentsAdmin,
    'paiements_admin': loadPaiementsAdmin,
    'notifications_admin': loadNotifUsers,
  };
  if (loaders[name]) loaders[name]();
}

function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('mobile-open');
}

// ===== DASHBOARD STATS =====
async function loadDashboardStats() {
  const result = await apiCall(API + '?action=admin_get_stats');
  if (!result.success) return;
  const d = result.data;

  document.getElementById('statEtudiants').textContent = d.total_etudiants;
  document.getElementById('statFormations').textContent = d.total_formations;
  document.getElementById('statRevenus').textContent = new Intl.NumberFormat('fr-FR').format(d.revenus_totaux);
  document.getElementById('statAttente').textContent = d.paiements_en_attente;
  document.getElementById('statMessages').textContent = d.messages_non_lus;
  document.getElementById('statInscriptions').textContent = d.inscriptions_confirmees;

  // Derniers inscrits
  document.getElementById('lastUsersTable').innerHTML = d.derniers_inscrits.map(u => `
    <tr>
      <td><div style="font-weight:600;color:var(--text-primary)">${u.prenom} ${u.nom}</div></td>
      <td style="font-size:.83rem">${u.email}</td>
      <td style="font-size:.8rem;color:var(--text-muted)">${formatDate(u.created_at)}</td>
    </tr>
  `).join('') || '<tr><td colspan="3" style="text-align:center;color:var(--text-muted);">Aucun</td></tr>';

  // Derniers paiements
  document.getElementById('lastPaiementsTable').innerHTML = d.derniers_paiements.map(p => `
    <tr>
      <td>${p.prenom} ${p.nom}</td>
      <td style="color:var(--accent);font-weight:700">${formatMontant(p.montant)}</td>
      <td>${getStatusBadge(p.statut)}</td>
    </tr>
  `).join('') || '<tr><td colspan="3" style="text-align:center;color:var(--text-muted);">Aucun</td></tr>';
}

// ===== UTILISATEURS =====
async function loadUsers() {
  const role = document.getElementById('userRoleFilter')?.value || '';
  const result = await apiCall(API + '?action=admin_get_users&role=' + role);
  if (!result.success) return;

  document.getElementById('usersCount').textContent = result.data.length + ' utilisateur(s)';
  document.getElementById('usersTable').innerHTML = result.data.map(u => `
    <tr>
      <td>
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:36px;height:36px;background:var(--gradient-gold);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--primary);font-size:.85rem;">${(u.prenom[0]||'') + (u.nom[0]||'')}</div>
          <div><div style="font-weight:600;color:var(--text-primary)">${u.prenom} ${u.nom}</div><div style="font-size:.78rem;color:var(--text-muted)">${u.email}</div></div>
        </div>
      </td>
      <td>${u.phone || '-'}</td>
      <td><span class="badge ${u.role==='admin'?'badge-gold':'badge-info'}">${u.role==='admin'?'👑 Admin':'👤 Étudiant'}</span></td>
      <td>${getStatusBadge(u.statut)}</td>
      <td style="font-size:.8rem;color:var(--text-muted)">${formatDate(u.created_at)}</td>
      <td style="display:flex;gap:6px;">
        <button class="btn-success" onclick="toggleUserStatus(${u.id},'${u.statut}','${u.role}')" style="padding:6px 12px;font-size:.78rem;">${u.statut==='actif'?'🔒 Suspendre':'✅ Activer'}</button>
        ${u.role!=='admin'?`<button class="btn-danger" onclick="deleteUser(${u.id})" style="padding:6px 12px;font-size:.78rem;">🗑️</button>`:''}
      </td>
    </tr>
  `).join('') || '<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">Aucun utilisateur</td></tr>';
}

async function toggleUserStatus(id, currentStatus, role) {
  const newStatus = currentStatus === 'actif' ? 'suspendu' : 'actif';
  const result = await apiCall(API + '?action=admin_update_user', 'POST', { id, statut: newStatus, role });
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) loadUsers();
}

async function deleteUser(id) {
  if (!confirm('Supprimer cet utilisateur?')) return;
  const result = await apiCall(API + '?action=admin_delete_user', 'POST', { id });
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) loadUsers();
}

// ===== FORMATIONS =====
async function loadFormationsAdmin() {
  const result = await apiCall(API + '?action=get_formations');
  if (!result.success) return;

  // Populate emploi select
  const sel1 = document.getElementById('emploiFormationSelect');
  const sel2 = document.getElementById('docFormationSelect');
  [sel1, sel2].forEach(sel => {
    if (!sel) return;
    const current = sel.innerHTML.split('<option value="">')[0] + '<option value="">-- Générale (tous) --</option>';
    sel.innerHTML = '<option value="">-- Générale (tous) --</option>' + result.data.map(f => `<option value="${f.id}">${f.titre}</option>`).join('');
  });

  document.getElementById('formationsAdminTable').innerHTML = result.data.map(f => `
    <tr>
      <td><div style="font-weight:600;color:var(--text-primary)">${f.titre}</div></td>
      <td>${f.categorie || '-'}</td>
      <td style="color:var(--accent);font-weight:700">${formatMontant(f.prix)}</td>
      <td><span class="badge badge-info">${f.niveau}</span></td>
      <td>${getStatusBadge(f.statut)}</td>
      <td style="display:flex;gap:6px;">
        <button class="btn-success" onclick="toggleFormationStatus(${f.id},'${f.statut}','${f.titre.replace(/'/g,"\\'")}',${f.prix},'${f.description.replace(/'/g,"\\'")}');" style="padding:6px 12px;font-size:.78rem;">${f.statut==='actif'?'⏸️ Désactiver':'▶️ Activer'}</button>
        <button class="btn-danger" onclick="deleteFormation(${f.id})" style="padding:6px 12px;font-size:.78rem;">🗑️</button>
      </td>
    </tr>
  `).join('') || '<tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">Aucune formation</td></tr>';
}

async function toggleFormationStatus(id, status, titre, prix, desc) {
  const newStatus = status === 'actif' ? 'inactif' : 'actif';
  const result = await apiCall(API + '?action=admin_update_formation', 'POST', { id, statut: newStatus, titre, prix, description: desc });
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) loadFormationsAdmin();
}

async function deleteFormation(id) {
  if (!confirm('Supprimer cette formation?')) return;
  const result = await apiCall(API + '?action=admin_delete_formation', 'POST', { id });
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) loadFormationsAdmin();
}

document.getElementById('createFormationForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  const result = await apiCall(API + '?action=admin_create_formation', 'POST', data);
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) { closeModal('createFormationModal'); this.reset(); loadFormationsAdmin(); }
});

// ===== SERVICES =====
async function loadServicesAdmin() {
  const result = await apiCall(API + '?action=get_services');
  if (!result.success) return;

  document.getElementById('servicesAdminTable').innerHTML = result.data.map(s => `
    <tr>
      <td><div style="font-weight:600;color:var(--text-primary)">${s.titre}</div></td>
      <td>${s.categorie || '-'}</td>
      <td style="color:var(--accent);font-weight:700">${formatMontant(s.prix)}</td>
      <td>${getStatusBadge(s.statut)}</td>
      <td><button class="btn-danger" onclick="deleteService(${s.id})" style="padding:6px 12px;font-size:.78rem;">🗑️ Supprimer</button></td>
    </tr>
  `).join('') || '<tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">Aucun service</td></tr>';
}

async function deleteService(id) {
  if (!confirm('Supprimer ce service?')) return;
  const result = await apiCall(API + '?action=admin_delete_service', 'POST', { id });
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) loadServicesAdmin();
}

document.getElementById('createServiceForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  const result = await apiCall(API + '?action=admin_create_service', 'POST', data);
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) { closeModal('createServiceModal'); this.reset(); loadServicesAdmin(); }
});

// ===== EMPLOI DU TEMPS =====
async function loadEmploiAdmin() {
  await loadFormationsAdmin(); // populate selects
  const result = await apiCall(API + '?action=admin_get_emplois');
  if (!result.success) return;

  document.getElementById('emploiAdminTable').innerHTML = result.data.map(e => `
    <tr>
      <td style="font-weight:600;color:var(--text-primary)">${e.titre}</td>
      <td>${e.formation_titre || '<span style="color:var(--text-muted)">Général</span>'}</td>
      <td><span class="badge badge-info">${e.jour||'-'}</span></td>
      <td>${e.heure_debut?.slice(0,5)||'-'} → ${e.heure_fin?.slice(0,5)||'-'}</td>
      <td>${e.salle||'-'}</td>
      <td>${e.formateur||'-'}</td>
      <td><button class="btn-danger" onclick="deleteEmploi(${e.id})" style="padding:6px 12px;font-size:.78rem;">🗑️</button></td>
    </tr>
  `).join('') || '<tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">Aucun cours planifié</td></tr>';
}

async function deleteEmploi(id) {
  if (!confirm('Supprimer ce cours du planning?')) return;
  const result = await apiCall(API + '?action=admin_delete_emploi', 'POST', { id });
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) loadEmploiAdmin();
}

document.getElementById('createEmploiForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  const result = await apiCall(API + '?action=admin_create_emploi', 'POST', data);
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) { closeModal('createEmploiModal'); this.reset(); loadEmploiAdmin(); }
});

// ===== DOCUMENTS =====
async function loadDocumentsAdmin() {
  await loadFormationsAdmin();
  const result = await apiCall(API + '?action=get_documents');
  if (!result.success) return;

  const grid = document.getElementById('documentsGrid');
  grid.innerHTML = result.data.map(d => `
    <div style="padding:24px;background:var(--bg-card);border:1px solid var(--border);border-radius:var(--radius);">
      <div style="font-size:2.5rem;margin-bottom:12px;">📄</div>
      <h4 style="font-weight:700;color:var(--text-primary);margin-bottom:4px;">${d.titre}</h4>
      <p style="font-size:.82rem;color:var(--text-secondary);margin-bottom:12px;">${d.description||'Aucune description'}</p>
      <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px;">
        <span class="badge badge-info">${d.type_fichier||'PDF'}</span>
        <span class="badge ${d.acces==='tous'?'badge-success':'badge-warning'}">${d.acces==='tous'?'👥 Tous':'🎯 Spécifique'}</span>
        ${d.formation_titre?`<span class="badge badge-gold">📚 ${d.formation_titre}</span>`:''}
      </div>
      <div style="display:flex;gap:8px;">
        <a href="#" class="btn-success" style="flex:1;text-align:center;padding:8px;">⬇️ Voir</a>
        <button class="btn-danger" onclick="deleteDoc(${d.id})" style="padding:8px 14px;">🗑️</button>
      </div>
    </div>
  `).join('') || '<div style="grid-column:1/-1;text-align:center;padding:60px;color:var(--text-muted);">Aucun document en ligne</div>';
}

async function deleteDoc(id) {
  if (!confirm('Supprimer ce document?')) return;
  const result = await apiCall(API + '?action=admin_delete_document', 'POST', { id });
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) loadDocumentsAdmin();
}

document.getElementById('uploadDocForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  data.titre = data.titre;
  const result = await apiCall(API + '?action=admin_upload_document', 'POST', data);
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) { closeModal('uploadDocModal'); this.reset(); loadDocumentsAdmin(); }
});

// ===== PAIEMENTS =====
async function loadPaiementsAdmin() {
  const result = await apiCall(API + '?action=admin_get_paiements');
  if (!result.success) return;

  document.getElementById('paiementsAdminTable').innerHTML = result.data.map(p => `
    <tr>
      <td style="font-size:.78rem;font-family:monospace;color:var(--accent)">${p.reference_paiement||'-'}</td>
      <td>${p.prenom} ${p.nom}</td>
      <td><span class="badge ${p.type_item==='formation'?'badge-info':'badge-gold'}">${p.type_item}</span></td>
      <td style="font-weight:700;color:var(--accent)">${formatMontant(p.montant)}</td>
      <td style="font-size:.82rem">${p.methode_paiement}</td>
      <td>${getStatusBadge(p.statut)}</td>
      <td style="font-size:.78rem;color:var(--text-muted)">${formatDate(p.created_at)}</td>
      <td>
        ${p.statut==='en_attente'?`<button class="btn-success" onclick="updatePaiement(${p.id},'confirme')" style="padding:6px 10px;font-size:.75rem;">✅ Confirmer</button>`:''}
        ${p.statut==='confirme'?`<button class="btn-danger" onclick="updatePaiement(${p.id},'rembourse')" style="padding:6px 10px;font-size:.75rem;">↩️ Rembourser</button>`:''}
      </td>
    </tr>
  `).join('') || '<tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">Aucun paiement</td></tr>';
}

async function updatePaiement(id, statut) {
  const result = await apiCall(API + '?action=admin_update_paiement', 'POST', { id, statut });
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) loadPaiementsAdmin();
}

// ===== NOTIF ADMIN =====
async function loadNotifUsers() {
  const result = await apiCall(API + '?action=admin_get_users&role=etudiant');
  if (!result.success) return;
  const sel = document.getElementById('notifUserId');
  if (!sel) return;
  sel.innerHTML = '<option value="0">📢 Tous les étudiants</option>' + result.data.map(u => `<option value="${u.id}">${u.prenom} ${u.nom}</option>`).join('');
}

document.getElementById('notifAdminForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  const result = await apiCall(API + '?action=admin_send_notification', 'POST', data);
  toast(result.message, result.success ? 'success' : 'error');
  if (result.success) this.reset();
});

// ===== PROFIL =====
document.getElementById('profileForm')?.addEventListener('submit', async function(e) {
  e.preventDefault();
  const data = Object.fromEntries(new FormData(this));
  const result = await apiCall(API + '?action=update_profile', 'POST', data);
  toast(result.message, result.success ? 'success' : 'error');
});

// ===== UTILITAIRES =====
function getStatusBadge(status) {
  const map = {
    'actif': '<span class="badge badge-success">✅ Actif</span>',
    'inactif': '<span class="badge badge-error">⏸️ Inactif</span>',
    'suspendu': '<span class="badge badge-error">🔒 Suspendu</span>',
    'confirme': '<span class="badge badge-success">✅ Confirmé</span>',
    'en_attente': '<span class="badge badge-warning">⏳ En attente</span>',
    'echoue': '<span class="badge badge-error">❌ Échoué</span>',
    'rembourse': '<span class="badge badge-info">↩️ Remboursé</span>',
    'annule': '<span class="badge badge-error">❌ Annulé</span>',
  };
  return map[status] || `<span class="badge">${status}</span>`;
}

async function loadNotifications() {
  const result = await apiCall(API + '?action=get_notifications');
  if (result.success) {
    const badge = document.getElementById('notifBadge');
    if (badge) {
      badge.textContent = result.unread_count;
      badge.style.display = result.unread_count > 0 ? 'flex' : 'none';
    }
  }
}

// Init
document.addEventListener('DOMContentLoaded', () => {
  loadDashboardStats();
  loadNotifications();
});
