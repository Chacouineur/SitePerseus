<?php 
    $titre = "Page Suppression CSV";
    $page = "../pages.css";
    require '../header.inc.php';
    include '../rechercheCSV.php';
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
    <main>
        <form method="post" action="../supprimerCSV.php" class="mx-auto p-5 rounded" id="ligne">
            <div class="row">
                <div class="col-4 mb-3 mr-5">
                    <label for="selectFile" class="form-label">Fichier CSV :</label>
                    <select class="form-select" name="FileName" id="selectFile" placeholder="Selectionnez un fichier"required>
                        <?php
                        if (!empty($csvFiles)) {
                            foreach ($csvFiles as $file) {
                                echo "<option value=\"$file\">$file</option>";
                            }
                        }else{
                            echo "<option value=\"\">Veuillez creer un fichier dans « Ajouter Fichier » </option>";
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-primary" name="btnValue" value="afficher">Afficher le fichier</button>
                    <button type="submit" class="btn btn-primary" name="btnValue" value="supprimer">Supprimer le fichier</button>
                </div>
                <div class="col-4 mb-3 ml-5">
                    <table class="tableau" id="myTable">
                        <thead>
                            <tr>
                                <th scope="col">Carte</th>
                                <th scope="col">Vannes/Etat</th>
                                <th scope="col">Valeur</th>
                                <th scope="col">Timer dépendance</th>
                                <th scope="col">Dépendance vannes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Vérifiez si $csvData est défini avant d'utiliser la boucle foreach
                            if(isset($csvData)) {
                                // Boucle à travers $csvData à partir de la deuxième ligne
                                for($i = 1; $i < count($csvData); $i++) {
                                    echo "<tr>";
                                    foreach ($csvData[$i] as $cell) {
                                        echo "<td>$cell</td>";
                                    }
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
        
    </main>
</body>