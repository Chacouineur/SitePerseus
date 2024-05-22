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
                        <li class="nav-item"><a class="nav-link" href="Pages/pageAjoutCSV.php">Ajouter Fichiers Etats</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageModifCSV.php">Modifier Fichiers</a></li>
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
                <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4 border-end">
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
                        <a class="nav-link" href="#item-3">Supprimer Fichiers Etats</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-3-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-3-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-3">Supprimer Config</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-3-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-3-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-3">Déploiement</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-3-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-3-2">Comment ?</a>
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
                        <p></p>
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
