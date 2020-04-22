<div class="container mt-5">
    <div class="row">
        <div class="col-6 mx-auto">

            <!-- zone de connexion --> 
            <form action="index.php?controller=utilisateur&task=tconnec" method="POST">
                <?php if (!empty($Err)) {?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?=$Err?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="email">Email d'utilisateur</label>
                    <input type="email" name="email_utilisateur" required class="form-control" id="email"  placeholder="name@example.com" aria-describedby="emailHelp">
                    <small id="emailHelp" class="form-text text-muted">Nous ne partagerons pas votre email.</small>
                </div>

                <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password"  name="mdp_utilisateur" required class="form-control" id="mdp">
                </div>

                <button type="submit" class="btn btn-primary">Connexion</button>

            </form>

        </div>
    </div>
</div>