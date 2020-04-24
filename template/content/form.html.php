<div class="container mt-5">
    <div class="row">
        <div class="col-10 col-md-7 mx-auto">

            <form action="index.php?controller=<?=$controller?>&task=<?=$task?><?=$get?>" method="POST">

                <div class="form-group text-center">
                    <h3><?=ucfirst($title)?> :</h3>
                    <p><small><?=ucfirst($p)?></small></p>
                </div>

                <?=Alert::message()?>

                <?=$inputs?>

                <button type="submit" class="btn btn-primary"><?=ucfirst($btm)?></button>

            </form>

        </div>
    </div>
</div>
