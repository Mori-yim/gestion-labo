-- =============================================
-- LABO FORMATION - Base de données MySQL
-- =============================================

CREATE DATABASE IF NOT EXISTS labo_formation CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE labo_formation;

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','etudiant') DEFAULT 'etudiant',
    phone VARCHAR(20),
    photo VARCHAR(255) DEFAULT 'default-avatar.png',
    adresse TEXT,
    date_naissance DATE,
    statut ENUM('actif','inactif','suspendu') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table des formations
CREATE TABLE IF NOT EXISTS formations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    description_courte VARCHAR(300),
    duree VARCHAR(50),
    prix DECIMAL(10,2) NOT NULL,
    niveau ENUM('Débutant','Intermédiaire','Avancé') DEFAULT 'Débutant',
    image VARCHAR(255) DEFAULT 'formation-default.jpg',
    categorie VARCHAR(100),
    places_disponibles INT DEFAULT 20,
    statut ENUM('actif','inactif') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des services
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    description_courte VARCHAR(300),
    prix DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT 'service-default.jpg',
    categorie VARCHAR(100),
    statut ENUM('actif','inactif') DEFAULT 'actif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des paiements
CREATE TABLE IF NOT EXISTS paiements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type_item ENUM('formation','service') NOT NULL,
    item_id INT NOT NULL,
    montant DECIMAL(10,2) NOT NULL,
    methode_paiement VARCHAR(100) DEFAULT 'Mobile Money',
    reference_paiement VARCHAR(100) UNIQUE,
    statut ENUM('en_attente','confirme','echoue','rembourse') DEFAULT 'en_attente',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des inscriptions aux formations
CREATE TABLE IF NOT EXISTS inscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    formation_id INT NOT NULL,
    paiement_id INT,
    statut ENUM('en_attente','confirme','annule') DEFAULT 'en_attente',
    date_inscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE CASCADE,
    FOREIGN KEY (paiement_id) REFERENCES paiements(id),
    UNIQUE KEY unique_inscription (user_id, formation_id)
);

-- Table des emplois du temps
CREATE TABLE IF NOT EXISTS emploi_du_temps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    formation_id INT,
    jour ENUM('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'),
    heure_debut TIME,
    heure_fin TIME,
    salle VARCHAR(100),
    formateur VARCHAR(150),
    description TEXT,
    date_debut DATE,
    date_fin DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE SET NULL
);

-- Table des documents
CREATE TABLE IF NOT EXISTS documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(200) NOT NULL,
    description TEXT,
    fichier VARCHAR(255) NOT NULL,
    type_fichier VARCHAR(50),
    taille_fichier INT,
    formation_id INT,
    uploaded_by INT NOT NULL,
    acces ENUM('tous','formation_specifique') DEFAULT 'tous',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (formation_id) REFERENCES formations(id) ON DELETE SET NULL,
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- Table des messages de contact
CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    sujet VARCHAR(200),
    message TEXT NOT NULL,
    statut ENUM('non_lu','lu','repondu') DEFAULT 'non_lu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des souscriptions aux services
CREATE TABLE IF NOT EXISTS souscriptions_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    paiement_id INT,
    statut ENUM('en_attente','confirme','annule') DEFAULT 'en_attente',
    date_souscription TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Table notifications
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    titre VARCHAR(200),
    message TEXT NOT NULL,
    type ENUM('info','success','warning','error') DEFAULT 'info',
    lu TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- =============================================
-- DONNÉES DE DÉMONSTRATION
-- =============================================

