<div class="container mt-5">
    <div class="row">
        <div class="col-10 col-md-7 mx-auto">

            <!-- zone mot de pass oublie --> 
            <form action="index.php?controller=utilisateur&task=toublie" method="POST">

                <div class="form-group text-center mb-4">
                    <h3>Mot de passe oublié :</h3>
                    <p class="mt-4"><small>Nous allons vous envoyer un email de validation pour redéfinir votre mot de passe.</small></p>
                </div>

                <?=Alert::message();?>

                <?=Form::input('email', 'utilisateur', 'exemple@gmail.com', 'email de l\'utilisateur')?>

                <button type="submit" class="btn btn-primary">Validation</button>

            </form>

        </div>
    </div>
</div>
