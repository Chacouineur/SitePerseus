<?php 
    $titre = "Page Suppression CSV";
    $page = "../pages.css";
    require '../header.inc.php';
    include '../rechercheCSV.php';
    include '../rechercheConfig.php';
    session_start();
    unset($_SESSION['csvFileName']);
    unset($_SESSION['csvDataDeploiement']);
    unset($_SESSION['fileName']);
    unset($_SESSION['fileType']);
    unset($_SESSION['csvData2']);
    unset($_SESSION['statesFile']);
    unset($_SESSION['boards']);
    unset($_SESSION['configName2']);
    
    if(isset($_SESSION['csvName'])){
        $csvName = $_SESSION['csvName']; 
    } else {
        // Initialisez $csvFileName comme un tableau vide si la session n'a pas encore été définie
        $csvName = [];
    }
    if(isset($_SESSION['csvData'])){
        $csvData = $_SESSION['csvData'];
    } else {
        // Initialisez $csvFileName comme un tableau vide si la session n'a pas encore été définie
        $csvData = [];
    }
    if(isset($_SESSION['csvEG'])){
        $csvEGs = $_SESSION['csvEG'];
    }else{
        $csvEGs=[];
    }
    if(isset($_SESSION['configName'])){
        $configName = $_SESSION['configName'];
    }else{
        $configName=[];
    }
    
?>
<body>
    <?php include("headerPages.php"); ?>
    <main>
        <form method="post" action="../supprimerCSV.php" class="mx-auto p-5 rounded" id="ligne">
            <div class="row">
                <div class="col-4 mb-3 mr-5" id="formSelect">
                    <div class="mb-3">
                        <label for="config" class="form-label">Configuration :</label>
                        <div class="input-group mb-3">
                            <select class="form-select" name="config" id="config" placeholder="Selectionnez une configuration"required>
                                <?php
                                if (!empty($folders)) {
                                    foreach ($folders as $config) {
                                        $selected = ($config == $configName) ? 'selected' : '';  // Si le fichier correspond à $csvName, marquez-le comme sélectionné
                                        echo "<option value=\"$config\" $selected>$config</option>";
                                    }
                                }else{
                                    echo "<option value=\"\">Veuillez creer une configuration dans « Ajouter Config » </option>";
                                }
                                ?>
                            </select>
                            <button class="btn btn-primary" type="submit" name="btnValue" id="btnConfig" value="config">Selectionner Config</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="selectFile" class="form-label">Fichier CSV :</label>
                        <select class="form-select" name="FileName" id="selectFile" placeholder="Selectionnez un fichier">
                        <?php 
                        if (!empty($csvEGs)) {
                            foreach ($csvEGs as $csvEG) {
                                $selected = ($csvEG == $csvName) ? 'selected' : '';  // Si le fichier correspond à $csvName, marquez-le comme sélectionné
                                echo "<option value=\"$csvEG\" $selected>$csvEG</option>";
                            }
                        }else{
                            echo "<option value=\"\">Veuillez creer un fichier Etat dans « Ajouter Fichier » </option>";
                        }?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="btnValue" value="afficher" id="afficher">Afficher le fichier</button>
                    <button type="submit" class="btn btn-primary" name="btnValue" value="supprimer" id="supprimer" onclick="return confirmDelete();">Supprimer le fichier</button>
                    <?php
                        switch(true) {
                            case isset($_GET['erreurSuppr']):
                                echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la suppression du fichier !</div>";
                                break;
                            case isset($_GET['erreurFichierLiaison']):
                                echo "<div class=\"alert alert-danger\" role=\"alert\">Impossible de lire le fichier liaisonEGEtat.csv !</div>";
                                break;
                            case isset($_GET['erreurFichierEtat']):
                                echo "<div class=\"alert alert-danger\" role=\"alert\">Impossible de lire le fichier CSV d'états !</div>";
                                break;
                            case isset($_GET['reussiteSuppr']):
                                echo "<div class=\"alert alert-success\" role=\"alert\">Fichier d'états supprimé.</div>";
                                break;
                            default:
                                break;
                        }
                    ?>
                </div>
                <div class="col-4 mb-3 ml-5">
                    <?php if(!empty($csvName)){ ?>
                        <h4>Config : <?php echo $configName;?> | Fichier : <?php echo $csvName; ?> | Correspondance EG : <?php echo getCorrespondance($csvName, $configName); ?></h4>
                        <table class="tableau" id="myTable">
                        <thead>
                            <?php 
                            if(isset($csvData)) {
                                // Boucle à travers $csvData à partir de la deuxième ligne
                                echo "<tr>";
                                foreach ($csvData[0] as $cell) {
                                    echo "<th scope=\"col\">$cell</th>";
                                }
                                echo "</tr>";
                            }
                            ?>
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
                            else{
                                echo "\$csvData vide.";
                            }
                            ?>
                        </tbody>
                    </table><?php } ?>
                    
                </div>
            </div>
        </form>
        <script>
            function confirmDelete() {
                return confirm("Etes vous sur de vouloir supprimer cette configuration ?");
            }
            
            document.addEventListener('DOMContentLoaded', function () {
                var btnAfficher = document.getElementById("afficher");
                var btnSuppr = document.getElementById("supprimer");
                var fichier = document.getElementById('selectFile').value;
                
                btnAfficher.disabled = true;
                btnSuppr.disabled = true;
                
                if (fichier !== "") {
                        btnAfficher.disabled = false;
                        btnSuppr.disabled = false;
                    } 

                
            });
        </script>

    </main>
    
</body>
<?php session_write_close(); ?>