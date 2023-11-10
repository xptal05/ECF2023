Garage Vincent parrot - ECF septembre 2023 STUDI 

Site vitrine du garage, disposant de la présentation des services proposés, des véhicules disponible à la vente, des horaires d'ouvertures ainsi qu'un moyen de contact.

Un panel administrateur avec la possibilité de modération des avis, lecture des messages envoyé par via le site vitrine, ajout et modifications des utilisateurs, ajout et modifications des véhicules, modification des contacts, horaires, addresse affichés sur le site vitrine, ajout et modification des champs relatifs aux véhicules (couleurs, modele, no de portes, etc.)

Prérequis: 
GIT 
PHP 7 ou plus 
Un server local en SQL (xampp, wampp, Mamp) 
Editeur de code (ex. VS Code)

Comment éxécuter l'application web en local : 

Créer un dossier dans le répertoire où se trouve votre logiciel de server local (ex: Xampp, Wampp, Mamp ...). 
Ouvrir votre terminal et taper git clone git@github.com:https://github.com/xptal05/ECF2023.git. 
Ouvrir le dossier et chercher le fichier migration.sql dans le dossier migration. 
Importer le fichier migration.sql dans le serveur local afin de créer une nouvelle base de données. 
Créer un dossier config and un fichier db.php. Dans ce fichier créer la fonction suivante en remplisant les valeurs de votre connection a la BD (host, port, name, user, password): 
function connectToDatabase($config) { 
  try { 
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['name']}"; $pdo = new PDO($dsn, $config['user'], $config['password']); 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
    } catch (PDOException $e)
    { 
      die("Connection failed: " . $e->getMessage()); 
    } 
  } 
    
Dans le dossier config créer un fichier jwt.php avec la function generateJWT($user_id, $username) et function authenticateUser() pour l'authentification JWT. Lancer application en local sur votre localhost port
-  pour le site vitrine sur index.php
-  pour le panel administration admin/index.php 
Un compte par défaut avec le role "ADMIN" sera inscrit automatiquement en base de donnée lorsque que vous créerez l'application. (l'access administrateur est parrot(at)parrot.fr avec 123456)

Quitter la page d'administration : Pour quitter le menu d'administration, veuillez cliquer sur le lien de déconnexion sur le menu. Sachez que vous ne serez pas automatiquement déconnecté lorsque vous quitterez le site par un autre moyen que la déconnexion via la page d'administration. En revanche vous serez déconnect automatiquement au bout de 30 minut d'inactivité.

Liens d'accès au site en ligne :