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
        // Redirect index si déjà connecté
        $this->redirectIfConnec();

        // Affichage
        $pageTitle = 'Connexion';
        Renderer::render('connexion', compact('pageTitle'));
    }

    public function tconnec() 
    {
        // Redirect index si déjà connecté
        $this->redirectIfConnec();

        // Init var
        $email_utilisateur = $mdp_utilisateur = $id_utilisateur = "";

        // Fontion test
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Vérification champs
        if (empty($_POST["mdp_utilisateur"])) {
            $_SESSION['flash']['danger'] = "Veuillez saisir votre mot de passe.";
        } else {
            $mdp_utilisateur = test_input($_POST["mdp_utilisateur"]);
        }
        if (empty($_POST["email_utilisateur"])) {
            $_SESSION['flash']['danger'] = "Veuillez saisir votre email.";
        } else {
            $email_utilisateur = test_input($_POST["email_utilisateur"]);
            if (!filter_var($email_utilisateur, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash']['danger'] = "Le format de l'email invalide.";
            }
        }

        // SI TOUT EST OK
        if ( empty($_SESSION['flash']) AND !empty($email_utilisateur) AND !empty($mdp_utilisateur) ) {

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
                            $_SESSION['flash']['danger'] = "Vous n'avez pas validé votre adresse email.";
                        }
                    } else {
                        $_SESSION['flash']['danger'] = "Votre mot de passe est incorrect.";
                    }
                }
            }
            if ($error) {
                $_SESSION['flash']['danger'] = "L'email indiqué n'existe pas.";
            }
        }
        if ( empty($_SESSION['flash']) ) {
            // Création de la session
            session_start();
            $_SESSION["id_utilisateur"] = $id_utilisateur;

            // Redirection
            $_SESSION['flash']['success'] = "Vous êtes connecté. Nous vous souhaitons une bonne journée !";
            header('location:index.php');
            exit();
        } else {
            header('location:index.php?controller=utilisateur&task=connec');
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
        // Redirect index si déjà connecté
        $this->redirectIfConnec();

        // Affichage
        $pageTitle = 'Inscription';
        Renderer::render('inscription', compact('pageTitle'));

    }

    public function tinscrip() 
    {
        // Redirect index si déjà connecté
        $this->redirectIfConnec();

        // Init var
        $nom_utilisateur = $prenom_utilisateur = $email_utilisateur = $mdp_utilisateur = $id_utilisateur = "";

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
            $_SESSION['flash']['danger'] = "Veuillez saisir un mot de passe.";
        } else {
            $mdp_utilisateur_tp = test_input($_POST["mdp_utilisateur"]);
            if ( !preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$#', $mdp_utilisateur_tp) ) {
                $_SESSION['flash']['danger'] = "Format du mot de passe invalide.<br>8 caractères minimum avec au minimum 1 majuscule, 1 minuscule et 1 chiffre.";
            } else {
                $mdp_utilisateur = password_hash($mdp_utilisateur_tp, PASSWORD_DEFAULT);
            }
        }
        // email
        if (empty( $_POST["email_utilisateur"]) ) {
            $_SESSION['flash']['danger'] = "Veuillez saisir votre adresse email.";
        } else {
            $email_utilisateur = test_input($_POST["email_utilisateur"]);
            if (!filter_var($email_utilisateur, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash']['danger'] = "Le format de l'adresse email est invalide.";
            } else {
                // REQ 1 ) Verif si l'email existe

                $email_utilisateur_tp = $this->findBy('email', $email_utilisateur);

                if ( !empty($email_utilisateur_tp) ) {
                    $_SESSION['flash']['danger'] = "Vous êtes déja inscrit. Connectez-vous : <a href='index.php?controller=utilisateur&task=connec'>ICI</a>";
                }
            }
        }
        // prenom
        if (empty($_POST["prenom_utilisateur"])) {
            $_SESSION['flash']['danger'] = "Veuillez saisir votre prenom.";
        } else {
            $prenom_utilisateur = mb_convert_case( test_input($_POST["prenom_utilisateur"]), MB_CASE_TITLE, 'UTF-8');
            if ( !preg_match('#^[\p{Latin}\' -]+$#u', $prenom_utilisateur) ) {
                $_SESSION['flash']['danger'] = "Format du prenom invalide.<br>Seul les caractères alphanumérique et les accents sont autorisés.";
            }
        }
        // nom
        if (empty($_POST["nom_utilisateur"])) {
            $_SESSION['flash']['danger'] = "Veuillez saisir votre nom.";
        } else {
            $nom_utilisateur = mb_strtoupper( test_input($_POST["nom_utilisateur"]), 'UTF-8');
        }
        
        // SI TOUT EST OK
        if ( empty($_SESSION['flash']) AND !empty($nom_utilisateur) AND !empty($prenom_utilisateur) AND !empty($email_utilisateur) AND !empty($mdp_utilisateur) ) {

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
                $_SESSION['flash']['danger'] = "Une erreur c'est produite lors de l'insertion dans la base de donnée. Veuillez contacter un administrateur.";
            }
        }

        if ( empty($_SESSION['flash']) ) {

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
            $headers = "From: christophe.sd@gmail.com \r\n";
            $headers .= "Reply-To: christophe.sd@gmail.com \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to, $subject, $message, $headers);

            // Redirection
            $_SESSION['flash']['success'] = "Inscription réussi ! Veuilliez valider votre compte par mail.";
            header('location:index.php?controller=utilisateur&task=connec');
        } else {
            header('location:index.php?controller=utilisateur&task=inscrip');
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
                $_SESSION['flash']['success'] = "Votre compte à bien été validé.";
                header('location:index.php');
                exit();
            }
        } elseif ( $user["token_{$this->_table}"] == NULL AND !empty($user["confirmat_{$this->_table}"]) ) {
            // Redirection
            if ( !empty($_SESSION["id_utilisateur"]) ) {
                $_SESSION['flash']['success'] = "Votre adresse email a déjà été validé.";
                header('location:index.php');
            } else {
                $_SESSION['flash']['success'] = "Votre adresse email a déjà été validé. Vous pouvez maintenant vous connecter.";
                header('location:index.php?controller=utilisateur&task=connec');
            }
            exit();
        } else {
            // Redirection
            $_SESSION['flash']['danger'] = "Le lien de validation est incorrecte.";
            header('location:index.php?controller=utilisateur&task=connec');
            exit();
        }
        
    }

    public function redirectIfConnec()
    {
        if ( !empty($_SESSION["id_utilisateur"]) ) 
        {
            $_SESSION['flash']['success'] = "Vous êtes déjà connecté.";
            header('location:index.php');
            exit();
        }
    }
}