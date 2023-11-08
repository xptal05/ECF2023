<?php 
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php"); // Redirect to the login page or another page as needed
    exit();
} 
// Check if the user has been inactive for 30 minutes (1800 seconds)
$inactiveTimeout = 1800; // 30 minutes in seconds
if (isset($_SESSION["last_activity"]) && (time() - $_SESSION["last_activity"]) > $inactiveTimeout) {
    // User has been inactive for too long; log them out
    session_unset();
    session_destroy();
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Update the last activity timestamp
$_SESSION["last_activity"] = time();

/*
JWT
- 2. TOKEN JWT

composer require firebase/php-jwt


    - créer le service pour authentification -> src/services/Authentification.js
    - récuperer le token via un systém de promesses
        - EXAMPLE:
            //Récupère une instance du client HTTP Axios
            import { http } from '../helpers/http';

            const login = (userCredentials) => {
                return http.post('/login', JSON.stringify(userCredentials))
                    .then(response => response.data)
                    .catch(error => console.log(error));
            }

            export const authenticationService = {
                login
            }
    - stockage dans localstorage
        - EXAMPLE
            import React from 'react';
            import './App.css';
            import { authenticationService } from './services/authentication';
            import Login form './Login'

            const App = () => {
                const loginUser = (login, password) => {
                    const userCredentials = {
                    login,
                    password
                };

                authenticationService.login(userCredentials)
                .then(loginData => window.localStorage.setItem('token', loginData.token));
                }

                return (
                    <div className="App">
                        <Login loginUser={(login, password) => loginUser(login, password)}/>        //fce loginUser est ensuite untilisě das Login.js
                    </div>
                );
            }

            export default App;
    - token dans requete
        - via une mécanique d'intercepteurs de requêtes offerte par le client HTTP Axios (centralisé via un seul code pour toute application)
        - example: src/services/Authentification.js (appels a API)
            import { http } from './http';

            const setup = () => {
                http.interceptors.request.use(config => {
                    const token = window.localStorage.getItem('token');

                    if (token) {
                        config.headers['X-Auth-Token'] = token;
                    }

                    return config;
                },

                error => {
                    return Promise.reject(error)
                });
            }

            export const interceptorsSetup = {
                setup
            }
        - EXAMPLE2:
            import { http } from './http';

            const login = userCredentials => {
                return http.post('/login', userCredentials)
                    .then(reponse => response.data)
                    .catch(error => console.log(error))
            }

            export const authentificationService = {
                login
            }
        session_start(); 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {                                                        //vérifie si la méthode de requête est POST.
            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {         // Si le jeton CSRF n'est pas présent ou s'il ne correspond pas au jeton stocké en session, on arrête le processus et affiche une erreur.
                die('Erreur CSRF !'); 
            }
        }
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));            /* Génère un token aléatoire de 32 octets à partir de la fonction random_bytes() de PHP, puis le convertit en une chaîne hexadécimale et le stocke dans la variable de session 'csrf_token'.
        <input type="" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">



        
        - VERIFICATION ADRESSE IP
    $_SESSION['ip_adress'] = $_SERVEUR['REMOTE_ADDR'];
    if(isset($_SESSION['ip_adress']) AND $_SESSION['ip_adress'] !== $_SERVEUR['REMOTE_ADDR']) {
        session_unset();
        session_destroy();
        header('location:login.php');
    }

- VERIFICATION DE NAVIGATEUR
    $_SESSION['user_agent'] = $_SERVEUR['HTTP_USER_AGENT'];  /* la suite est la meme

- GENERATION ID DE LA SESSION
    if(isset($_SESSION['last_id']) AND time() - $_SESSION['last_id'] > 10 ) {            //IF vetsí než 10s  
        session_regenarete_id(true);
        $_SESSION['last_id'] = time();
    } 

    if(!isset($_SESSION['last_id'])){                       //Jestliže Last session jesté neexistuje
        $_SESSION['last_id'] = time();
    }
