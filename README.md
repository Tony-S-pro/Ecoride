# EcoRide - Application de Covoiturage

EcoRide est une plateforme de covoiturage développée dans le cadre de l'Évaluation en Cours de Formation (ECF) pour la validation des compétences du titre professionnel développeur Web et Web Mobile (DWWM).

**Présentation** 

D'après le Centre Interprofessionnel Technique d’Études de la Pollution Atmosphérique (CITEPA), le secteur des transports est le 1er secteur émetteur de gaz à effet de serre en France, totalisant 30% des émissions de CO2. Si on s’intéresse uniquement aux véhicules particuliers, on peut noter par exemple qu’un actif sur deux utilise une voiture pour aller au travail. Sur une distance de 10km, cela représente jusqu’à 2,2 kg d'émissions de CO2 avec un seul véhicule thermique plein de sièges vides. 

Or le covoiturage permet de diminuer le nombre de voitures sur la route, de réduire les embouteillages et de faire des économies sur les trajets. C’est un geste simple qui permet d'agir collectivement face à l’urgence de la crise climatique et que la jeune startup "EcoRide" vise à rendre plus accessible via une application web éponyme.

L’objectif de la plateforme Ecoride serait d'encourager en France les déplacements en covoiturage. Cette application permettrait à des utilisateurs de proposer des trajets à des passagers qui pourraient voyager avec eux moyennant une participation financière (au conducteur et à EcoRide). De plus, les employés de l'entreprise pourraient gérer avis et réclamations tandis que l'administrateur suivrait les progrès de la plateforme et aurait l'option de créer ou suspendre un compte.

En résumé, Ecoride faciliterait l'organisation et l'exécution de déplacements en covoiturage en mettant en contact des gens souhaitant voyager de manière économique et écologique.

---

## Fonctionnalités Principales

- Inscription des visiteurs.
- Connexion des utilisateurs, des employés et de l'administrateur.
- Recherche de covoiturage par ville (et/ou adresse) de départ, d'arrivée et date.
- Filtrage des résultats par prix/durée maximum, note chauffeur, type d’énergie (thermique/élec), préférences fumeurs, etc.
- Option du covoiturage à la date la plus proche s'il n'y a pas de résultats.
- Réservation (et annulation) de places sur les covoiturages par les utilisateurs (avec gestion du paiement par 'crédits').
- Annulation des réservation et remboursement des crédits.
- Affichage des covoiturages passés et à venir dans l'espace personnel des passagers.
- Système d'avis et note, ainsi que la possibilité d'objecter et demander un remboursement pour les covoiturages passés.
    - Système de log des objection (fonction NoSQL via MongoDB).
- Gestion des véhicules et préférences pour les utilisateurs chauffeurs (créer et supprimer).
- Ajout d'une photo au profil des chauffeurs.
- Création (et annulation) de covoiturages dans l'espace personnel des chauffeurs (avec gestion du paiement par 'crédits').
- Annulation des covoiturages, remboursement des crédits et notifications email aux passagers.
- Affichage des covoiturages passés et à venir dans l'espace personnel des chauffeur.
- Système de signalisation du démarrage et arrivée du covoiturage avec notification emails aux passagers pour valider le trajet.
- Formulaire de contact (envoi par mail).
- **Espace Employé :**
    - Modération des commentaires sur les avis (accepter/effacer).
    - Validation/rejet des demandes de remboursement.
        - Mise-à-jour des logs objection (fonction NoSQL via MongoDB)
- **Espace Admin :**
    - Affichage de statistiques : covoiturages/jour (graph), crédits par/jour (graph), total des crédits. via MongoDB.
    - Création de comptes employés.
    - Suspension/ré-instauration de comptes utilisateurs.
    - Affichage et suppression des logs des objections utilisateurs et décisions employée (fonction NoSQL via MongoDB).
    
--- 
    
## Technologies Utilisées

**Frontend :** 
    - HTML5
    - CSS3 (Bootstrap 5.3)
    - JavaScript (jQuery 3.7.1/AJAX) pour afficher du contenu sans recharger la page.
**Backend :** PHP 8.2.
**Base de Données Relationnelle :** MySQL (dév : MariaDB/XAMPP en localhost, prod : MySQL sur www.alwaysdata.com) via l'extension PDO.
**Base de Données NoSQL :** MongoDB (dev : localhost, prod : MongoDB/MongoDB Atlas) pour les logs.
**Développement Local/Serveur :** XAMPP (Apache, MariaDB, PHP).
**Dépendances PHP :**
    - `composer/composer` Composer et son autoload pour gérer les dépendances
    - `mongodb/mongodb` pour communiquer avec la bdd NoSQL.
    - `vlucas/phpdotenv` pour gérer le .env contenant les information de connection MongoDB
    - `phpmailer/phpmailer` PHPMailer pour l'envoi d'emails via smtp.
    - `symfony/var-dumper` utile pendant le dévelppement
