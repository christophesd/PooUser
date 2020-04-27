<div class="container mt-5">

    <?=Alert::general()?>
    <?=Alert::reset()?>
    
    <h1>Accueil</h1>



    <?php if (!empty($_SESSION["id_utilisateur"])) {?>
        <p>Bonjour <strong><?=$prenom?></strong> ! Vous êtes bien connecté !</p>
        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#exampleModalCenter">
            Supprimer mon compte
        </button>
    <?php } else {?>
        <p>Vous n'êtes pas connecté.</p>
    <?php } ?>

</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Voulez vous supprimer votre compte ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        L'intégralité de votre compte sera supprimé sans possibilité de retour en arrière. Nous serons dans l'incapacité de vous restitier vos données.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Revenir sur mon Compte</button>
        <button type="button" onClick="del()" class="btn btn-primary">Supprimer</button>
      </div>
    </div>
  </div>
</div>


<script>
    function del() {
        document.location.href="index.php?controller=utilisateur&task=del";
    }
</script>
