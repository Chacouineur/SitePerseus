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
                <a class="navbar-brand" href="index.php" style="font-weight: bold;">
                    <img src="logo.png" alt="Logo" width="100" height="100" class="d-inline-block align-text-center">HOME
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="Pages/pageAjoutConfig.php">Ajouter Config</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageModifCSV.php">Modifier Fichiers</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageAjoutCSV.php">Ajouter Fichiers Etats</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageSuppCSV.php">Supprimer Fichiers Etats</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageSuppConfig.php">Supprimer Config</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageDeploiements.php">Déploiements</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="row">
        <div class="col-4">
                <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4 border-end scrollable-nav">
                    <nav class="nav nav-pills flex-column">
                        <a class="nav-link" href="#item-1">Ajouter Config</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-1-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-1-2">comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-2">Modifier Fichiers</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-2-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-2-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-3">Ajouter Fichiers Etats</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-3-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-3-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-4">Supprimer Fichiers Etats</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-4-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-4-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-5">Supprimer Config</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-5-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-5-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-6">Déploiement</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-6-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-6-2">Comment ?</a>
                        </nav>
                    </nav>
                </nav>
            </div>
            <div class="col-8">
                <div data-bs-spy="scroll" data-bs-target="#navbar-example3" data-bs-smooth-scroll="true" class="scrollspy-example-2" tabindex="0">
                    <div id="item-1">
                        <h4>Ajouter Config</h4>
                        <p>
                        </p>
                    </div>
                    <div id="item-1-1">
                        <h5>Pourquoi ?</h5>
                        <p></p>
                    </div>
                    <div id="item-1-2">
                        <h5>Comment ?</h5>
                        <p>Pour créer une configuration, il faut ajouter un nom ainsi que le nombre de carte voulu dans la configuration.</p>
                        <img src="Images/creerConfig1.png" alt="Image" height="150" width="300"> 
                        <p>Appuyez ensuite sur le bouton "Créer Tableau". Ce bouton permet de d'obtenir un tableau contenant les valeurs par défaut de la configuration.</p>
                        <img src="Images/creerConfig2.png" alt="Image" height="400" width="800">
                        <p>Pour changer ces valeurs, il faut selectionner une ligne puis remplacer dans "Nom des Cartes" le "#" par le nom voulu. Appuyez ensuite sur le bouton "Modifier" afin de modifier le nom de la carte.</p>
                        <img src="Images/creerConfig3.png" alt="Image" height="80" width="400">
                        <p>Une fois avoir indiqué le nom de toutes les cartes, cliquez sur le bouton "Ajouter Configuration" afin de créer la configuration et tous ces dossiers associés.</p>
                        <img src="Images/creerConfig4.png" alt="Image" height="400" width="800">
                        <p>Un message de confirmation de la création de la configuration apparait.</p>
                    </div>
                    <div id="item-2">
                        <h4>Modifier Fichiers</h4>
                        <p></p>
                    </div>
                    <div id="item-2-1">
                        <h5>Pourquoi ?</h5>
                        <p></p>
                    </div>
                    <div id="item-2-2">
                        <h5>Comment ?</h5>
                        <p>Pour modifier un fichier, il faut choisir une configuration créée plus tôt et appuyer sur "Selectionner Config".</p>
                        <p>Pour modifier le fichier CSV de la configuration physique des vannes, il faut choisir le fichier de la vanne voulue puis appuyer sur "Afficher". il faut ensuite selectionner une ligne puis remplacer dans "Nom des Cartes" le "#" par le nom voulu. </p>
                        <p></p>
                    </div>
                    <div id="item-3">
                        <h4>Ajouter Fichiers Etats</h4>
                        <p></p>
                    </div>
                    <div id="item-3-1">
                        <h5>Pourquoi ?</h5>
                        <p></p>
                    </div>
                    <div id="item-3-2">
                        <h5>Comment ?</h5>
                        <p></p>
                    </div>
                    <div id="item-4">
                        <h4>Supprimer Fichiers Etats</h4>
                        <p></p>
                    </div>
                    <div id="item-4-1">
                        <h5>Pourquoi ?</h5>
                        <p></p>
                    </div>
                    <div id="item-4-2">
                        <h5>Comment ?</h5>
                        <p></p>
                    </div>
                    <div id="item-5">
                        <h4>Supprimer Config</h4>
                        <p></p>
                    </div>
                    <div id="item-5-1">
                        <h5>Pourquoi ?</h5>
                        <p></p>
                    </div>
                    <div id="item-5-2">
                        <h5>Comment ?</h5>
                        <p></p>
                    </div>
                    <div id="item-6">
                        <h4>Déploiements</h4>
                        <p></p>
                    </div>
                    <div id="item-6-1">
                        <h5>Pourquoi ?</h5>
                        <p></p>
                    </div>
                    <div id="item-6-2">
                        <h5>Comment ?</h5>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </main>    
    <?php 
        unset($_SESSION['csvData']);
        unset($_SESSION['csvFileName']);
        unset($_SESSION['csvName']);
        unset($_SESSION['dataConfig']);
        unset($_SESSION['nomCOnfig']); 
        session_write_close();
    ?>
</body>
