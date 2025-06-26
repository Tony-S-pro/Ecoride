## Contenu du dépôt

Organisation des fichiers :

```markdown
MVC/
│
├── app/                         # Application MVC
│   ├── Config/
│   │   └── Config.php           # Configuration du site (mdp de la bdd, constantes, etc)
│   │
│   ├── Controllers/
│   │   └── HomeController.php   # Exemple de contrôleur (pour la homepage)
│   │
│   ├── Models/
│   │   └── Model.php            # Classe modèle de base (parent des autres modèles)
│   │
│   ├── Views/
│   │   ├── layout.php           # Layout de base
│   │   ├── error.php            # Vue d’erreur de base
│   │   ├── elements/
│   │   │   ├── header.php       # En-tête des pages HTML
│   │   │   └── footer.php       # Pied de page des pages HTML
│   │   └── home/
│   │       └── index.php        # Vue correspondant à HomeController
│   │
│   ├── Route/
│   │   ├── Router.php           # Routeur
│   │   └── Routes.php           # Fichier de déclaration des routes
│   │
│   └── Core/
│       ├── Controller.php       # Contrôleur parent commun (parent des autres contrôleurs)
│       ├── Database.php         # Connexion PDO
│       └── View.php             # Organise le rendu des vues
│
│   ├── .htaccess                # Fichier config Apache, gère l’accès aux fichiers
│   ├── autoload.php             # Fonction autoload pour charger les .php
│   └── index.php                # Routeur qui demarre l'app en appelant le controlleur aproprié à l'url
│
├── public/                      # Dossier web accessible à l'utilisateurs
│   └── assets/                  # Dossier des resources statiques (css/js/images/polices/etc)
│       ├── css/
│       │   └── style.css        # Feuille de style (chargé après bootstrap)
│       ├── img/
│       └── js/
│           └── script.js        # Script js (chargé après bootstrap)
│   ├── index.php                # Point d'entrée de l'app
│   └── .htaccess                # Fichier config Apache, ici gère le redirect vers index.php (URL rewriting)
│
├── vendor/                      # Dossier packages/libraries
│
├── README.md                    # Ce fichier
└── robot.txt                    # Fichier destiné aux crawlers des moteurs de recherche
```

---

- On démarre avec un framework MVC de base
- Les vues utilisent un layout avec `header.php` et `footer.php` communs.