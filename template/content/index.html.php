<h1>Accueil</h1>



<?php if (!empty($_SESSION["id_utilisateur"])) {?>
    <p>Vous êtes connecté !</p>
<?php } else {?>
    <p>Vous n'êtes pas connecté.</p>
<?php } ?>