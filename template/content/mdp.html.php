<div class="container mt-5">
    <div class="row">
        <div class="col-10 col-md-7 mx-auto">

            <!-- zone changement de mot de passe --> 
            <form action="index.php?controller=utilisateur&task=mdpt&id=<?=$id?>&token=<?=$token?>" method="POST">

                <div class="form-group text-center mb-4">
                    <h3>Mot de passe oublié :</h3>
                    <p class="mt-4"><small>Veuillez saisir un nouveau mot de passe.</small></p>
                </div>

                <?=Alert::message();?>

                <?=Form::input('mdp', 'utilisateur', 'nouveau mot de passe', '', 'password')?>
                <?=Form::input('mdp2', 'utilisateur', 'nouveau mot de passe', 'confirmation du mot de passe', 'password')?>

                <button type="submit" class="btn btn-primary">Validation</button>

            </form>

        </div>
    </div>
</div>
