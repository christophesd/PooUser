<?php 

class Data 
{

    public static function name($name)
    {
        if ( !empty($_SESSION['data'][$name]) ) 
        {
            //value
            echo " value='".$_SESSION['data'][$name]."' ";
            unset($_SESSION['data'][$name]);
        }

    }

}