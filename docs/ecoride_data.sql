/*
Base de données Ecoride
Création et insertion de données test 
*/

-- Insertion de données test

START TRANSACTION;

USE ecoride;

-- Données users
/*
role : email / mdp
Administrateur : admin@ecoride.fr / azerty123A
Employé : employe@ecoride.fr / azerty123E
Utilisateur1 (passager) : user1@mail.com / azerty123U
Utilisateur2 (conducteur) : user2@mail.com / azerty123U ...
*/
INSERT INTO users (name, firstname, email, password, pseudo, credit, role) VALUES
('Admin', 'Sys', 'admin@ecoride.fr', '$2y$10$mpiJptPn.gagIVCyY77Z5OECpUvL4feUWOOxlMUP2p7GqmonJ0Nyu', 'admin', 0, 'admin'),
('Dupont', 'Pierre', 'employe@ecoride.fr', '$2y$10$TsSCfAhNQEMBMgCFBelbCOzy7EDfbTR2izh6sBjlcatHDXekPtQW.', 'employee', 0, 'employee'),
('Leroy', 'Marie', 'user1@mail.com', '$2y$10$WoOYo.q8cORBGUx9Ce4iAOmeXPNdQFjnHiD5lSIUDETya.2QR917S', 'user1', 20, 'user'),
('Da Silva', 'Paulo', 'user2@mail.com', '$2y$10$e4NXbhwIyaE90KrGPilq1eRcgm2ZObNuSqfLTuNXhR90O74qR37Um', 'user2', 20, 'user'),
('Meunier', 'Sophie', 'user3@mail.com', '$2y$10$3tcO/W/iLSNy2rr./RyVeuL4arNhBSrgytLprCvLnU8XWrNvNn3YO', 'user3', 20, 'user'),
('Bakri', 'Camil', 'user4@mail.com', '$2y$10$SRD.F80U754AVgv/2oQo/OcOn/guF9s4bmVfEoWrbIq9Y0z6xebhu', 'user4', 20, 'user');

-- Données vehicles
INSERT INTO vehicles (driver_id, brand, model, registration_date, registration, fuel, color, seats, smoking, animals, misc) VALUES
(4, 'Dacia', 'Sandero III', '2021', 'AB-123-CD', 'diesel', 'Bleu marine', 4, 1, 1, 'Allergique aux chats'),
(4, 'Peugeot', 'e-208', '2020', 'EF-456-GH', 'electrique', 'Blanc', 4, 0, 1, 'Allergique aux chats'),
(5, 'BMW', '230i', '2016', 'IJ-789-KL', 'essence', 'Noir', 1, 0, 0, 'Pas de nourriture dans la voiture, svp.'),
(6, 'Opel', 'Corsa', '2018', 'MN-000-OP', 'essence', 'Noir', 4, 1, 0, NULL);

-- Données carpools
INSERT INTO carpools (
	driver_id, 
	vehicle_id, 
	departure_date, 
	departure_time, 
	departure_city,
	departure_address,	
	arrival_city,
	arrival_address,
	travel_time, 
	price,
	description,
	creation_date,
	status) 
VALUES
(4, 1, '2025-07-15', '08:30', 'Paris', 'rue des Départs', 'Rouen', 'rue des Arrivées', 3, 6, NULL, '2025-07-10 14:25:00', 'valide'),
(4, 1, '2025-07-16', '08:30', 'Paris', 'rue des Départs', 'Lyon', 'rue des Arrivées', 5, 12, NULL, '2025-07-10 14:26:00', 'valide'),
(4, 1, '2025-07-17', '08:30', 'Paris', 'rue des Départs', 'Rouen', 'rue des Arrivées', 3, 6, NULL, '2025-07-10 14:27:00', 'termine'),
(4, 1, '2025-07-18', '08:30', 'Paris', 'rue des Départs', 'Rouen', 'rue des Arrivées', 3, 6, NULL, '2025-07-10 14:28:00', 'termine'),
(4, 2, '2025-07-21', '08:30', 'Paris', 'rue des Départs', 'Rouen', 'rue des Arrivées', 3, 5, 'Je peux vous déposer à la gare.', '2025-07-18 14:20:00', 'planifie'),
(4, 2, '2025-07-21', '08:30', 'Paris', 'rue des Départs', 'Rouen', 'rue des Arrivées', 3, 5, 'Je dois passer par une boulangerie.', '2025-07-18 14:21:00', 'planifie'),
(4, 2, '2025-07-21', '12:00', 'Paris', 'rue des Départs', 'Versailles', 'Gare Rive-Droite', 2, 3, 'Je serais peut-être 5-10min en retard.', '2025-07-18 14:22:00', 'planifie'),
(6, 4, '2025-07-17', '06:30', 'Herblay-Sur-Seine', 'rue des Départs', 'Pontoise', 'rue des Arrivées', 1, 3, NULL, '2025-07-10 14:28:00', 'termine'),
(5, 3, '2025-07-18', '18:10', 'Pontoise', 'rue des Départs', 'Poissy', 'rue des Arrivées', 1, 4, NULL, '2025-07-12 14:28:00', 'termine');

-- Données reviews
INSERT INTO reviews (user_id, carpool_id, rating, comment, objection, validated, creation_date) VALUES 
(3, 1, 4, 'Bien. RAS.', 0, 1, '2025-07-15 15:21:00'),
(3, 2, 5, NULL, 0, 1, '2025-07-16 09:31:00'),
(5, 8, 5, 'Mr Bakri est fort sympatique.', 0, 0, '2025-07-17 7:31:00'),
(6, 9, 1, 'J\'ai fait le voyage accroché au toit. AU TOIT !', 1, 0, '2025-07-18 20:10:00');
	
	
-- Données user_carpool
INSERT INTO user_carpool (user_id, carpool_id, participation_date) VALUES 
(3, 1, '2025-07-14 16:25:10'),
(3, 2, '2025-07-14 16:26:10'),
(3, 3, '2025-07-14 16:27:10'),
(5, 8, '2025-07-16 14:28:00'),
(6, 9, '2025-07-18 14:28:00');

COMMIT;