**Gestion de version et dépôt :** Git et GitHub.
**Outil de gestion de projet :** Trello.
**Production/Hébergement :**
    - PHP/MySQL : Alwaysdata
    - MongoDB : MongoDB Atlas

---

## Installation et Déploiement en Local (pour test/développement)

### Prérequis

- Serveur web local supportant PHP 8.2+ et MySQL/MariaDB (XAMPP par exemple).
- Composer (gestionnaire de dépendances PHP).
- Serveur MongoDB local.
- Navigateur web moderne.
- Git.

### Installation locale via XAMPP

**Cloner le dépôt (Git) :**
    ```bash
    git clone [https://github.com/Tony-S-pro/Ecoride/] Ecoride
    cd Ecoride
    ```

**Installer les dépendances (Composer) :**
À la racine du dossier de l'app `Ecoride` (là où se trouve `composer.json`), exécutez :
    ```bash
    composer install
    ```
Les dépendances sont dans le dossier `Ecoride/vendor/`.

3. **Configurer la bdd MySQL (XAMPP) :**
Assurez-vous que le serveur local MySQL (via XAMPP dans cet exemple) a été démarré et ouvrez un client SQL. Par exemple le Shell MySQL accessible depuis le panneau de contrôle XAMPP (ou DBeaver, DbGate, SQL Workbench, un terminal d'IDE, etc).

- Créez une nouvelle base de données pour le projet :
    ```sql
    CREATE DATABASE IF NOT EXISTS ecoride CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
    ```
- Placez vous dans la bdd :
    ```sql
    USE ecoride;
    ```
- Exécutez le script de création des tables `ecoride.sql`. Copier le contenu du fichier dans la shell, ou utiliser la commande `source` si depuis le dossier du fichier la shell :
    ```sql
    source C:/xampp/htdocs/Ecoride/docs/ecoride.sql
    ```
- Idem pour le script d'insertion des données de test `ecoride_data.sql`.
    ```sql
    source C:/xampp/htdocs/Ecoride/docs/ecoride_data.sql
    ```
- Vous pouver ajouter un User/admin de la dbb si vous le désirez (optionnel):
    ```sql
    CREATE USER IF NOT EXISTS 'ecoride_admin'@'localhost' IDENTIFIED BY 'Admin_Jose_12345';
    GRANT ALL PRIVILEGES ON ecoride.* TO 'ecoride_admin'@'localhost';
    FLUSH PRIVILEGES;
    ```
L'utilisateur `ecoride_admin` (psw: `Admin_Jose_12345`) a tous les droits dans la base de données `ecoride`.

**Configuration de connection de l'app :**
Le fichier `config/config.php` contient les infos de configurations par défaut pour l'environnement local (XAMPP).

- Vérifiez que les identifiants MySQL (`DB_USER`, `DB_PASS`) et les identifiants SMTP pour Gmail (`SMTP_USER`, `SMTP_PASS`) sont bien défini dans ce fichier. 
- Assurez-vous que le compte Gmail est configuré pour autoriser les applications moins sécurisées ou qu'un mot de passe d'application est utilisé. 
- Vous pouvez également changer le nom de la base de donnée MongoDB (`MDB_NAME`) en local.

(Notez que la chaîne de connexion pour MongoDB Atlas (`MDB_URI`) se trouve dans `config\.env`.)

**Configurer l'extension PHP MongoDB pour XAMPP :**
- Téléchargez le fichier `.dll` de l'extension MongoDB correspondant à votre PHP depuis PECL ([https://pecl.php.net/package/mongodb](https://pecl.php.net/package/mongodb)).
- Placez le fichier `php_mongodb.dll` dans votre dossier d'extensions PHP (exemple : `C:\xampp\php\ext\`).
- Ouvrez `php.ini` (via XAMPP : Apache > Config > PHP (php.ini) ou dans le dossier `xampp\php`).
- Ajoutez la ligne `extension=mongodb` (ou `extension=php_mongodb.dll`).
- Redémarrez le serveur.

**Configurer le serveur :**
- Assurez-vous que le fichier `.htaccess` suivant est présent à la racine du dossier `Ecoride` pour pointer vers `Ecoride/public` comme point d'entrée à l'app:
    ```apache
    <IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
    </IfModule>
    ```
- Assurez-vous que le fichier `.htaccess` suivant est présent à la racine du dossier `Ecoride/public` pour le routeur et l'accès aux assets par le client:
    ```apache
    RewriteEngine On
    ##  If it's not a file/directory in /public
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    ## then redirect towards index.php and pass filename as 'url' param
    RewriteRule ^(.+)$ index.php?url=$1 [QSA,L]
    ```
- Relancer le serveur.

**Lancer l'application :**
- Ouvrez votre navigateur
- lancer le projet : [http://localhost/ecoride](http://localhost/ecoride)

---

## Contenu du dépôt

Organisation des fichiers :

```markdown
Ecoride/
│
├── app/                         # Application MVC
│   ├── Controllers/
│   │   └── HomeController.php   # Exemple de contrôleur (pour la homepage)
│   │
│   ├── Core/
│   │   ├── Controller.php       # Contrôleur parent commun (parent des autres contrôleurs)
│   │   ├── Database.php         # Connexion PDO
│   │   ├── Database.php         # Connexion MongoDB (Document Manager)
│   │   ├── ImageHelper.php      # Méthodes d'upload/redimensionnement d'images
│   │   └── MailHelper.php       # Méthodes pour l’utilisation de phpmailer
│   │
│   ├── Models/
│   │   ├── Model.php            # Classe modèle de base (parent des autres modèles)
│   │   └── ModelDM.php          # Classe modèle NoSQL de base
│   │
│   ├── Views/
│   │   ├── elements/
│   │   │   ├── header.php       # En-tête des pages HTML
│   │   │   └── footer.php       # Pied de page des pages HTML
│   │   ├── error404.php         # Vue d’erreur de base
│   │   ├── home.php             # Exemple de vue (correspondant à HomeController)
│   │   └── layout.php           # Layout de base│   │
│   │
│   ├── .htaccess                # Fichier config Apache, gère l’accès aux fichiers
│   ├── autoload.php             # Fonction autoload pour charger les classes sans composer
│   └── index.php                # Routeur qui démarre l'app en appelant le contrôleur aproprié à l'url
│
├── config/
│   ├── .env                     # Fichier de config pour MongoDB uniquement, pour l'instant
│   └── config.php               # Configuration de l'app (mdp de la bdd, constantes, etc)
│
├── docs/                        # Documentation à fournir dans le cadre de l'ECF
│
├── public/                      # Dossier web accessible à l'utilisateurs
│   ├── assets/                  # Dossier des ressources statiques (css/js/images/etc)
│   │   ├── ajax                 # Dossier des appels AJAX
│   │   ├── css/
│   │   │   └── style.css        # Feuille de style (chargé après bootstrap)
│   │   ├── img/
│   │   ├── js/
│   │   │   └── script.js        # Script js (chargé après bootstrap)
│   │   ├── uploads/             # Dossier des uploads utilisateur (photos)
│   │   └── favicon.ico          # Icône de l'app
│   │
│   ├── .htaccess                # Fichier config Apache, ici le redirect vers index.php et accès aux assets
│   ├── index.php                # Point d'entrée de l'app
│   └── robot.txt                # Fichier destiné aux crawlers des moteurs de recherche
│
├── vendor/                      # Dossier packages/libraries
│
├── .htaccess                    # Fichier config Apache, redirige vers public/index.php
├── composer.json                # Décrit les dépendances du projet + set up de l'autoload de Composer
├── composer.lock                # Décrit les versions des dépendances du projet
├── README.md                    # Ce fichier
└── robot.txt                    # Fichier destiné aux crawlers des moteurs de recherche
```

- On à démarré le projet avec un framework MVC de base
- Les vues utilisent un layout avec `header.php` et `footer.php` communs.
- On utilise uniquement PDO pour le relationnel, pas d'ORM.
- On utilise un simple CRUD NoSQL/MongoDB seulement pour les logs d'objection, a titre de démonstration.

---

## Documentation
Inclus dans le dossier `docs` :
- 2 fichiers SQL de création de bdd + données
- manuel d'utilisation
- charte graphique (palette, polices, 6 wireframes et 6 mockups)
- documentation technique (technologies, configuration d'utilisation, diagrammes, déploiement)

---

## Auteur

Projet réalisé dans le cadre du TP Développeur Web et Web Mobile (DWWM) – Studi ECF Novembre-Décembre 2025.
