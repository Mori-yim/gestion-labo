#  Labo Academie - Centre d'Excellence Numérique
## Guide d'Installation Complet

---

## 📁 Structure du Projet

```
labo-formation/
├── index.php              ← Page d'accueil principale
├── about.php              ← Page À Propos
├── formations.php         ← Page Formations
├── services.php           ← Page Services
├── paiement.php           ← Page Paiement
├── contact.php            ← Page Contact
├── login.php              ← Page Connexion (alternative)
│
├── admin/
│   └── dashboard.php      ← Dashboard Administrateur
│
├── student/
│   └── dashboard.php      ← Dashboard Étudiant
│
├── php/
│   ├── config.php         ← Configuration BDD & utilitaires
│   ├── auth.php           ← Authentification (login/register/logout)
│   └── api.php            ← API REST principale
│
├── css/
│   ├── style.css          ← Styles principaux
│   └── dashboard.css      ← Styles dashboard
│
├── js/
│   ├── main.js            ← JavaScript principal
│   ├── dashboard-admin.js ← JS Dashboard Admin
│   └── dashboard-student.js ← JS Dashboard Étudiant
│
├── includes/
│   ├── footer.php         ← Footer réutilisable
│   └── auth_modal.php     ← Modal d'authentification
│
└── database.sql           ← Script BDD complet
```

---

## ⚙️ Installation

### Prérequis
- PHP 7.4+ (PHP 8.x recommandé)
- MySQL 5.7+ ou MariaDB 10.3+
- Serveur web Apache ou Nginx (XAMPP, WAMP, Laragon)

### Étapes

**1. Placer les fichiers**
```
Copier le dossier `labo-formation/` dans :
- XAMPP: C:/xampp/htdocs/labo-formation/
- WAMP:  C:/wamp64/www/labo-formation/
- Linux: /var/www/html/labo-formation/
```

**2. Créer la base de données**
```sql
-- Via phpMyAdmin OU ligne de commande:
mysql -u root -p < database.sql
```
Ou ouvrir phpMyAdmin → Importer → Sélectionner `database.sql`

**3. Configurer la connexion BDD**
Éditer `php/config.php`:
```php
define('DB_HOST', 'localhost');    // Hôte BDD
define('DB_USER', 'root');         // Utilisateur BDD
define('DB_PASS', '');             // Mot de passe BDD
define('DB_NAME', 'labo_formation'); // Nom de la BDD
define('BASE_URL', 'http://localhost/labo-formation'); // URL de base
```

**4. Accéder à l'application**
```
http://localhost/labo-formation/
```

---

##  Comptes de Démonstration

| Rôle | Email | Mot de passe |
|------|-------|--------------|
|  Admin | admin@laboformation.cm | password |
|  Étudiant 1 | jean.kamga@email.com | password |
|  Étudiant 2 | marie.nkomo@email.com | password |
|  Étudiant 3 | pauljr@email.com | password |

> ⚠️ Changer les mots de passe en production!

---

## 🏗️ Architecture Technique

### Frontend
- **HTML5** sémantique et accessible
- **CSS3** avec variables personnalisées, Flexbox, Grid, animations
- **JavaScript ES6+** vanilla (aucune dépendance externe)
- Design **Responsive** Mobile-first
- Polices Google: Playfair Display + DM Sans

### Backend
- **PHP 7.4+** orienté PDO/PDO Prepared Statements
- Architecture **API REST** (JSON)
- **Sessions PHP** sécurisées pour l'authentification
- Mots de passe hashés avec **bcrypt** (password_hash)
- Protection **XSS** et **SQL Injection**

### Base de Données
- **MySQL/MariaDB**
- 11 tables relationnelles
- Contraintes FK, index, unicité

---

##  Fonctionnalités par Espace

###  Public (sans connexion)
- Page d'accueil avec carousel, stats animés, témoignages
- Liste des formations et services
- Pages À Propos, Paiement, Contact
- Modal d'inscription/connexion

### 👑 Espace Administrateur
- **Dashboard** avec stats en temps réel
- **Gestion Utilisateurs** (CRUD, activation/suspension)
- **Gestion Formations** (créer, modifier, supprimer)
- **Gestion Services** (CRUD)
- **Emploi du Temps** (planification des cours)
- **Documents** (mise en ligne de supports)
- **Paiements** (suivi et validation)
- **Notifications** (envoi ciblé ou global)

### 🎓 Espace Étudiant
- **Tableau de bord** personnalisé
- **Mes Formations** (formations souscrites)
- **Mes Services** (services souscrits)
- **Emploi du Temps** (planning des cours)
- **Documents** (supports disponibles)
- **Mes Paiements** (historique)
- **Profil** (gestion du compte)

---

## 🔒 Sécurité Implémentée

-  Mots de passe hashés bcrypt
-  Sessions PHP sécurisées
-  Requêtes préparées PDO (anti SQL injection)
-  Sanitisation des entrées (htmlspecialchars)
-  Vérification des rôles à chaque requête
-  Protection CSRF (à renforcer en production)

### Pour la production, ajouter:
```php
// Dans php/config.php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);   // HTTPS seulement
ini_set('session.cookie_samesite', 'Strict');
```

---

##  Support

**Centre LaboFormation**
- 📍 Carrefour IPA, Ange Raphael, Douala, Cameroun
- 📧 laboacademy@gmail.cm
- 📞 +237 6 99 00 00 01

---

*Application développée avec PHP, MySQL, HTML/CSS/JavaScript*
*© 2026 Labo Academy - Tous droits réservés*
