<?php 
    $titre = "Page D'accueil";
    $page = "style.css";
    require 'header.inc.php';
    session_start();
?>

<body>
    <header>
        <nav class="navbar navbar-expand-lg" id="nav">
            <div class="container-fluid">
                <!-- Bouton de logo à gauche -->
                <a class="navbar-brand" href="index.php" style="font-weight: bold;">
                    <img src="logo.png" alt="Logo" width="100" height="100" class="d-inline-block align-text-center" >HOME
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item ">
                            <a class="nav-link" href="Pages/pageAjoutConfig.php">Ajouter Config</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="Pages/pageAjoutCSV.php" >Ajouter Fichiers Etats</a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="Pages/pageModifCSV.php" >Modifier Fichiers</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="Pages/pageSuppCSV.php" >Supprimer Fichiers Etats</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="Pages/pageSuppConfig.php" >Supprimer Config</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="Pages/pageDeploiements.php" >Déploiements</a>
                        </li>
                        
                    </ul>

                </div>
            </div>
        </nav>

    </header>
    
</body>

<?php unset($_SESSION['csvData']);
unset($_SESSION['csvFileName']);
unset($_SESSION['csvName']);
unset($_SESSION['dataConfig']);
unset($_SESSION['nomCOnfig']); 
session_write_close();?>