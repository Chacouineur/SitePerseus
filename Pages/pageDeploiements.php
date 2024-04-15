<?php 
    $titre = "Page Deploiements";
    $page = "../pages.css";
    require '../header.inc.php';
    session_start();
?>
<body>
    <header>
        <nav class="navbar navbar-expand-lg" id="nav">
            <div class="container-fluid">
                <!-- Bouton de logo à gauche -->
                <a class="navbar-brand" href="../index.php">
                    <img src="../logo.png" alt="Logo" width="100" height="100" class="d-inline-block align-text-center">HOME
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                        <li class="nav-item ">
                            <a class="nav-link" href="pageAjoutCSV.php">Ajouter Fichier</a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="pageModifCSV.php">Modifier Fichier</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="pageSuppCSV.php">Supprimer Fichier</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="pageDeploiements.php">Déploiements</a>
                        </li>
                        
                    </ul>

                </div>
            </div>
        </nav>

    </header>
</body>