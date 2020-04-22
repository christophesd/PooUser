<?php 

require_once('Database.php');

abstract class Model extends Database {

    
    protected $_table;


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

    public function updateInto($data, $filter, $id)
    {
        $sql = "UPDATE {$this->_table} SET ";
        foreach($data as $k=>$v)
        {
            $sql .= ' '.$k.' = '.$v.','; 
        }
        $sql = substr($sql,0,-1);
        $sql .= " WHERE {$filter}_{$this->_table} = :id ";
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
    }




}