-- Admin par défaut (mot de passe: Admin@2024)
INSERT INTO users (nom, prenom, email, password, role, phone, statut) VALUES
('Admin', 'Super', 'admin@laboformation.cm', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '+237 699 000 001', 'actif'),
('Kamga', 'Jean', 'jean.kamga@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', '+237 677 111 222', 'actif'),
('Nkomo', 'Marie', 'marie.nkomo@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', '+237 655 333 444', 'actif'),
('Biya', 'Paul Jr', 'pauljr@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'etudiant', '+237 688 555 666', 'actif');

-- Formations
INSERT INTO formations (titre, description, description_courte, duree, prix, niveau, categorie, places_disponibles) VALUES
('Développement Web Full Stack', 'Formation complète en développement web couvrant HTML, CSS, JavaScript, PHP, MySQL et les frameworks modernes. Apprenez à créer des applications web professionnelles de A à Z avec des projets pratiques réels.', 'Maîtrisez le développement web de A à Z avec HTML, CSS, JS, PHP et MySQL.', '6 mois', 150000, 'Débutant', 'Informatique', 15),
('Intelligence Artificielle & Machine Learning', 'Plongez dans le monde fascinant de l\'IA et du Machine Learning. Formation intensive couvrant Python, TensorFlow, scikit-learn, deep learning, traitement du langage naturel et vision par ordinateur.', 'Explorez Python, TensorFlow et les algorithmes d\'IA modernes.', '4 mois', 200000, 'Intermédiaire', 'IA & Data', 12),
('Cybersécurité & Ethical Hacking', 'Formation avancée en cybersécurité couvrant les techniques de hacking éthique, la sécurité des réseaux, la cryptographie, les tests d\'intrusion et la protection des systèmes informatiques.', 'Protégez les systèmes avec les techniques de cybersécurité avancées.', '3 mois', 180000, 'Avancé', 'Sécurité', 10),
('Design Graphique & UI/UX', 'Formation en design graphique et conception d\'interfaces utilisateur. Maîtrisez Photoshop, Illustrator, Figma et les principes du design UX pour créer des interfaces belles et fonctionnelles.', 'Créez des designs percutants avec Figma, Photoshop et Illustrator.', '3 mois', 120000, 'Débutant', 'Design', 18),
('Développement Mobile (Android & iOS)', 'Apprenez à créer des applications mobiles natives et hybrides pour Android et iOS avec React Native, Flutter et les APIs natives. Publiez vos apps sur les stores.', 'Créez des apps mobiles pour Android et iOS avec React Native et Flutter.', '5 mois', 175000, 'Intermédiaire', 'Mobile', 12),
('Comptabilité Informatisée & ERP', 'Formation complète en comptabilité informatisée couvrant SAGE Comptabilité, les ERPs d\'entreprise, la gestion financière numérique et les tableaux de bord financiers.', 'Maîtrisez SAGE, les ERPs et la gestion financière digitale.', '2 mois', 95000, 'Débutant', 'Gestion', 20);

-- Services
INSERT INTO services (titre, description, description_courte, prix, categorie) VALUES
('Développement de Site Web', 'Création de sites web professionnels sur mesure pour votre entreprise ou organisation. Sites vitrines, e-commerce, portfolios avec des technologies modernes et un design responsive.', 'Sites web professionnels sur mesure pour votre activité.', 250000, 'Développement'),
('Application Mobile Sur Mesure', 'Développement d\'applications mobiles Android et iOS personnalisées selon vos besoins. Design soigné, performances optimales et maintenance incluse.', 'Apps mobiles Android et iOS développées sur mesure.', 400000, 'Développement'),
('Formation Entreprise', 'Sessions de formation informatique personnalisées pour vos employés directement dans vos locaux. Programmes adaptés à vos besoins spécifiques et secteur d\'activité.', 'Formez vos équipes avec des sessions personnalisées en entreprise.', 150000, 'Formation'),
('Maintenance Informatique', 'Service de maintenance et support technique pour votre parc informatique. Dépannage, mises à jour, sécurité et optimisation de vos systèmes informatiques.', 'Support et maintenance de votre parc informatique professionnel.', 50000, 'Support'),
('Consultation & Audit IT', 'Audit complet de votre infrastructure informatique avec recommandations stratégiques. Accompagnement dans votre transformation numérique.', 'Audit et conseil pour optimiser votre infrastructure IT.', 100000, 'Conseil'),
('Design & Identité Visuelle', 'Création de logos, chartes graphiques, supports de communication et identité visuelle complète pour votre marque. Design professionnel et mémorable.', 'Logos, chartes graphiques et identité visuelle professionnelle.', 75000, 'Design');

-- Emploi du temps
INSERT INTO emploi_du_temps (titre, formation_id, jour, heure_debut, heure_fin, salle, formateur, date_debut, date_fin) VALUES
('HTML/CSS Fondamentaux', 1, 'Lundi', '08:00:00', '12:00:00', 'Salle A01', 'M. Tchouanga Eric', '2024-02-05', '2024-03-15'),
('JavaScript Avancé', 1, 'Mercredi', '14:00:00', '18:00:00', 'Salle A01', 'Mme Fouda Sandra', '2024-02-07', '2024-03-20'),
('Python & Machine Learning', 2, 'Mardi', '09:00:00', '13:00:00', 'Salle B02', 'Dr. Mbarga Claude', '2024-02-06', '2024-04-30'),
('Sécurité des Réseaux', 3, 'Jeudi', '15:00:00', '19:00:00', 'Salle C03', 'M. Atangana Pierre', '2024-02-08', '2024-04-15'),
('Figma & Prototypage', 4, 'Vendredi', '08:00:00', '12:00:00', 'Salle Design', 'Mme Njoya Isabelle', '2024-02-09', '2024-04-20'),
('React Native Mobile', 5, 'Samedi', '09:00:00', '17:00:00', 'Salle A02', 'M. Ndjike Franck', '2024-02-10', '2024-05-25');

-- Paiements
INSERT INTO paiements (user_id, type_item, item_id, montant, methode_paiement, reference_paiement, statut) VALUES
(2, 'formation', 1, 150000, 'MTN Mobile Money', 'REF-2024-001', 'confirme'),
(2, 'formation', 4, 120000, 'Orange Money', 'REF-2024-002', 'confirme'),
(3, 'formation', 2, 200000, 'MTN Mobile Money', 'REF-2024-003', 'confirme'),
(3, 'service', 1, 250000, 'Virement Bancaire', 'REF-2024-004', 'confirme'),
(4, 'formation', 3, 180000, 'Orange Money', 'REF-2024-005', 'en_attente');

-- Inscriptions
INSERT INTO inscriptions (user_id, formation_id, paiement_id, statut) VALUES
(2, 1, 1, 'confirme'),
(2, 4, 2, 'confirme'),
(3, 2, 3, 'confirme'),
(4, 3, 5, 'en_attente');

-- Documents
INSERT INTO documents (titre, description, fichier, type_fichier, formation_id, uploaded_by, acces) VALUES
('Support de cours HTML/CSS', 'Support complet du cours HTML et CSS avec exercices pratiques', 'support-html-css.pdf', 'PDF', 1, 1, 'formation_specifique'),
('TP JavaScript - Exercices', 'Travaux pratiques JavaScript niveau débutant à intermédiaire', 'tp-javascript.pdf', 'PDF', 1, 1, 'formation_specifique'),
('Guide Python ML', 'Guide d\'initiation au Machine Learning avec Python et scikit-learn', 'guide-python-ml.pdf', 'PDF', 2, 1, 'formation_specifique'),
('Règlement Intérieur du Centre', 'Règlement intérieur et code de conduite du centre de formation', 'reglement-interieur.pdf', 'PDF', NULL, 1, 'tous'),
('Planning Général 2024', 'Planning général des cours et événements pour l\'année 2024', 'planning-2024.pdf', 'PDF', NULL, 1, 'tous');

-- Notifications
INSERT INTO notifications (user_id, titre, message, type) VALUES
(2, 'Inscription confirmée', 'Votre inscription à la formation Développement Web Full Stack a été confirmée.', 'success'),
(2, 'Nouveau document disponible', 'Un nouveau support de cours a été mis en ligne pour votre formation.', 'info'),
(3, 'Paiement reçu', 'Votre paiement de 200,000 FCFA a été reçu et validé.', 'success'),
(4, 'Paiement en attente', 'Votre paiement est en cours de vérification. Vous serez notifié sous 24h.', 'warning');
