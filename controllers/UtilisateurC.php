<?php 

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

        // Var du formulaire
        $controller = 'utilisateur';
        $task = 'tconnec';
        $title = 'connexion';
        $btm = 'connexion';

        // Inputs du formulaire
        ob_start();
        Form::input('email', 'utilisateur', 'exemple@gmail.com', 'email de l\'utilisateur');
        Form::input('mdp', 'utilisateur', 'mot de passe', 'mot de passe <small>(<a href="index.php?controller=utilisateur&task=oublie">Vous avez oublié votre mot de passe ?</a>)</small>', 'password');
        $inputs = ob_get_clean();

        // Affichage
        $pageTitle = 'Connexion';
        Renderer::render('form', compact('pageTitle', 'controller', 'task', 'title', 'btm', 'inputs'));
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
            $user = $this->findBy('email',$email_utilisateur);
            if ( $user["email_{$this->_table}"] == $email_utilisateur ) {
                $error = false;
                if ( password_verify($mdp_utilisateur, $user["mdp_{$this->_table}"]) ) {
                    if ( !empty($user["confirmat_{$this->_table}"]) ) {
                        $id_utilisateur = $user["id_{$this->_table}"];
                    } else {
                        $_SESSION['flash']['danger'] = "Vous n'avez pas validé votre adresse email.";
                    }
                } else {
                    $_SESSION['flash']['danger'] = "Votre mot de passe est incorrect.";
                }
            } else {
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
            $_SESSION['data'] = compact('email_utilisateur');
            header('location:index.php?controller=utilisateur&task=connec');
            exit();
        }

    }

    public function deco()
    {
        unset($_SESSION['id_utilisateur']);
        $_SESSION['flash']['success'] = "Une bonne journée à vous et à bientôt !<i class='far fa-smile-wink ml-3'></i>";
        header('Location:index.php');
        exit();
    }

    public function inscrip() 
    {   
        // Redirect index si déjà connecté
        $this->redirectIfConnec();

        // Var du formulaire
        $controller = 'utilisateur';
        $task = 'tinscrip';
        $title = 'inscription';
        $btm = 's\'inscrire';

        // Inputs du formulaire
        ob_start();
        Form::input('nom', 'utilisateur', 'dupont', 'nom de l\'utilisateur');
        Form::input('prenom', 'utilisateur', 'nicolas', 'prenom de l\'utilisateur');
        Form::input('email', 'utilisateur', 'exemple@gmail.com', 'email de l\'utilisateur');
        Form::input('mdp', 'utilisateur', 'mot de passe', '', 'password');
        $inputs = ob_get_clean();

        // Affichage
        $pageTitle = 'Inscription';
        Renderer::render('form', compact('pageTitle', 'controller', 'task', 'title', 'btm', 'inputs'));

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
            $_SESSION['data'] = compact('nom_utilisateur', 'prenom_utilisateur', 'email_utilisateur');
            header('location:index.php?controller=utilisateur&task=inscrip');
        }

    }

    public function oublie() 
    {
        // Redirect index si déjà connecté
        $this->redirectIfConnec();

        // Var du formulaire
        $controller = 'utilisateur';
        $task = 'toublie';
        $title = 'mot de passe oublié';
        $btm = 'validation';
        $p = 'nous allons vous envoyer un email de validation pour redéfinir votre mot de passe.';

        // Inputs du formulaire
        ob_start();
        Form::input('email', 'utilisateur', 'exemple@gmail.com', 'email de l\'utilisateur');
        $inputs = ob_get_clean();

        // Affichage
        $pageTitle = 'Mot de passe oublié';
        Renderer::render('form', compact('pageTitle', 'controller', 'task', 'title', 'btm', 'p', 'inputs'));
    }

    public function toublie() 
    {
        // Redirect index si déjà connecté
        $this->redirectIfConnec();

        // Init var
        $email_utilisateur = $id_utilisateur = "";

        // Fontion test
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Vérification des champs
        // email
        if (empty( $_POST["email_utilisateur"]) ) {
            $_SESSION['flash']['danger'] = "Veuillez saisir votre adresse email.";
        } else {
            $email_utilisateur = test_input($_POST["email_utilisateur"]);
            if (!filter_var($email_utilisateur, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['flash']['danger'] = "Le format de l'adresse email est invalide.";
            } else {
                // Vérif utilisateur
                $user = $this->findBy('email', $email_utilisateur);
                if ( empty( $user["email_{$this->_table}"] ) ) {
                    $_SESSION['flash']['danger'] = "Nous n'avons pas trouvé l'adresse email.";
                }
            }
        }


        // SI TOUT EST OK
        if ( empty($_SESSION['flash']) AND !empty($email_utilisateur) ) {

            // Création du token 
            $token_utilisateur = bin2hex( openssl_random_pseudo_bytes(20) );
            
            // Update token et confirmat
            $this->updateInto([
                "token_{$this->_table}" => $token_utilisateur
            ], 'id', $user["id_{$this->_table}"] );


            // Envoie du token par mail 
            $to      = $user["email_{$this->_table}"];
            $subject = 'Lien de réinitialisation de votre mot de passe.';
            $message = '
             <html>
                <head>
                    <title>Site.fr : Lien de réinitialisation de votre mot de passe.</title>
                </head>
                <body>
                    <h3>Lien de réinitialisation de votre mot de passe :</h3>
                    <p>Pour réinitialisation de votre mot de passe veuiller cliquer sur ce lien : <a href="https://saidoun.simplon-charleville.fr/connec/index.php?controller=utilisateur&task=mdp&id='.$user["id_{$this->_table}"].'&token='.$token_utilisateur.'" target="_blank">LIEN DE VALIDATION</a></p>
                </body>
            </html>
            ';
            $headers = "From: christophe.sd@gmail.com \r\n";
            $headers .= "Reply-To: christophe.sd@gmail.com \r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
            mail($to, $subject, $message, $headers);

            // Redirection
            $_SESSION['flash']['success'] = "Email envoyé ! Veuilliez valider votre nouveau mot de passe.";
            header('location:index.php?controller=utilisateur&task=oublie');
        } else {
            $_SESSION['data'] = compact('email_utilisateur');
            header('location:index.php?controller=utilisateur&task=oublie');
        }
    }

    public function mdp()
    {
        // Redirect index si déjà connecté
        $this->redirectIfConnec();

        // Redirect si token faux
        $id = htmlentities($_GET['id']);
        $token = htmlentities($_GET['token']);
        $this->redirectIfBadToken($id, $token);

        // Var du formulaire
        $controller = 'utilisateur';
        $task = 'mdpt';
        $get = '&id='.$id.'&token='.$token;
        $title = 'mot de passe oublié';
        $btm = 'validation';
        $p = 'veuillez saisir un nouveau mot de passe.';

        // Inputs du formulaire
        ob_start();
        Form::input('mdp', 'utilisateur', 'nouveau mot de passe', '', 'password');
        Form::input('mdp2', 'utilisateur', 'nouveau mot de passe', 'confirmation du mot de passe', 'password');
        $inputs = ob_get_clean();

        // Affichage
        $pageTitle = 'Mot de passe oublié';
        Renderer::render('form', compact('pageTitle', 'controller', 'task', 'get', 'title', 'btm', 'p', 'inputs'));
    }

    public function mdpt()
    {
        // Redirect index si déjà connecté
        $this->redirectIfConnec();

        // Redirect si token faux
        $id = htmlentities($_GET['id']);
        $token = htmlentities($_GET['token']);
        $this->redirectIfBadToken($id, $token);

        // Init var
        $mdp_utilisateur = $id_utilisateur = "";

        // Fontion test
        function test_input($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        // Vérification champs
        if ( empty($_POST["mdp_utilisateur"]) || empty($_POST["mdp2_utilisateur"]) ) {
            $_SESSION['flash']['danger'] = "Veuillez saisir tous les champs.";
        } else {
            if ( $_POST["mdp_utilisateur"] != $_POST["mdp2_utilisateur"] ) {
                $_SESSION['flash']['danger'] = "Veuillez saisir les mêmes mots de passe.";
            } else {
                $mdp_utilisateur_tp = test_input($_POST["mdp_utilisateur"]);
                if ( !preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$#', $mdp_utilisateur_tp) ) {
                    $_SESSION['flash']['danger'] = "Format du mot de passe invalide.<br>8 caractères minimum avec au minimum 1 majuscule, 1 minuscule et 1 chiffre.";
                } else {
                    $mdp_utilisateur = password_hash($mdp_utilisateur_tp, PASSWORD_DEFAULT);
                }
            }
        }


        // SI TOUT EST OK
        if ( empty($_SESSION['flash']) AND !empty($mdp_utilisateur) ) {

            // Update mdp, token et confirmat
            $this->updateInto([
                "mdp_{$this->_table}" => $mdp_utilisateur,
                "token_{$this->_table}" => NULL,
                "confirmat_{$this->_table}" => date("Y-m-d H:i:s")
            ], 'id', $user["id_{$this->_table}"] );

            // Redirection
            $_SESSION['flash']['success'] = "Votre mot de passe à bien été réinitialisé.";
            header('location:index.php?controller=utilisateur&task=connec');
            exit();

        } else {
            header('location:index.php?controller=utilisateur&task=mdp&id='.$id.'&token='.$token.'');
            exit();
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
                "token_{$this->_table}" => NULL,
                "confirmat_{$this->_table}" => date("Y-m-d H:i:s")
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
        } elseif ( !empty($user["confirmat_{$this->_table}"]) ) {
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

    public function redirectIfBadToken($id, $token)
    {
        $user = $this->findBy('id', $id);
        if ( empty($user) ||
            $token != $user["token_{$this->_table}"] ||
            empty($user["confirmat_{$this->_table}"]) ) 
        {
            $_SESSION['flash']['success'] = "Vous ne pouvez pas accèder à cette page.";
            header('location:index.php');
            exit();
        }
    }
}