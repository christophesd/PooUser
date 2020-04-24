<div class="container mt-5">
    <div class="row">
        <div class="col-10 col-md-7 mx-auto">

            <!-- zone de connexion --> 
            <form action="index.php?controller=utilisateur&task=tconnec" method="POST">

                <div class="form-group text-center">
                    <h3>Connexion :</h3>
                </div>

                <?=Alert::message();?>

                <?=Form::input('email', 'utilisateur', 'exemple@gmail.com', 'email de l\'utilisateur')?>
                <?=Form::input('mdp', 'utilisateur', 'mot de passe', 'mot de passe <small>(<a href="index.php?controller=utilisateur&task=oublie">Vous avez oublié votre mot de passe ?</a>)</small>', 'password')?>

                <button type="submit" class="btn btn-primary">Connexion</button>

            </form>

        </div>
    </div>
</div>
