<div class="container mt-5">
    <div class="row">
        <div class="col-8 mx-auto">

            <!-- zone de inscription --> 
            <form action="index.php?controller=utilisateur&task=tinscrip" method="POST">
                <?php if (!empty($Err)) {?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?=$Err?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php } ?>

                <div class="form-group">
                    <label for="nom">Nom de l'utilisateur</label>
                    <input type="text" name="nom_utilisateur" required class="form-control" id="nom"  placeholder="Nicolas">
                </div>

                <div class="form-group">
                    <label for="prenom">Prenom de l'utilisateur</label>
                    <input type="text" name="prenom_utilisateur" required class="form-control" id="prenom"  placeholder="Dupond">
                </div>

                <div class="form-group">
                    <label for="email">Email de l'utilisateur</label>
                    <input type="email" name="email_utilisateur" required class="form-control" id="email"  placeholder="name@example.com" aria-describedby="emailHelp">
                    <small id="emailHelp" class="form-text text-muted">Nous ne partagerons pas votre email.</small>
                </div>

                <div class="form-group">
                    <label for="mdp">Mot de passe</label>
                    <input type="password"  name="mdp_utilisateur" required class="form-control" id="mdp">
                </div>

                <button type="submit" class="btn btn-primary">Inscription</button>

            </form>

        </div>
    </div>
</div>