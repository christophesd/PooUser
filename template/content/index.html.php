<div class="container mt-5">

    <?=Alert::general();?>
    <?=Alert::reset();?>
    
    <h1>Accueil</h1>



    <?php if (!empty($_SESSION["id_utilisateur"])) {?>
        <p>Bonjour <strong><?=$prenom?></strong> ! Vous êtes bien connecté !</p>
        <button onClick="del()" type="button" class="btn btn-outline-danger">Supprimer mon compte</button>
    <?php } else {?>
        <p>Vous n'êtes pas connecté.</p>
    <?php } ?>

</div>


<script>
    function del() {
        if(confirm("Voulez vous vraiment supprimer votre compte ?"))
        {
            document.location.href="index.php?controller=utilisateur&task=del";
        }
    }
</script>
