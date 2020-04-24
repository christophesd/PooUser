<div class="container mt-5">
    <div class="row">
        <div class="col-10 col-md-7 mx-auto">

            <!-- zone de inscription --> 
            <form action="index.php?controller=utilisateur&task=tinscrip" method="POST">

                <div class="form-group text-center">
                    <h3>Inscription :</h3>
                </div>

                <?=Alert::message();?>

                <?=Form::input('nom', 'utilisateur', 'dupont', 'nom de l\'utilisateur')?>
                <?=Form::input('prenom', 'utilisateur', 'nicolas', 'prenom de l\'utilisateur')?>
                <?=Form::input('email', 'utilisateur', 'exemple@gmail.com', 'email de l\'utilisateur')?>
                <?=Form::input('mdp', 'utilisateur', 'mot de passe', '', 'password')?>

                <button type="submit" class="btn btn-primary">S'inscrire</button>

            </form>

        </div>
    </div>
</div>