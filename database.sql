-- ==========================================
-- Script de création de la base de données
-- Projet : Application de covoiturage interne
-- Auteur : Nabil
-- ==========================================

-- Création de la base
CREATE DATABASE IF NOT EXISTS covoiturage
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE covoiturage;

-- ====================
-- Table USER
-- ====================
CREATE TABLE user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telephone VARCHAR(20) NOT NULL,
    role ENUM('utilisateur', 'admin') NOT NULL DEFAULT 'utilisateur'
);

-- ====================
-- Table AGENCE
-- ====================
CREATE TABLE agence (
    id_agence INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
);

-- ====================
-- Table TRAJET
-- ====================
CREATE TABLE trajet (
    id_trajet INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    id_agence_depart INT NOT NULL,
    id_agence_arrivee INT NOT NULL,
    date_depart DATETIME NOT NULL,
    date_arrivee DATETIME NOT NULL,
    nb_places_total INT NOT NULL,
    nb_places_dispo INT NOT NULL,
    CONSTRAINT fk_user FOREIGN KEY (id_user) REFERENCES user(id_user) ON DELETE CASCADE,
    CONSTRAINT fk_agence_depart FOREIGN KEY (id_agence_depart) REFERENCES agence(id_agence),
    CONSTRAINT fk_agence_arrivee FOREIGN KEY (id_agence_arrivee) REFERENCES agence(id_agence),
    CONSTRAINT chk_places CHECK (nb_places_total >= nb_places_dispo),
    CONSTRAINT chk_agences CHECK (id_agence_depart <> id_agence_arrivee)
);

ALTER TABLE user 
ADD COLUMN password VARCHAR(255) NOT NULL AFTER role;