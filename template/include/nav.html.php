<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">POO</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <li class="nav-item <?php if( empty($_GET['controller']) AND empty($_GET['task']) ) { echo 'active';}?>">
                <a class="nav-link" href="index.php">Accueil</a>
            </li>

            <li class="nav-item <?php if( $_GET['controller'] == 'utilisateur' AND $_GET['task'] == 'connec' ) { echo 'active';}?>">
                <!-- session -->
                <?php if (!empty($_SESSION["id_utilisateur"])) {?>
                    <a class="nav-link" href="index.php?controller=utilisateur&task=deco">Deconnexion</a>
                <?php } else {?>
                    <a class="nav-link" href="index.php?controller=utilisateur&task=connec">Connexion</a>
                <?php } ?>
            </li>

            <?php if (empty($_SESSION["id_utilisateur"])) {?>
                <li class="nav-item <?php if( $_GET['controller'] == 'utilisateur' AND $_GET['task'] == 'inscrip' ) { echo 'active';}?>">
                    <a class="nav-link" href="index.php?controller=utilisateur&task=inscrip">Inscription</a>
                </li>
            <?php } ?>

        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
    </div>
</nav>