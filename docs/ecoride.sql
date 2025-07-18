/* Base de données Ecoride */

START TRANSACTION;

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS ecoride;
USE ecoride;

-- Création des tables

-- Table users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    email VARCHAR(254) UNIQUE NOT NULL,
    password VARCHAR(100) NOT NULL,				 -- bcrypt: éviter  mdp >50 char (72 max une fois haché)
	photo VARCHAR(500) NULL,                     -- nom du fichier dans uploads
    pseudo VARCHAR(50),
	phone VARCHAR(50) NULL,                      -- Inutilisé pour l'instant, proposé par client
	address VARCHAR(255) NULL,                   -- Inutilisé pour l'instant, proposé par client
    dob DATE NULL,                               -- Inutilisé pour l'instant, proposé par client
	credit INT DEFAULT 20,                       -- Offre de 20 crédits lors de l'inscription
    subscription_date DATETIME DEFAULT CURRENT_TIMESTAMP(),
	role ENUM('user','employee','admin') DEFAULT 'user',
    suspended BOOLEAN DEFAULT 0,
	
	PRIMARY KEY (id)
);

-- Table vehicles
CREATE TABLE IF NOT EXISTS vehicles (
    id INT AUTO_INCREMENT,
    driver_id INT,                               -- Utilisateur chauffeur, propriétaire présumé
    brand VARCHAR(50) NOT NULL,
    model VARCHAR(50) NULL,
	registration_date YEAR NULL,                 -- Année de première immatriculation
    registration VARCHAR(20) NOT NULL,
    fuel ENUM('essence', 'diesel', 'autre', 'hybride', 'electrique') DEFAULT 'essence',
    color VARCHAR(50) NULL,
    seats INT DEFAULT 4,
    smoking BOOLEAN DEFAULT 0,
    animals BOOLEAN DEFAULT 0,
    misc TEXT NULL,
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP(),
	update_date DATETIME NULL,
	
	PRIMARY KEY (id),
	
	FOREIGN KEY (driver_id) 
	REFERENCES users(id)
	ON DELETE CASCADE ON UPDATE CASCADE 
);

-- Table carpools
CREATE TABLE IF NOT EXISTS carpools (
    id INT AUTO_INCREMENT,
    driver_id INT,
    vehicle_id INT,
    departure_date DATE NOT NULL,
    departure_time TIME NOT NULL,
    departure_address VARCHAR(100),
	departure_city VARCHAR(100) NOT NULL,
    arrival_city VARCHAR(100) NOT NULL,
	arrival_address VARCHAR(100),
	travel_time TINYINT,
    price INT NOT NULL,
    description TEXT NULL,                            -- TEXT ~64KB (ie 65535 char)
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP(),
	update_date DATETIME,
    status ENUM('planifie', 'annule', 'en_cours', 'termine', 'valide') DEFAULT 'planifie',
	
    PRIMARY KEY (id),
	
	FOREIGN KEY (driver_id) 
	REFERENCES users(id) 	                     -- chauffeur supprimé -> leurs covoiturages suivent
	ON DELETE CASCADE ON UPDATE CASCADE,
	
    FOREIGN KEY (vehicle_id) 
	REFERENCES vehicles(id)
	ON DELETE CASCADE ON UPDATE CASCADE 
);

-- Table reviews
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT,
    user_id INT,                                 -- Utilisateur (passager) qui donne l'avis
    carpool_id INT,
    rating TINYINT,                              -- Note de 1 à 5
    comment TEXT NULL,                           -- Avis écrit optionnel (ie peut être NULL)
    objection BOOLEAN DEFAULT 0,
	objection_reviewed BOOLEAN DEFAULT 0,
	validated BOOLEAN DEFAULT 0,
    creation_date DATETIME DEFAULT CURRENT_TIMESTAMP(),
	update_date DATETIME NULL,
	
    PRIMARY KEY (id),
	
	FOREIGN KEY (user_id) 
	REFERENCES users(id)
	ON DELETE CASCADE ON UPDATE CASCADE,
    
	FOREIGN KEY (carpool_id) 
	REFERENCES carpools(id)
	ON DELETE CASCADE ON UPDATE CASCADE
);

