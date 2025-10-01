-- ==========================================
-- Script d'insertion des trajets de test
-- ==========================================

USE covoiturage;

INSERT INTO trajet (
    id_user, 
    id_agence_depart, 
    id_agence_arrivee, 
    date_depart, 
    date_arrivee, 
    nb_places_total, 
    nb_places_dispo
) VALUES
(1, 1, 2, '2025-10-05 09:00:00', '2025-10-05 13:00:00', 4, 3),   -- Alexandre Martin : Paris -> Lyon
(2, 3, 4, '2025-10-06 08:30:00', '2025-10-06 12:00:00', 3, 1),   -- Sophie Dubois : Marseille -> Toulouse
(3, 10, 9, '2025-10-07 14:00:00', '2025-10-07 18:00:00', 5, 4),  -- Julien Bernard : Lille -> Bordeaux
(4, 6, 5, '2025-10-08 07:00:00', '2025-10-08 11:30:00', 2, 2),   -- Camille Moreau : Nantes -> Nice
(5, 8, 7, '2025-10-09 10:00:00', '2025-10-09 14:00:00', 4, 2),   -- Lucie Lefèvre : Montpellier -> Strasbourg
(6, 11, 12, '2025-10-10 09:30:00', '2025-10-10 13:30:00', 3, 2), -- Thomas Leroy : Rennes -> Reims
(7, 2, 9, '2025-10-11 08:00:00', '2025-10-11 12:30:00', 4, 1),   -- Chloé Roux : Lyon -> Bordeaux
(8, 5, 1, '2025-10-12 15:00:00', '2025-10-12 19:30:00', 5, 5),   -- Maxime Petit : Nice -> Paris
(9, 4, 3, '2025-10-13 09:00:00', '2025-10-13 11:30:00', 2, 1),   -- Laura Garnier : Toulouse -> Marseille
(10, 12, 6, '2025-10-14 07:30:00', '2025-10-14 12:00:00', 4, 3); -- Antoine Dupuis : Reims -> Nantes
