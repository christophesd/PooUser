<?php 

require_once('./controllers/UtilisateurC.php');

class Application 
{
    public static function process() 
    {
        $controllerName = "Utilisateur";
        $taskName = "index";

        if (!empty($_GET['controller'])) 
        {
            $controllerName = ucfirst($_GET['controller']);
        }
        if (!empty($_GET['task'])) 
        {
            $taskName = $_GET['task'];
        }

        $controllerName =  $controllerName."C";

        $controller = new $controllerName;
        $controller->$taskName();


    }


}