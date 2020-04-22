<?php 

// require_once('models/Utilisateur.php');
// $user = new Utilisateur;

// $all = $user->findAll();
// foreach ($all as $row) {
//     echo $row["id_{$this->_table}"];
// }

// $id = $user->findBy('nom','TEST');
// var_dump($id);

// $user->insertInto([
//     'email_utilisateur' => 'email@email.com',
//     'prenom_utilisateur' => 'gerard',
//     'nom_utilisateur' => 'dupont',
//     'mdp_utilisateur' => 'test'
// ]);

// $user->deleteBy('id', 10);


require_once('./models/Utilisateur.php');
require_once('Renderer.php');

class UtilisateurC extends Utilisateur {


    public function index() 
    {
        // Requêtes
        $user = $this->findBy('id', $_SESSION["id_utilisateur"]);
        $prenom = $user["prenom_{$this->_table}"];

        // Affichage
        $pageTitle = 'Accueil';
        Renderer::render('index', compact('pageTitle', 'prenom'));
    }

    public function connec() 
    {
        // Err
        $Err = htmlentities($_GET['err']);
        if ($Err) {
            switch ($Err) {
                case 1:
                    $Err = "Veuillez saisir votre email.";
                    break;
                case 2:
                    $Err = "Le format de l'email invalide.";
                    break;
                case 3:
                    $Err = "Veuillez saisir votre mot de passe.";
                    break;
                case 4:
                    $Err = "L'email indiqué n'existe pas.";
                    break;
                case 5:
                    $Err = "Votre mot de passe est incorrect.";
                    break;
                case 6:
                    $Err = "Vous n'avez pas validé votre adresse email.";
                    break;
                case 7:
                    $Err = "Le lien de validation est incorrecte.";
                    break;
                default:
                    $Err = "Evitez de jouer avec l'url :D";
            } 
        }
        $Val = htmlentities($_GET['val']);
        if ($Val) {
            switch ($Val) {
                case 'ok':
                    $Val = "Inscription réussi ! Veuilliez valider votre compte par mail.";
                    break;
                case 'email':
                    $Val = "Votre adresse email a déjà été validé.";
                    break;
            }
        }


        // Affichage
        $pageTitle = 'Connexion';
        Renderer::render('connexion', compact('pageTitle', 'Err', 'Val'));

    }

    public function tconnec() 
    {

        // Init var
        $email_utilisateur = $mdp_utilisateur = $id_utilisateur = "";
        $Err = 0;

        // Fontion test
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Vérification champs
        if (empty($_POST["mdp_utilisateur"])) {
            $Err = 3;
        } else {
            $mdp_utilisateur = test_input($_POST["mdp_utilisateur"]);
        }
        if (empty($_POST["email_utilisateur"])) {
            $Err = 1;
        } else {
            $email_utilisateur = test_input($_POST["email_utilisateur"]);
            if (!filter_var($email_utilisateur, FILTER_VALIDATE_EMAIL)) {
                $Err = 2;
            }
        }

        // SI TOUT EST OK
        if ( $Err===0 AND !empty($email_utilisateur) AND !empty($mdp_utilisateur) ) {

            // Recherche de l'utilisateur
            $users = $this->findAll();
            $error = true;
            foreach ( $users as $user ) { 
                if ( $user["email_{$this->_table}"] === $email_utilisateur ) {
                    $error = false;
                    if ( password_verify($mdp_utilisateur, $user["mdp_{$this->_table}"]) ) {
                        if ( $user["token_{$this->_table}"] == NULL AND !empty($user["confirmat_{$this->_table}"]) ) {
                            $id_utilisateur = $user["id_{$this->_table}"];
                        } else {
                            $Err = 6;
                        }
                    } else {
                        $Err = 5;
                    }
                }
            }
            if ($error) {
                $Err = 4;
            }
        }
        if ($Err === 0) {
            // Création de la session
            session_start();
            $_SESSION["id_utilisateur"] = $id_utilisateur;

            // Redirection
            header('location:index.php');
            exit();
        } else {
            header('location:index.php?controller=utilisateur&task=connec&err='.$Err.'');
            exit();
        }

    }

    public function deco()
    {
        session_destroy();
        header('Location:index.php');
        exit();
    }

    public function inscrip() 
    {   
        // Err
        $Err = htmlentities($_GET['err']);
        if ($Err) {
            switch ($Err) {
                //mot de passe 1x
                case 11:
                    $Err = "Veuillez saisir un mot de passe.";
                    break;
                case 12:
                    $Err = "Format du mot de passe invalide.<br>8 caractères minimum avec au minimum 1 majuscule, 1 minuscule et 1 chiffre.";
                    break;
                // email 2x
                case 21:
                    $Err = "Veuillez saisir votre adresse email.";
                    break;
                case 22:
                    $Err = "Le format de l'adresse email est invalide.";
                    break;
                case 23:
                    $Err = "Vous êtes déja inscrit. Connectez-vous : <a href='index.php?controller=utilisateur&task=connec'>ICI</a>";
                    break;
                // prenom 5x
                case 51:
                    $Err = "Veuillez saisir votre adresse prenom.";
                    break;
                case 52:
                    $Err = "Format du prenom invalide.<br>Seul les caractères alphanumérique et les accents sont autorisés.";
                    break;
                // nom 6x
                case 61:
                    $Err = "Veuillez saisir votre adresse nom.";
                    break;
                // insertion 7x
                case 71:
                    $Err = "Une erreur c'est produite lors de l'insertion dans la base de donnée. Veuillez contacter un administrateur.";
                    break;
                default:
                    $Err = "Evitez de jouer avec l'url :D";
            } 
        }

        // Affichage
        $pageTitle = 'Inscription';
        Renderer::render('inscription', compact('pageTitle', 'Err'));

    }

