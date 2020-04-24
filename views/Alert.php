<?php 

class Alert 
{

    public static function message()
    {
        if ( !empty($_SESSION['flash']) ) 
        {
            foreach ( $_SESSION['flash'] as $type => $message) 
            {?>
                <div class="alert alert-<?=$type?> alert-dismissible fade show" role="alert">

                    <i class='far fa-bell mr-3'></i><?=$message?>

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    
                </div>
            <?php }
            unset($_SESSION['flash']);
        }
    }

}