-- Table user_carpool
/* table pivot de la relation "participe" n:n) */
CREATE TABLE IF NOT EXISTS user_carpool (
    user_id INT,
    carpool_id INT,
    participation_date DATETIME DEFAULT CURRENT_TIMESTAMP(),
	
    FOREIGN KEY (user_id) 
	REFERENCES users(id)
	ON DELETE CASCADE ON UPDATE CASCADE,
	
	FOREIGN KEY (carpool_id) 
	REFERENCES carpools(id)
	ON DELETE CASCADE ON UPDATE CASCADE 
);

-- Vues

-- Vue pour récupérer le nombre de sièges réservés
CREATE VIEW view_participants AS
SELECT 
	COUNT(*) AS seats_taken, 
	carpool_id
FROM user_carpool
GROUP BY carpool_id;

-- Vue pour récupérer les moyennes des chauffeurs et nombre d'avis
CREATE VIEW view_ratings AS
SELECT 
	ROUND(AVG(r.rating), 2) AS avg_rating, 
	COUNT(*) AS ratings_nbr,
	c.driver_id
FROM reviews r
RIGHT JOIN carpools c on r.carpool_id = c.id
WHERE (r.validated = 1 AND r.rating IS NOT NULL)
GROUP BY c.driver_id;

-- Vue de tous les détails covoiturages
/* note: si aucun avis, 'moyenne' à 5 */
CREATE VIEW view_carpools_full AS
SELECT 
	c.id,
	c.driver_id,
	c.departure_city,
	c.departure_date,
	c.departure_time,
	COALESCE(c.departure_address, 'Aucune adresse') AS departure_address,
	c.arrival_city,
	COALESCE(c.arrival_address, 'Aucune adresse') AS arrival_address,
	c.travel_time,
	c.price,
	COALESCE(c.description, 'Pas de description.') AS description,
    c.status,
    u.pseudo,
    u.photo,
    v.brand,
	v.model,
	v.registration_date,
	v.fuel,
	v.color,
	v.smoking,
	v.animals,
	COALESCE(v.misc, 'Pas d\'autres préférences') AS misc,	
	(v.seats - COALESCE(vp.seats_taken, 0)) AS remaining_seats,
	COALESCE(vr.avg_rating, 5) AS avg_rating,
	COALESCE(vr.ratings_nbr, 0) AS ratings_nbr
FROM carpools c
JOIN users u ON c.driver_id = u.id
JOIN vehicles v ON c.vehicle_id = v.id
LEFT JOIN view_participants vp on c.id = vp.carpool_id
LEFT JOIN view_ratings vr on c.driver_id = vr.driver_id;

-- Vue pour récupérer les commentaires pour chaque conducteurs
CREATE VIEW view_driver_comments AS
SELECT 
	c.driver_id,
	r.rating,
	r.creation_date,
	r.comment
FROM reviews r
JOIN carpools c ON r.carpool_id = c.id
WHERE (r.validated = 1 AND r.objection = 0)
ORDER BY r.creation_date DESC;

-- Vue pour récupérer les id covoiturages et status d'après les id passager
CREATE VIEW view_carpools_status AS
SELECT 
	uc.user_id,
	c.id AS carpool_id,
	c.departure_date,
	c.status
FROM user_carpool uc
JOIN carpools c ON uc.carpool_id = c.id
ORDER BY c.departure_date DESC, c.departure_time DESC;

COMMIT;


-- 6. Conclusion/divers
-- ---------------------------------------------
/*
  - enum pour role car un seul role/user et 3 roles seulement
  - pas d'enum pour statuts car + nbx et pas pour energ car ce qui est considéré ecolo pourrait changer
 */