    public function tinscrip() 
    {


        // Init var
        $nom_utilisateur = $prenom_utilisateur = $email_utilisateur = $mdp_utilisateur = $id_utilisateur = "";
        $Err = 0;

        // Fontion test
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Vérification des champs
        // mot de passe
        if ( empty($_POST["mdp_utilisateur"]) ) {
            $Err = 11;
        } else {
            $mdp_utilisateur_tp = test_input($_POST["mdp_utilisateur"]);
            if ( !preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$#', $mdp_utilisateur_tp) ) {
                $Err =12;
            } else {
                $mdp_utilisateur = password_hash($mdp_utilisateur_tp, PASSWORD_DEFAULT);
            }
        }
        // email
        if (empty( $_POST["email_utilisateur"]) ) {
            $Err = 21;
        } else {
            $email_utilisateur = test_input($_POST["email_utilisateur"]);
            if (!filter_var($email_utilisateur, FILTER_VALIDATE_EMAIL)) {
                $Err = 22;
            } else {
                // REQ 1 ) Verif si l'email existe

                $email_utilisateur_tp = $this->findBy('email', $email_utilisateur);

                if ( !empty($email_utilisateur_tp) ) {
                    $Err = 23;
                }
            }
        }
        // prenom
        if (empty($_POST["prenom_utilisateur"])) {
            $Err = 51;
        } else {
            $prenom_utilisateur = mb_convert_case( test_input($_POST["prenom_utilisateur"]), MB_CASE_TITLE, 'UTF-8');
            if ( !preg_match('#^[\p{Latin}\' -]+$#u', $prenom_utilisateur) ) {
                $Err = 52;
            }
        }
        // nom
        if (empty($_POST["nom_utilisateur"])) {
            $Err = 61;
        } else {
            $nom_utilisateur = mb_strtoupper( test_input($_POST["nom_utilisateur"]), 'UTF-8');
        }
        
        // SI TOUT EST OK
        if ( $Err===0 AND !empty($nom_utilisateur) AND !empty($prenom_utilisateur) AND !empty($email_utilisateur) AND !empty($mdp_utilisateur) ) {

            // Création du token 
            $token_utilisateur = bin2hex( openssl_random_pseudo_bytes(20) );

            // REQ 3 ) Insertion de l'utilisateur
            $this->insertInto([
                "email_{$this->_table}" => $email_utilisateur,
                "prenom_{$this->_table}" => $prenom_utilisateur,
                "nom_{$this->_table}" => $nom_utilisateur,
                "mdp_{$this->_table}" => $mdp_utilisateur,
                "token_{$this->_table}" => $token_utilisateur
            ]);

            // REQ 4 ) Verif insertion de l'utilisateur

            $utilisateur = $this->findBy('email', $email_utilisateur);
            if ( empty($utilisateur["id_{$this->_table}"]) ) {
                $Err = 71;
            }
        }

        if ($Err === 0) {

            // Envoie du token par mail 
            $to      = $email_utilisateur;
            $subject = 'Lien de confirmation de votre inscription.';
            $message = '
             <html>
                <head>
                    <title>Site.fr : Lien de confirmation de votre inscription.</title>
                </head>
                <body>
                    <h3>Lien de validation de votre email :</h3>
                    <p>Pour valider votre inscription veuiller cliquer sur ce lien : <a href="https://saidoun.simplon-charleville.fr/connec/index.php?controller=utilisateur&task=validt&id='.$utilisateur["id_{$this->_table}"].'&token='.$token_utilisateur.'" target="_blank">LIEN DE VALIDATION</a></p>
                </body>
            </html>
            ';
            $headers = "From: nicolas08@gmail.com \r\n";
            $headers .= "Reply-To: nicolas08@gmail.com \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            mail($to, $subject, $message, $headers);


            // Redirection
            header('location:index.php?controller=utilisateur&task=connec&val=ok');
        } else {
            header('location:index.php?controller=utilisateur&task=inscrip&err='.$Err.'');
        }


    }

    public function del()
    {
        $this->deleteBy('id', $_SESSION["id_utilisateur"]);
        $this->deco();
    }

    public function validt()
    {
        // Vérif utilisateur
        $user = $this->findBy('id', htmlentities($_GET['id']) );

        // Validation token
        if ( !empty($user) AND 
            $_GET['token'] == $user["token_{$this->_table}"] AND 
            $user["confirmat_{$this->_table}"]  == NULL ) 
        {
            // Update token et confirmat
            $this->updateInto([
                "token_{$this->_table}" => "NULL",
                "confirmat_{$this->_table}" => "NOW()"
            ], 'id', $user["id_{$this->_table}"] );

            // Nouvel vérif utilisateur
            $user = $this->findBy('id', htmlentities($_GET['id']) );

            // Si token validé
            if ($user["token_{$this->_table}"]  == NULL AND 
                !empty( $user["confirmat_{$this->_table}"] )) 
            {
                // Création de la session
                session_start();
                $_SESSION["id_utilisateur"] = $user["id_{$this->_table}"];

                // Redirection
                header('location:index.php');
                exit();
            }
        } else {
            // Redirection
            header('location:index.php?controller=utilisateur&task=connec&err=7');
            exit();
        }
        
        if ( $user["token_{$this->_table}"] == NULL AND !empty($user["confirmat_{$this->_table}"]) ) {
            // Redirection
            header('location:index.php?controller=utilisateur&task=connec&val=email');
            exit();
        }
    }


}