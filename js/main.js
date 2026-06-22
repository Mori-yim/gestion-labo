// =============================================
// LABO FORMATION - JAVASCRIPT PRINCIPAL
// =============================================

const BASE_URL = 'php';

// ===== UTILITAIRES =====
function toast(message, type = 'info', duration = 4000) {
  let container = document.querySelector('.toast-container');
  if (!container) {
    container = document.createElement('div');
    container.className = 'toast-container';
    document.body.appendChild(container);
  }

  const icons = { success: '✅', error: '❌', warning: '⚠️', info: 'ℹ️' };
  const t = document.createElement('div');
  t.className = `toast ${type}`;
  t.innerHTML = `<span>${icons[type]}</span><span>${message}</span>`;
  container.appendChild(t);

  setTimeout(() => {
    t.style.opacity = '0';
    t.style.transform = 'translateX(120%)';
    t.style.transition = 'all 0.3s ease';
    setTimeout(() => t.remove(), 300);
  }, duration);
}

async function apiCall(endpoint, method = 'GET', data = null) {
  const opts = { method, headers: {} };
  
  if (data) {
    if (data instanceof FormData) {
      opts.body = data;
    } else {
      opts.headers['Content-Type'] = 'application/x-www-form-urlencoded';
      opts.body = new URLSearchParams(data).toString();
    }
  }
  
  try {
    const response = await fetch(`${BASE_URL}/${endpoint}`, opts);
    const json = await response.json();
    return json;
  } catch (e) {
    console.error('API Error:', e);
    return { success: false, message: 'Erreur de connexion au serveur.' };
  }
}

function formatMontant(amount) {
  return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
}

function formatDate(dateStr) {
  if (!dateStr) return '-';
  return new Date(dateStr).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' });
}

function getInitials(nom, prenom) {
  return (prenom?.charAt(0) || '') + (nom?.charAt(0) || '');
}

// ===== NAVBAR =====
function initNavbar() {
  const navbar = document.querySelector('.navbar');
  const hamburger = document.querySelector('.hamburger');
  
  if (!navbar) return;

  window.addEventListener('scroll', () => {
    navbar.classList.toggle('scrolled', window.scrollY > 50);
  });

  if (hamburger) {
    hamburger.addEventListener('click', () => {
      navbar.classList.toggle('nav-mobile-open');
    });
  }

  // Active link highlighting
  const currentPage = window.location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('.nav-links a').forEach(link => {
    if (link.getAttribute('href') === currentPage) {
      link.classList.add('active');
    }
  });
}

// ===== LOADING SCREEN =====
function hideLoading() {
  const loading = document.querySelector('.loading-screen');
  if (loading) {
    loading.style.opacity = '0';
    setTimeout(() => loading.remove(), 500);
  }
}

// ===== CAROUSEL =====
function initCarousel() {
  const track = document.querySelector('.carousel-track');
  if (!track) return;

  const slides = track.querySelectorAll('.carousel-slide');
  const dots = document.querySelectorAll('.dot');
  let current = 0;
  let autoplay;

  function goTo(index) {
    current = (index + slides.length) % slides.length;
    track.style.transform = `translateX(-${current * 100}%)`;
    dots.forEach((d, i) => d.classList.toggle('active', i === current));
  }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }

  document.querySelector('.carousel-btn.next')?.addEventListener('click', () => { next(); resetAutoplay(); });
  document.querySelector('.carousel-btn.prev')?.addEventListener('click', () => { prev(); resetAutoplay(); });
  dots.forEach((d, i) => d.addEventListener('click', () => { goTo(i); resetAutoplay(); }));

  function startAutoplay() {
    autoplay = setInterval(next, 4500);
  }

  function resetAutoplay() {
    clearInterval(autoplay);
    startAutoplay();
  }

  goTo(0);
  startAutoplay();
}

// ===== PARTICLES HERO =====
function initParticles() {
  const container = document.querySelector('.hero-particles');
  if (!container) return;

  for (let i = 0; i < 20; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    p.style.cssText = `
      left: ${Math.random() * 100}%;
      animation-delay: ${Math.random() * 8}s;
      animation-duration: ${6 + Math.random() * 6}s;
      width: ${2 + Math.random() * 4}px;
      height: ${2 + Math.random() * 4}px;
      opacity: ${0.3 + Math.random() * 0.7};
    `;
    container.appendChild(p);
  }
}

