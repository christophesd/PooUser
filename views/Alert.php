<?php 

class Alert 
{
    private static $_err = true;

    public static function getErr()
    {
        return self::$_err;
    }

    public static function setPerso($name , $message, $type = "danger")
    {
        $_SESSION['flash']['perso'][$name][$type] = $message;
        if ($type == 'danger')
        {
            self::$_err = false;
        }
    }

    public static function setGeneral($message, $type = "danger")
    {
        $_SESSION['flash']['general'][$type] = $message;
        if ($type == 'danger')
        {
            self::$_err = false;
        }
    }
    


    public static function general()
    {
        if ( !empty($_SESSION['flash']['general']) ) 
        {
            foreach ( $_SESSION['flash']['general'] as $type => $message) 
            {?>
                <div class="alert alert-<?=$type?> alert-dismissible fade show" role="alert">

                    <i class='far fa-bell mr-3'></i><?=$message?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    
                </div>
            <?php }
        }
    }

    public static function perso($name)
    {
        if ( !empty($_SESSION['flash']['perso'][$name]) ) 
        {
            foreach ( $_SESSION['flash']['perso'][$name] as $type => $message) 
            {
                if ($type == 'danger') {
                ?>
                    <div class="invalid-feedback">
                        <?=$message?>
                    </div>
                <?php }
                if ($type == 'success') {
                ?>
                    <div class="valid-feedback">
                        <?=$message?>
                    </div>
                <?php }
            }
        }
    }

    public static function persoValid($name)
    {
        if ( !empty($_SESSION['flash']['perso'][$name]) ) 
        {
            foreach ( $_SESSION['flash']['perso'][$name] as $type => $message) 
            {
                if ($type == 'danger') {
                    echo " class='form-control is-invalid' ";
                }
                if ($type == 'success') {
                    echo " class='form-control is-valid' ";
                }
            }
        }
    }

    public static function reset()
    {
        unset($_SESSION['flash']);
    }

    public static function resetPerso()
    {
        unset($_SESSION['flash']['perso']);
    }
}