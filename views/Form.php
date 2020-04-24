<?php 


class Form
{
    
    public static function input($name, $table, $placeholder, $phrase=NULL, $type='text')
    {
        if ($phrase == NULL) { $phrase=$placeholder;}
        ?>
            
            <div class="form-group">
                <label for="<?=$name?>"><?=ucfirst($phrase)?> :</label>
                <input type="<?=$type?>" name="<?=$name?>_<?=$table?>" required class="form-control" id="<?=$name?>" <?=Data::name("{$name}_{$table}");?> placeholder="<?=ucfirst($placeholder)?>">
            </div>

        <?php 
    }

    
}