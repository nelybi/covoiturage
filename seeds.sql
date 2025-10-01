-- ==========================================
-- Script d'insertion des données initiales
-- ==========================================

USE covoiturage;

-- ====================
-- Agences
-- ====================
INSERT INTO agence (nom) VALUES
('Paris'),
('Lyon'),
('Marseille'),
('Toulouse'),
('Nice'),
('Nantes'),
('Strasbourg'),
('Montpellier'),
('Bordeaux'),
('Lille'),
('Rennes'),
('Reims');

-- ====================
-- Utilisateurs
-- ====================
INSERT INTO user (nom, prenom, telephone, email, role, password) VALUES
('Martin', 'Alexandre', '0612345678', 'alexandre.martin@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Dubois', 'Sophie', '0698765432', 'sophie.dubois@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Bernard', 'Julien', '0622446688', 'julien.bernard@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Moreau', 'Camille', '0611223344', 'camille.moreau@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Lefèvre', 'Lucie', '0777889900', 'lucie.lefevre@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Leroy', 'Thomas', '0655443322', 'thomas.leroy@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Roux', 'Chloé', '0633221199', 'chloe.roux@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Petit', 'Maxime', '0766778899', 'maxime.petit@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Garnier', 'Laura', '0688776655', 'laura.garnier@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Dupuis', 'Antoine', '0744556677', 'antoine.dupuis@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Lefebvre', 'Emma', '0699887766', 'emma.lefebvre@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Fontaine', 'Louis', '0655667788', 'louis.fontaine@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Chevalier', 'Clara', '0788990011', 'clara.chevalier@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Robin', 'Nicolas', '0644332211', 'nicolas.robin@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Gauthier', 'Marine', '0677889922', 'marine.gauthier@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Fournier', 'Pierre', '0722334455', 'pierre.fournier@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Girard', 'Sarah', '0688665544', 'sarah.girard@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Lambert', 'Hugo', '0611223366', 'hugo.lambert@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Masson', 'Julie', '0733445566', 'julie.masson@email.fr', 'utilisateur', '$2y$10$yY1h3yM08ozSh1sFfP9O6OJQOGzwYY3p6H7y3k5HZzZvn0Z5Z1RMS'),
('Henry', 'Arthur', '0666554433', 'arthur.henry@email.fr', 'admin', '$2y$10$Qp3EStK8yzkI2lwe/OtMyu95ubG1uK5YfOD7Q0htWZpI/Whs1B7hG');
