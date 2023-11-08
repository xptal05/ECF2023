


#Mon Projet

Ceci est un projet d'un site pour un restaurant pour l'ECF STUDI 2023.

Les fonctionnalitées demandées:

l'admin PEUT crées des formules et des menu et les affichers.

Un client peut crée un compte et ainsi ce connecter.

Client/Visiteur peuvent réserver à une date précise et un créneau précis

Le client à ses données déjà pré rempli.

#ADMINISTRATEUR:

Le compte Admin à été crée : email :studiECF@test.com , pwd :123456. Si vous utilisez les fixtures , l'admin est codé en dure dans le code de la fixture. Il suffit de vous connecter pour accédez au backoffice ADMIN et ainsi pouvoir changer l'email et le mot de passe.

Si vous utilisez mon export de BDD : Admin -> email : studiECF@test.com , pwd : 123456

#PRÉ REQUIS : PHP 8.0 minimum Symfony 6 MariaDB Nginx Composer

#INSTALLATION DU PROJET EN LOCAL :

se rendre sur github : https://github.com/yassrzg/studi.git

git clone https://github.com/yassrzg/studi.git

#LOCAL

Soyer connecté à internet pour que l'API d'envoie de mail fonctionne.

#BASE DE DONNÉE

J'ai exporté pour vous ma bdd avec la quel j'ai mis en ligne le site si vous souhaitez l'utiliser. vous la trouverait dans export.sql : Pour l'utiliser il vous faut crée une Base de donnée ;

mysql -u username -p database_name < export.sql

Exécuter cela à la racine de votre projet , et remplacer username par le nom de votre base de donner et databasename par le nom de votre database

Ou bien vous pouvez utiliser la migration qui est un fichier qui se trouve dans le dossier évaluation studi ecf envoyé. Puis ensuite l'importer et exécuter les fixtures avec la commande suivante :

php bin/console doctrine:fixtures:load

Liens du site :




Voici votre site d'administration pour la gestion d'accès des franchisés Fitness Spirit. Vous pouvez également créer de nouveaux profils administrateur.

Pré-requis:

-En local

Utiliser un générateur de serveur local (ex: xampp).
Avoir un éditeur de code (ex: visual studio code).
My sql sur votre ordinateur.
Un gestionnaire de base de donnée (ex: phpMyAdmin).
-En ligne

Avoir un hébergeur.
Avoir une base de données.
Avoir un éditeur de code (ex: visual studio code).
-Installation

Télecharger le code du site sur GitHub
Créer la base de données en éxécutant le fichier BDD_fitness_spirit.sql depuis votre hébergeur.
Modifier avec les informations de votre serveur, le fichier database.php qui se trouve dans le document database. database
Exporter l'ensemble du contenu sur serveur de façon que le fichier index.php soit à la racine.
-Développé avec

Visual studio code.
Xampp.
PhpMyAdmin.
Git bash.
Photoshop.
Bootstrap.
HTML
CSS
javascript
PHP
SQL
-Auteur

