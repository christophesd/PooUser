<?php

class Validation
{

    //nom/prenom
    public static function verifName($name)
    {
        if (empty($_POST["{$name}_utilisateur"])) {
            Alert::setPerso($name, "Veuillez saisir votre $name.");
        } else {
            $name_tp =  htmlentities($_POST["{$name}_utilisateur"], ENT_QUOTES, "UTF-8");
            $name_tp = mb_convert_case($name_tp, MB_CASE_TITLE, 'UTF-8');
            if ( !preg_match('#^[\p{Latin}\' -]+$#u', $name_tp) ) {
                Alert::setPerso($name, "Format du $name invalide. Seul les lettres sont autorisés.");
            } else {
                Alert::setPerso($name, '', 'success');
            }
            return $name_tp;
        }
    }

    //email
    public static function verifEmail( $same = NULL )
    {
        if (empty($_POST["email_utilisateur"])) {
            Alert::setPerso('email', 'Veuillez saisir un email.');
        } else {
            $email_utilisateur = htmlentities($_POST["email_utilisateur"], ENT_QUOTES, "UTF-8");
            if (!filter_var($email_utilisateur, FILTER_VALIDATE_EMAIL)) {
                Alert::setPerso('email', "Le format de l'email est invalide.");
            } else {
                Alert::setPerso('email', '', 'success');
            }
            return $email_utilisateur;
        }
    }

    
}