<?php 

abstract class Database {

    
    protected $_pdo;

    public function __construct() {

        try {

            // $bdd = "mysql:host=localhost:3306;dbname=test";
            // $user = "root";
            // $pass = "rootroot";

            $bdd = "mysql:host=db5000303652.hosting-data.io;dbname=dbs296639";
            $user = "dbu526615";
            $pass = "5(9|zhXJ";
            
            // Je me connecte à ma bdd
            $pdo = new PDO($bdd, $user, $pass, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $this->_pdo = $pdo;
        } catch(Exception $e) {
            // En cas d'erreur, un message s'affiche et tout s'arrête
            die('Erreur : '.$e->getMessage());
        }

    }


}