Alexandre Malésieux Alias @Crash
(https://fitnessspirit.online/ /contirubors)


Project Garage V. Parrot
Site vitrine du garage, disposant de la présentation des services proposés, des véhicules disponible à la vente, des horaires d'ouvertures ainsi qu'un moyen de contact.

Documentation
https://symfony.com/doc/current/index.html https://dev.mysql.com/doc/

Admin Connexion

Mail: VincentParrot@gmail.com MDP: 1234 (hashé dans la base de donnée)

Features
Light/dark mode
Gestions Admin EasyAdmin
Fullscreen mode
Cross platform
Run Locally
Clone the project

  git clone du Projet
Launch MySQL

Services de composants>MySQL80>Démarrer

Install dependencies

   conposer uptdate
   yarn install
Start the server

  symfony server:start -d
Tech Stack
"require":

    "php": ">=8.1",
    "ext-ctype": "*",
    "ext-iconv": "*",
    "doctrine/doctrine-bundle": "^2.10",
    "doctrine/doctrine-migrations-bundle": "^3.2",
    "doctrine/orm": "^2.15",
    "easycorp/easyadmin-bundle": "^4.7",
    "phpdocumentor/reflection-docblock": "^5.3",
    "phpstan/phpdoc-parser": "^1.22",
    "symfony/asset": "6.3.*",
    "symfony/console": "6.3.*",
    "symfony/doctrine-messenger": "6.3.*",
    "symfony/dotenv": "6.3.*",
    "symfony/expression-language": "6.3.*",
    "symfony/flex": "^2",
    "symfony/form": "6.3.*",
    "symfony/framework-bundle": "6.3.*",
    "symfony/http-client": "6.3.*",
    "symfony/intl": "6.3.*",
    "symfony/mailer": "6.3.*",
    "symfony/mime": "6.3.*",
    "symfony/monolog-bundle": "^3.0",
    "symfony/notifier": "6.3.*",
    "symfony/process": "6.3.*",
    "symfony/property-access": "6.3.*",
    "symfony/property-info": "6.3.*",
    "symfony/runtime": "6.3.*",
    "symfony/security-bundle": "6.3.*",
    "symfony/serializer": "6.3.*",
    "symfony/string": "6.3.*",
    "symfony/translation": "6.3.*",
    "symfony/twig-bundle": "6.3.*",
    "symfony/validator": "6.3.*",
    "symfony/web-link": "6.3.*",
    "symfony/webpack-encore-bundle": "^2.0",
    "symfony/yaml": "6.3.*",
    "twig/extra-bundle": "^2.12|^3.0",
    "twig/twig": "^2.12|^3.0",
    "vich/uploader-bundle": "^2.1"
"devDependencies": { "@babel/core": "^7.17.0", "@babel/preset-env": "^7.16.0", "@popperjs/core": "^2.11.8", "@symfony/webpack-encore": "^4.4.0", "autoprefixer": "^10.4.14", "bootstrap": "^5.3.0", "core-js": "^3.23.0", "jquery": "^3.7.0", "postcss-loader": "^7.3.3", "regenerator-runtime": "^0.13.9", "sass": "^1.63.6", "sass-loader": "^13.0.0", "webpack": "^5.74.0", "webpack-cli": "^4.10.0", "webpack-notifier": "^1.15.0"

About
ECF STUDI

Resources
 Readme
 Activity
Stars
 0 stars
Watchers
 1 watching
Forks
 0 forks
Report repository
Releases
No releases published
Packages
No packages published
Languages
PHP
83.7%
 
Twig
8.8%
 
JavaScript
7.4%
 
SCSS
0.1%
Footer


Prérequis
PHP 7 ou plus
Un server local en SQL (xampp, wampp)
Composer
Node.js
Les accès pré établis pour tester l'application
Administrateur : - email : admin@test.com - mot de passe : admin

Employes : - email : employe@test.com - mot de passe : Employe01!

Inscrit : - email : jean@rochefort.com - mot de passe : jeanrochefort

Guide de déployement en local
Afin de déployer l'application en local sur votre apprareil suivre les instructions suivantes :

Créer un dossier dans le répertoire où se trouve votre logiciel de server local (ex: Xampp, Wampp ...).
Ouvrir votre terminal et taper git clone git@github.com:DumeGrisoni/ECF_Mediatheque.git.
Ouvrir le dossier et chercher le fichier ecf_mediatheque.sql.
Importer le fichier ecf_mediatheque.sql dans le serveur local afin de créer de créer une nouvelle base de données.
Ouvrir l'IDE (ex: VisualStudioCode) dans le dossier Ecf_mediatheque et décommenter dans le fichier .env la ligne DATABSE_URL selon le serveur utilisé. exemple : mysql si le serveur est en sql.
Modifier les valeurs de cette ligne en entrant les données de votre serveru local:
db_user : votre notre d'utilisateur
db_password : votre mot de passe
db_root: la route de votre serveur (ex: 127.0.0.1)
db_name : ecf_mediatheque
Taper la commande suivante dans le terminal de votre IDE ouvert à la racine du projet : composer install
Une fois les differentes dépendances dont l'application a besoin installés vous pouvez taper la commande symfony serve -d afin de lancer le serveur Symfony et voir l'application en direct dans votre navigateur.

Guide de déployement sur un serveur en ligne
Afin de déployer l'application sur un serveur en ligne Heroku suivez les differentes étapes :

Dans l'IDE puis dans le fichier .env modifier la ligne APP_ENV=dev et APP_ENV=prod, afin de passer en mode production.
Créer un compte chez Heroku, télécharger le Heroku Cli et l'installer sur votre appareil.
Dans l'IDE taper la commande heroku login et connectez vous.
Taper heroku create pour créer un projet heroku. Vous pouvez ajouter après le create le nom du projet souhaité.
Taper ensuite heroku config:set APP_ENV=prod.
Déployer l'application grâce à la commande git push heroku master.
Sur le site d'Heroku ajouter la ressource Cleardb dans votre projet sur le site.
Aller dans l'onglet Settings puis cliquer sur Reveal Config vars et les variables suivantes : - APP_ENV => prod si celle-ci n'est pas déjà écrite - DATABASE_URL => copier l'url fourni dans CLEARDB_DATABASE_URL - SYMFONY_ENV => prod
Dans l'onglet Deploy choisir Enable Automatic Deploy pour que le site se deploie a chaque fois que vous ferez un git push heroku master.
Ensuite dans le fichier .env modifier la ligne DATABASE_URL pour y coller l'url CLEARDB_DATABASE_URL
Télécharger le logiciel de controle de votre base de données (ex : phpmyadmin pour mysql), créer une nouvelle connexion vers le serveur de Cleardb en y entrant les données situées dans la variable CLEARDB_DATABASE_URL (exemple : mysql://ba52daera1d:e6dtft42@eu-cdbr-west-01.cleardb.com/heroku_81156tereeg2330?reconnect=true
user => ba52daera1d
password => e6dtft42
root => eu-cdbr-west-01.cleardb.com
db_name => heroku_81156tereeg2330 ).
Une fois connecté importer le fichier ecf_mediatheque.sql en tant que nouvelle base de données.
Dans le fichier public/index.php supprimer les # pour les lignes 6 à 12 puis la 14.
Lancer la commande git push heroku master.
Enfin taper la commande heroku open.

PROCÉDURE D'INSTALLATION

Prérequis: . Assurez-vous que vous avez installé les prérequis pour exécuter Symfony sur votre système (PHP, Composer, MySQL, etc.). . Créez une base de données vide sur votre serveur MySQL qui sera utilisée par le projet Symfony avec comme nom "usersymfony"

Clonez le dépot GitHub : . Copiez l'url du dépot GitHub = https://github.com/Biteau-Gael/symfony-authentication.git . Ouvrez un terminal ou une console sur votre machine locale . Utilisez la commande 'git clone' pour cloner le dépot GitHub sur votre machine : "git clone https://github.com/Biteau-Gael/symfony-authentication.git" . Cela créera un répertoire contenant le code source du projet Symfony

Accédez au répertoire du projet . Utilisez la commande 'cd' pour accéder au répertoire du projet que vous venez de cloner : "cd symfony-authentication"

Installez les dépendances avec Composer . Exécutez la commande composer install pour installer les dépendances du projet Symfony : "composer install"

Ouvrez le fichier .env dans un éditeur de texte et configurez les paramètres de connexion à la base de données (DATABASE_URL) en utilisant les informations d'accès que vous avez créées dans la base de données préalablement.

Exécutez la commande pour mettre à jour le schéma de la base de données en utilisant Doctrine : "php bin/console doctrine:migrations:migrate"

lancez le serveur web:

"symfony server:start"

UTILISATION DU PROFIL ADMINISTRATEUR :

Un compte par défaut avec le role "ADMIN" sera inscrit automatiquement en base de donnée lorsque que vous créerez l'application.

Voici les identifiants :

mail => t@t.com
pseudo : vincent
mdp => viveledev
Pour changer le mot de passe, il vous suffit d'accéder à la base de donnée MySql et de se diriger sur la table "user" pour ainsi modifier le mot de passe. Etant donné que le site crypte automatiquement tout les mot de passe, il vous faudra au préalable se rendre sur le site "https://busilearn.fr/outils/cryptage-mot-de-passe#:~:text=Le%20cryptage%20de%20mot%20de%20passe%20en%20Standard%20DES%2C%20MD5%20et%20sha1&text=DES%20est%20un%20algorithme%20de,4%20octets%20pour%20la%20cl%C3%A9.". Vous aurez juste à inscrire le mot de passe souhaité et de sélectionner le résultat correspondant à la ligne "Standard DES".


Fonctionnement du site
L'application web Garage Parrot est hébergé sur un serveur. L'administration passe uniquement via ce serveur et les pages administration du site même.

Aucune installation locale n'est requise.

Connexion en tant qu'administrateur :
Le site génère automatiquement les données relatives à l'administrateur global. Pour se connecter, veuillez vous rendre sur le lien administration situé en bas à droite de chaque page. En tant que gérant du site, vos identifiants de connexion sont :

- identifiant : vincent-p@vpgarage.fr - mot de passe : Enj0liver

Vous accédez maintenant à la page d'accueil de l'administration. D'ici, un menu déroulant vous permet d'accéder à différents services. Sélectionnez dans le menu le service auquel vous souhaitez accéder puis cliquez sur valider. L'en tête présente plusieurs éléments.

La couleur orange détermine l'administrateur principal. ** Une couleur bleue caractérise une connexion via un compte employé.
Le cercle en haut à gauche présente les initiales de l'utilisateur. En haut à droite, un lien de déconnexion (voir le paragraphe Quitter la page d'administration plus bas).

Les services accessibles sont :

Modération des avis
Ajout d'annonces de véhicules d'occasions
Ajout et modification d'un compte employé-e
Les pages s'affichent dynamiquement en dessous du menu principal. Ils sont généralement sous la forme de formulaires à remplir puis à valider.

! ATTENTION : Veillez à remplir correctement tout les champs en fonction des informations demandées.

Quitter la page d'administration :
Pour quitter le menu d'administration, veuillez cliquer sur le lien de déconnexion en haut à droite de la page. Vous basculerez alors sur la page d'accueil principale du site. Sachez que vous serez automatiquement déconnecté lorsque vous quitterez le site par un autre moyen que la déconnexion via la page d'administration.

Garage Vincent Parrot - ECF Studi

Comment éxécuter l'application web en local : a. installer PHP : https://windows.php.net/download#php-8.2 b. installer MySQL : https://www.mysql.com/fr/downloads/ c. installer extension MySQL v6.7.2 by Weijan Chan sur Visual Studio Code d. ouvrir le terminal de Visual studio Code e. lancer le serveur avec la commande : php -S localhost:8000 -t public (pour accéder au dossier public du site) f. ouvrir le navigateur et taper locallhost:8000 dans la barre d'adresse pour arriver sur la page d'accueil index.php

Liens d'accès au site en ligne : https://evaluation-ecf.000webhostapp.com

Liens vers le trello pour la gestion de projet : https://trello.com/b/FVckoxju/ecf-projet-garage-cr%C3%A9er-une-application-web

Lien d'accès vers les parties admin en ligne (cette partie n'est pas finie et non accessible autrement : • https://evaluation-ecf.000webhostapp.com/admin • https://evaluation-ecf.000webhostapp.com/admin/cars • https://evaluation-ecf.000webhostapp.com/admin/accounts • https://evaluation-ecf.000webhostapp.com/admin/messages • https://evaluation-ecf.000webhostapp.com/admin/services • https://evaluation-ecf.000webhostapp.com/admin/testimonials

Le fichier sql de création et d'alimentation de la base de donnée se trouve dans le dépôt git sous le nom "garage.sql"


URL du site en déployé en ligne:
Garage Parrot.

Les prérequis:
PHP 8.0 minimum.
Un serveur type APACHE.
MySQL.
Un SGBD type PhpMyAdmin. Je recommande l'utilisation du logiciel "XAMPP" sur Windows "LAMP" sur MacOS et "MAMP" sur Linux, logiciel permettant de gérer un serveur local facilement.
Récupérer le dossier du projet.
Avec git ( vous devez avoir git d'installé sur votre machine ).
Lancez votre Terminal.
Allez dans votre dossier de projets.
Exécutez la commande suivante: git clone https://github.com/antho6473/ECF_STUDI.git
Sans git.
Allez sur mon projet git.
CLiquez sur "Code" et sur "Download zip" ou "Télécharger Zip".
Décompressez le dossier dans le répertoire de vos projets.
Déployer localement le site.
Lancez votre serveur ainsi que MYSQL.
Allez sur votre SGBD et cliquez sur "Importer", cherchez le répertoire du projet, allez dans le dossier "database" et séléctionnez "database.sql".
Allez ensuite dans ce même dossier et ouvrez le fichier "connDb.php" dans votre éditeur de texte.
Changez $username et $password celon vote configuration de sgbd, $servername ne change généralement pas.
Enregistrez le fichier (CTRL + S) et fermez le.
Désormais allez dans le dossier "functions" présent à la racine du projet et ouvrez le fichier "hashPassword.php".
Modifiez les valeurs $user et $pass qui seront vos identifiants d'admin de connexion au site.


garage_ecf
Garage Vincent parrot ECF septembre 2023 STUDI

Comment éxécuter l'application web en local :

installer PHP : https://windows.php.net/download#php-8.2
installer MySQL : https://www.mysql.com/fr/downloads/
installer extension MySQL v6.7.2 by Weijan Chan sur Visual Studio Code
ouvrir le terminal de Visual studio Code
Lancer le serveur avec la commande : php -S localhost:8000 -t public (pour accéder au dossier public du site)
ouvrir le navigateur et taper locallhost:8000 dans la barre d'adresse pour arriver sur la page d'accueil index.php
Liens d'accès au site en ligne : https://ecf-valentin-boulet.000webhostapp.com/

Liens vers le trello pour la gestion de projet : https://trello.com/b/FVckoxju/ecf-projet-garage-cr%C3%A9er-une-application-web

Lien d'accès vers les parties admin en ligne (cette partie n'est pas finie et non accessible autrement : https://ecf-valentin-boulet.000webhostapp.com/admin https://ecf-valentin-boulet.000webhostapp.com/admin/cars https://ecf-valentin-boulet.000webhostapp.com/admin/accounts https://ecf-valentin-boulet.000webhostapp.com/admin/messages https://ecf-valentin-boulet.000webhostapp.com/admin/services https://ecf-valentin-boulet.000webhostapp.com/admin/testimonials

Le fichier sql de création et d'alimentation de la base de donnée se trouve dans le dépôt git sous le nom garage.sql