// ===== COUNTER ANIMATION =====
function animateCounters() {
  const counters = document.querySelectorAll('[data-count]');
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const el = entry.target;
        const target = parseInt(el.dataset.count);
        const suffix = el.dataset.suffix || '';
        let start = 0;
        const duration = 2000;
        const step = target / (duration / 16);

        const timer = setInterval(() => {
          start += step;
          if (start >= target) {
            start = target;
            clearInterval(timer);
          }
          el.textContent = Math.floor(start).toLocaleString('fr-FR') + suffix;
        }, 16);

        observer.unobserve(el);
      }
    });
  }, { threshold: 0.5 });

  counters.forEach(c => observer.observe(c));
}

// ===== SCROLL ANIMATIONS =====
function initScrollAnimations() {
  const elements = document.querySelectorAll('.card, .feature-card, .testimonial-card, .section-header');
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animation = 'fade-up 0.6s ease forwards';
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  elements.forEach(el => {
    el.style.opacity = '0';
    observer.observe(el);
  });
}

// ===== MODAL =====
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
  }
}

// Fermer modal en cliquant sur l'overlay
document.addEventListener('click', (e) => {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('active');
    document.body.style.overflow = '';
  }
  if (e.target.classList.contains('modal-close')) {
    const overlay = e.target.closest('.modal-overlay');
    if (overlay) {
      overlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  }
});

// ===== AUTH MODAL =====
function initAuthModal() {
  const loginBtn = document.getElementById('loginBtn');
  if (loginBtn) {
    loginBtn.addEventListener('click', () => openModal('authModal'));
  }

  // Switch tabs
  document.querySelectorAll('.auth-tab').forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.tab;
      document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
      document.querySelectorAll('.auth-panel').forEach(p => p.style.display = 'none');
      const panel = document.getElementById(target + 'Panel');
      if (panel) panel.style.display = 'block';
    });
  });

  // Login form
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = loginForm.querySelector('button[type="submit"]');
      btn.disabled = true;
      btn.textContent = 'Connexion...';

      const result = await apiCall('auth.php', 'POST', {
        action: 'login',
        email: loginForm.querySelector('[name="email"]').value,
        password: loginForm.querySelector('[name="password"]').value
      });

      if (result.success) {
        toast(result.message, 'success');
        setTimeout(() => window.location.href = result.redirect, 1000);
      } else {
        toast(result.message, 'error');
        btn.disabled = false;
        btn.textContent = 'Se connecter';
      }
    });
  }

  // Register form
  const registerForm = document.getElementById('registerForm');
  if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = registerForm.querySelector('button[type="submit"]');
      btn.disabled = true;

      const result = await apiCall('auth.php', 'POST', {
        action: 'register',
        nom: registerForm.querySelector('[name="nom"]').value,
        prenom: registerForm.querySelector('[name="prenom"]').value,
        email: registerForm.querySelector('[name="email"]').value,
        password: registerForm.querySelector('[name="password"]').value,
        phone: registerForm.querySelector('[name="phone"]')?.value || ''
      });

      if (result.success) {
        toast(result.message, 'success');
        // Switch to login tab
        document.querySelector('[data-tab="login"]')?.click();
      } else {
        toast(result.message, 'error');
      }
      
      btn.disabled = false;
    });
  }

  // Forgot password
  const forgotForm = document.getElementById('forgotForm');
  if (forgotForm) {
    forgotForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const result = await apiCall('auth.php', 'POST', {
        action: 'forgot_password',
        email: forgotForm.querySelector('[name="email"]').value
      });
      toast(result.message, result.success ? 'success' : 'error');
    });
  }
}

// ===== LOAD FORMATIONS (PUBLIC) =====
async function loadPublicFormations() {
  const container = document.getElementById('formationsGrid');
  if (!container) return;

  container.innerHTML = '<div style="text-align:center;padding:40px;color:var(--text-muted);">Chargement...</div>';

  const result = await apiCall('api.php?action=get_formations');
  
  if (result.success) {
    const icons = ['💻', '🤖', '🔐', '🎨', '📱', '💼', '🌐', '📊'];
    container.innerHTML = result.data.map((f, i) => `
      <div class="card">
        <div class="card-image" style="background:linear-gradient(135deg,${getGradient(i)})">
          <span style="font-size:4rem;filter:drop-shadow(0 0 20px rgba(212,160,23,0.3))">${icons[i % icons.length]}</span>
          <div class="card-image-overlay"></div>
          <span class="card-badge">${f.niveau}</span>
        </div>
        <div class="card-body">
          <div class="card-category">${f.categorie || 'Formation'}</div>
          <h3 class="card-title">${f.titre}</h3>
          <p class="card-desc">${f.description_courte || f.description}</p>
          <div class="card-meta">
            ${f.duree ? `<span class="meta-item"><span>⏱️</span>${f.duree}</span>` : ''}
            <span class="meta-item"><span>👥</span>${f.places_disponibles} places</span>
            <span class="meta-item"><span>📊</span>${f.niveau}</span>
          </div>
          <div class="card-footer">
            <div>
              <div class="card-price">${formatMontant(f.prix)}</div>
              <span class="card-price-sub">Formation complète</span>
            </div>
            <button class="btn-card" onclick="handleSouscrire('formation', ${f.id}, '${f.titre}', ${f.prix})">
              Souscrire →
            </button>
          </div>
        </div>
      </div>
    `).join('');
  }
}

