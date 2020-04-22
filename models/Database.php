<?php 

abstract class Database {

    
    protected $_pdo;

    public function __construct() {

        try {

            $bdd = "mysql:host=localhost:3306;dbname=test";
            $user = "root";
            $pass = "rootroot";
            
            // Je me connecte à ma bdd
            $pdo = new PDO($bdd, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->_pdo = $pdo;
        } catch(Exception $e) {
            // En cas d'erreur, un message s'affiche et tout s'arrête
            die('Erreur : '.$e->getMessage());
        }

    }


}