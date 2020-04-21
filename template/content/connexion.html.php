<?php

require_once('models/Utilisateur.php');
$user = new Utilisateur;


// $all = $user->findAll();
// foreach ($all as $row) {
//     echo $row['id_utilisateur'];
// }


// $id = $user->findBy('nom','TEST');
// var_dump($id);


// $user->insertInto([
//     'email_utilisateur' => 'email@email.com',
//     'prenom_utilisateur' => 'gerard',
//     'nom_utilisateur' => 'dupont',
//     'mdp_utilisateur' => 'test'
// ]);


// $user->deleteBy('id', 10);

?>

<div class="container mt-5">
    <div class="row">
        <div class="col-6 mx-auto">

            <!-- zone de connexion --> 
            <form action="" method="POST">

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