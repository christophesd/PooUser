<?php 

abstract class Model {

    
    protected $_table;
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


    public function findAll() 
    {
        $sql = "SELECT * 
                FROM {$this->_table}";
        $req = $this->_pdo->prepare($sql);
        $req->execute();
        $items = $req->fetchAll();
        $req->closeCursor();
        return $items;
    }

    public function findBy($filter, $id) 
    {
        $sql = "SELECT * 
                FROM {$this->_table} 
                WHERE {$filter}_{$this->_table} = :id";
        $req = $this->_pdo->prepare($sql);
        $req->execute(compact('id'));
        $item = $req->fetch();
        $req->closeCursor();
        return $item;
    }

    public function deleteBy($filter, $id) 
    {
        $sql = "DELETE  
                FROM {$this->_table} 
                WHERE {$filter}_{$this->_table} = :id";
        $req = $this->_pdo->prepare($sql);
        $req->execute(compact('id'));
        $req->closeCursor();
    }

    public function insertInto($data) 
    {
        $sql = 'INSERT INTO '.$this->_table.' (';
        foreach($data as $k=>$v)
        {
            $sql .= ''.$k.','; 
        }
            $sql = substr($sql,0,-1);
            $sql .= ') VALUES (';
        foreach($data as $v)
        {
            $sql .= '"'.$v.'",';
        }
            $sql = substr($sql,0,-1);
            $sql .= ')';	
        
        $req = $this->_pdo->prepare($sql);
        $req->execute();
        $req->closeCursor();
        return $item;
    }




}