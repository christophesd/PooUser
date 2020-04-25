<?php 


class Form
{
    
    public static function input($name, $table, $placeholder, $phrase=NULL, $type='text')
    {
        if ($phrase == NULL) { $phrase=$placeholder;}
        ?>
            
            <div class="form-group">

                <label for="<?=$name?>"><?=ucfirst($phrase)?> :</label>
                <input type="<?=$type?>" name="<?=$name?>_<?=$table?>" required id="<?=$name?>" <?=Data::name("{$name}_{$table}");?> <?=Alert::persoValid($name)?> class="form-control" placeholder="<?=ucfirst($placeholder)?>">
                <?=Alert::perso($name)?>
                
            </div>

        <?php 
    }

    
}