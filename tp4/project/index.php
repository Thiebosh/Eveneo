<?php
session_start();

require('Controller/cRegisterLogin.php');
require('Controller/cFrontEnd.php');
require('Controller/cBackEnd.php');

define('MAX_LIST', 5);

//affichage des datetimes en francais
setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('UTC');
//VIEW : REPRENDRE LES LIENS DE REDIRECTION ET APPLIQUER LE GET
try {
    if (!isset($_GET['action'])) throw new Exception('Page cible inconnue');

    $action = $_GET['action'];
    if ($action != 'login' && $action != 'logout' && $action != 'register' &&
    $action != 'reception' && $action != 'list'   && $action != 'detail' &&
    $action != 'delete'    && $action != 'new'    && $action != 'edit') {
        throw new Exception('Action indéfinie');
    }
    
    /* BEGINNING */
    if ($action == 'logout') {
        $_SESSION = array();//nettoie la variable
        header('Location: index.php?action=login');//redirige vers la page de connexion
        exit();//mets fin au script courant
    }


    if (!isset($_SESSION['rank'])) {//ok
        switch ($action) {
            case 'login':
                if (isset($_POST['script_login'])) {
                    if (!isset($_POST['login']) || !isset($_POST['password'])) {
                        throw new Exception('Connexion : Données formulaire incomplètes');
                    }

                    $received['login'] = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
                    $received['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

                    if (!$received['login'] || !$received['password']) {
                        throw new Exception('Connexion : Donnée(s) formulaire invalide(s)');
                    }
                }

                login($received);
            break;

            case 'register':
                if (isset($_POST['script_register'])) {
                    if (!isset($_POST['login']) || !isset($_POST['rank']) ||
                    !isset($_POST['password']) || !isset($_POST['passwordVerif'])) {
                        throw new Exception('Inscription : Données formulaire incomplètes');
                    }

                    $received['rank'] = filter_input(INPUT_POST, 'rank', FILTER_SANITIZE_STRING);
                    $received['login'] = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
                    $received['password'] = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
                    $received['passwordVerif'] = filter_input(INPUT_POST, 'passwordVerif', FILTER_SANITIZE_STRING);

                    if (!$received['login'] || !$received['rank'] || !$received['login'] ||
                    !$received['password'] || !$received['passwordVerif']) {
                        throw new Exception('Inscription : Donnée(s) formulaire invalide(s)');
                    }
                }

                register($received);
            break;
        }
    }
    else if ($_SESSION['rank'] == 'CUSTOMER') {//ok
        switch ($action) {
            case 'reception':
                if (isset($_GET['date'])) {
                    $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
                    if (!$date) throw new Exception('Accueil : Donnée invalide');
                }


                cEventsMonth($date);
            break;
            case 'list':
                if (!isset($_GET['date'])) {
                    throw new Exception('Evénements du jour : Donnée manquante');
                }
                $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
                if (!$date) throw new Exception('Evénements du jour : Donnée invalide');


                cEventsDay($date);
            break;
            case 'detail';
                if (!isset($_GET['id'])) {
                    throw new Exception('Evénement : Donnée manquante');
                }
                $received['id'] = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                if (!$received['id']) throw new Exception('Evénement : Donnée invalide');


                if (isset($_POST['script_joined'])) {
                    if (!isset($_POST['eventJoined'])) {
                        throw new Exception('Evénement : Donnée formulaire absente');
                    }
                    $received['eventJoined'] = filter_input(INPUT_POST, 'eventJoined', FILTER_VALIDATE_BOOLEAN);
                    if (!$received['eventJoined']) throw new Exception('Evénement : Donnée formulaire invalide');
                }


                cEvent($received);
            break;
            default:
                cEventsMonth();
            break;
        }
    }
    else if ($_SESSION['rank'] == 'ORGANIZER') {//ok
        switch ($action) {
            case 'reception':
                if (isset($_GET['date'])) {
                    $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
                    if (!$date) throw new Exception('Accueil : Donnée invalide');
                }


                cEventsMonth($date);
            break;
            case 'list':
                if (!isset($_GET['date'])) {
                    throw new Exception('Evénements du jour : Donnée manquante');
                }
                $date = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
                if (!$date) throw new Exception('Evénements du jour : Donnée invalide');


                cEventsDay($date);
            break;
            case 'detail';
                if (!isset($_GET['id'])) {
                    throw new Exception('Evénement : Donnée manquante');
                }
                $received['id'] = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                if (!$received['id']) throw new Exception('Evénement : Donnée invalide');


                if (isset($_POST['script_delete'])) {
                    if (!isset($_POST['deleteIdEvent'])) {
                        throw new Exception('Evénement : Donnée formulaire absente');
                    }
                    $received['deleteId'] = filter_input(INPUT_POST, 'deleteIdEvent', FILTER_VALIDATE_INT);
                    if (!$received['deleteId']) throw new Exception('Evénement : Donnée formulaire invalide');
                    oEventDelete($received['deleteId']);
                }


                oEvent($received);
            break;
            case 'edit':
                if (!isset($_GET['id'])) {
                    throw new Exception('Modification : Donnée manquante');
                }
                $received['id'] = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
                if (!$received['id']) throw new Exception('Modification : Donnée invalide');


                if (isset($_POST['script_edit'])) {
                    if (!isset($_POST['nbPlaces']) || !isset($_POST['description']) || !isset($_POST['endDate'])) {
                        throw new Exception('Modification : Donnée(s) formulaire absente(s)');
                    }

                    $received['nbPlaces'] = filter_input(INPUT_POST, 'nbPlaces', FILTER_VALIDATE_INT);
                    $received['endDate'] = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);
                    $received['description'] = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

                    if (!$received['nbPlaces'] || !$received['endDate'] || !$received['description']) {
                        throw new Exception('Modification : Donnée(s) formulaire invalide(s)');
                    }
                }


                oEventEdit($received);
            break;
            case 'new':
                if (!isset($_GET['date'])) {
                    throw new Exception('Nouveau : Donnée manquante');
                }
                $received['date'] = filter_input(INPUT_GET, 'date', FILTER_SANITIZE_STRING);
                if (!$received['date']) throw new Exception('Nouveau : Donnée invalide');


                if (isset($_POST['script_new'])) {
                    if (!isset($_POST['name']) || !isset($_POST['nbPlace']) || !isset($_POST['description']) ||
                    !isset($_POST['startDate']) || !isset($_POST['endDate'])) {
                        throw new Exception('Nouveau : Donnée(s) formulaire absente(s)');
                    }

                    $received['name'] = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                    $received['nbPlaces'] = filter_input(INPUT_POST, 'nbPlaces', FILTER_VALIDATE_INT);
                    $received['startDate'] = filter_input(INPUT_POST, 'startDate', FILTER_SANITIZE_STRING);
                    $received['endDate'] = filter_input(INPUT_POST, 'endDate', FILTER_SANITIZE_STRING);
                    $received['description'] = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

                    if (!$received['name'] || !$received['nbPlaces'] || !$received['description'] ||
                    !$received['startDate']|| !$received['endDate']) {
                        throw new Exception('Nouveau : Donnée(s) formulaire invalide(s)');
                    }
                }


                oEventNew($received);
            break;
            default:
                oEventsMonth();
            break;
        }
    }
    else throw new Exception('Rang : problème de définition');
}

catch(Exception $error) {//rediriger vers la page en affichant par dessus un bloc erreur, qu'on peut "fermer" en appuyant sur un bouton
    $errorMessage = $error->getMessage();
    $redirection['text'] = 'l\'accueil';
    $redirection['link'] = 'reception';
    if (!isset($_SESSION['rank'])) {
        $redirection['text'] = 'l\'écran de  connexion';
        $redirection['link'] = 'login';
    }
    
    require('View/vError.php');
}
