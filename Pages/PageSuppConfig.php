<?php 
    $titre = "Page Suppression CSV";
    $page = "../pages.css";
    require '../header.inc.php';
    include '../rechercheConfig.php';

    session_start();
    unset($_SESSION['csvFileName']);
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
                            <a class="nav-link" href="pageAjoutConfig.php">Ajouter Config</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="pageAjoutCSV.php">Ajouter Fichiers Etats</a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="pageModifCSV.php">Modifier Fichiers</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="pageSuppCSV.php">Supprimer Fichiers Etats</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="pageSuppConfig.php" style="font-weight: bold;">Supprimer Config</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="pageDeploiements.php">Déploiements</a>
                        </li>
                        
                    </ul>

                </div>
            </div>
        </nav>
    </header>
    <main>
        <form method="post" action="../supprimerConfig.php" class="mx-auto p-5 rounded" id="ligne">
                
            <div class="mb-3">
                <label for="config" class="form-label">Configuration :</label>
                
                <select class="form-select" name="config" id="config" placeholder="Selectionnez une configuration"required>
                    <?php
                    if (!empty($folders)) {
                        foreach ($folders as $config) {
                            echo "<option value=\"$config\">$config</option>";
                        }
                    }else{
                        echo "<option value=\"\">Veuillez creer une configuration dans « Ajouter Config » </option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="btnValue" value="supprimer" id="supprimer" onclick="return confirmDelete();">Supprimer la config</button>

        </form>
        <?php 
            switch(true) {
                case isset($_GET['erreurFichierConfigurations']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, impossible de lire le fichier configurations.csv.</div>";
                    break;
                case isset($_GET['erreurEcritureConfig']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, impossible d'écrire dans le fichier configurations.csv.</div>";
                    break;
                case isset($_GET['erreurDossierSuppr']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, impossible de supprimer le dossier.</div>";
                    break;
                case isset($_GET['erreurDossier']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le dossier spécifié n'existe pas.</div>";
                    break;
                case isset($_GET['reussiteSuppr']):
                    echo "<div class=\"alert alert-success\" role=\"alert\">Le dossier et sa configuration ont été supprimés avec succès.</div>";
                    break;
                default:
                    break;
            }
        ?>

        <script>
            function confirmDelete() {
                return confirm("Etes vous sur de vouloir supprimer cette configuration ?");
            }
            document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('config');
            const submitButton = document.getElementById('supprimer');

            // Fonction pour activer/désactiver le bouton en fonction de la valeur du select
            function toggleButton() {
                if (selectElement.value === "") {
                    submitButton.disabled = true;
                } else {
                    submitButton.disabled = false;
                }
            }

            // Initialiser le bouton à l'état désactivé
            toggleButton();

            // Ajouter un écouteur d'événement pour surveiller les changements de valeur du select
            selectElement.addEventListener('change', toggleButton);
        });
        </script>

    </main>
    </main>
</body>