// ===== LOAD SERVICES (PUBLIC) =====
async function loadPublicServices() {
  const container = document.getElementById('servicesGrid');
  if (!container) return;

  container.innerHTML = '<div style="text-align:center;padding:40px;color:var(--text-muted);">Chargement...</div>';

  const result = await apiCall('api.php?action=get_services');
  
  if (result.success) {
    const icons = ['🌐', '📱', '🎓', '🔧', '💡', '🎨'];
    container.innerHTML = result.data.map((s, i) => `
      <div class="card">
        <div class="card-image" style="background:linear-gradient(135deg,${getGradient(i + 2)})">
          <span style="font-size:4rem">${icons[i % icons.length]}</span>
          <div class="card-image-overlay"></div>
          <span class="card-badge">${s.categorie || 'Service'}</span>
        </div>
        <div class="card-body">
          <div class="card-category">${s.categorie || 'Service'}</div>
          <h3 class="card-title">${s.titre}</h3>
          <p class="card-desc">${s.description_courte || s.description}</p>
          <div class="card-footer">
            <div>
              <div class="card-price">${formatMontant(s.prix)}</div>
              <span class="card-price-sub">Service professionnel</span>
            </div>
            <button class="btn-card" onclick="handleSouscrire('service', ${s.id}, '${s.titre}', ${s.prix})">
              Obtenir →
            </button>
          </div>
        </div>
      </div>
    `).join('');
  }
}

function getGradient(index) {
  const gradients = [
    '#0a1628, #1a3a6b',
    '#1a3a6b, #0a2850',
    '#0d2040, #1a4080',
    '#102030, #1a5060',
    '#0a1628, #203060',
    '#152030, #1a3a50'
  ];
  return gradients[index % gradients.length];
}

// ===== HANDLE SOUSCRIRE =====
function handleSouscrire(type, id, titre, prix) {
  // Check if logged in (via session check)
  const isLoggedIn = document.body.dataset.loggedIn === 'true';
  
  if (!isLoggedIn) {
    toast('Veuillez vous connecter pour souscrire.', 'warning');
    openModal('authModal');
    return;
  }

  // Open payment modal
  document.getElementById('paymentItemType').value = type;
  document.getElementById('paymentItemId').value = id;
  document.getElementById('paymentItemTitle').textContent = titre;
  document.getElementById('paymentItemPrice').textContent = formatMontant(prix);
  openModal('paymentModal');
}

// ===== LOAD PUBLIC STATS =====
async function loadPublicStats() {
  const result = await apiCall('api.php?action=get_stats');
  if (result.success) {
    const d = result.data;
    document.querySelectorAll('[data-stat]').forEach(el => {
      const key = el.dataset.stat;
      if (d[key] !== undefined) {
        el.dataset.count = d[key];
      }
    });
    animateCounters();
  }
}

// ===== CONTACT FORM =====
function initContactForm() {
  const form = document.getElementById('contactForm');
  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Envoi en cours...';

    const result = await apiCall('api.php?action=contact_submit', 'POST', {
      nom: form.querySelector('[name="nom"]').value,
      email: form.querySelector('[name="email"]').value,
      sujet: form.querySelector('[name="sujet"]')?.value || '',
      message: form.querySelector('[name="message"]').value
    });

    toast(result.message, result.success ? 'success' : 'error');
    if (result.success) form.reset();
    btn.disabled = false;
    btn.textContent = 'Envoyer le message';
  });
}

// ===== INITIALISATION =====
document.addEventListener('DOMContentLoaded', () => {
  // Cacher loading screen
  setTimeout(hideLoading, 800);

  initNavbar();
  initParticles();
  initCarousel();
  initScrollAnimations();
  initAuthModal();
  initContactForm();

  // Charger stats
  loadPublicStats();

  // Charger formations/services si présents
  loadPublicFormations();
  loadPublicServices();
});
