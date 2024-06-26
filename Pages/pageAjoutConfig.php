<?php 
    $titre = "Page Ajout Config";
    $page = "../pages.css";
    require '../header.inc.php';
    session_start();
    unset($_SESSION['csvName']);
    unset($_SESSION['csvData']);
    unset($_SESSION['csvFileName']);
    unset($_SESSION['csvDataDeploiement']);
    unset($_SESSION['configName']);
    unset($_SESSION['fileName']);
    unset($_SESSION['fileType']);
    unset($_SESSION['csvData2']);
    unset($_SESSION['statesFile']);
    unset($_SESSION['boards']);
    unset($_SESSION['csvEG']);
    unset($_SESSION['configName2']);

    if(isset($_SESSION['dataConfig'])){
        $csvData = $_SESSION['dataConfig'] ;
    }else{
        $csvData=[];
    }
    if(isset($_SESSION['nomConfig'])){
        $nomConfig = $_SESSION['nomConfig'];
    }else{
        $nomConfig = [];
    }
        
?>

<body>
    <?php include("headerPages.php"); ?>
    <main>
        <form method="post" action="../ajoutConfig.php" class="mx-auto p-5 rounded" >
            
            <div class="mb-3" id="config">
                <label for="code" class="form-label">Nom de la configuration :</label>
                <input type="text" class="form-control" name="config" id="config" placeholder="Configuration *">
            </div>
            
            <div class="mb-3 " id="nb">
                <label for="nom" class="form-label">Nombre de cartes :</label>
                <div class="input-group mb-3">
                    <input type="number" min="1" max="239"class="form-control" name="nbCartes" id="nbCartes"  placeholder="min: 1 | max: 239">
                    <button class="btn btn-primary" type="submit" id="creerTab" name="btnValue" value="creerTab">Créer Tableau</button>
                </div>
            </div>

            <div class="mb-3">
                <label for="nomCarte" class="form-label">Nom des cartes :</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="nom*" aria-label="nomCarte" name="nomCarte" id="nomCarte" >
                    <button class="btn btn-primary" type="submit" name="btnValue" id="ajoutNom" value="modif">Modifier</button>
                </div>
                <input type="hidden" class="form-control" placeholder="ligneIndex" name="ligneIndex" id="ligneIndex">
            </div>
            <button type="submit" class="btn btn-primary" name="btnValue" id="btnAddConfig" value="ajoutConfig">Ajouter Configuration</button>
        </form>
        <?php 
        switch(true) {
            case isset($_GET['erreurNomsCartes']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, tous les noms des cartes n'ont pas été renseignés dans le tableau !</div>";
                break;
            case isset($_GET['erreurNomExiste']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, une configuration ayant le même nom existe déjà !</div>";
                break;
            case isset($_GET['erreurConfigurationCSVecriture']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier CSV 'configurations.csv' n'est pas accessible en écriture !</div>";
                break;
            case isset($_GET['erreurDossierConfigExiste']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le dossier de la configuration entrée existe déjà !</div>";
                break;
            case isset($_GET['erreurConfigExiste']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, la configuration entrée existe déjà dans le fichier CSV 'configurations.csv' !</div>";
                break;
            case isset($_GET['erreurNonTrouve']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, configuration non trouvée dans le fichier CSV 'configurations.csv', modification impossible !</div>";
                break;
            case isset($_GET['erreurChampsNonRenseignes']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, champs nom de configuration et/ou nombre de cartes non renseignés !</div>";
                break;
            case isset($_GET['erreurLigneNonCliquee']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, aucune ligne du tableau cliquée, modification impossible !</div>";
                break;
            case isset($_GET['reussiteCreerTab']):
                echo "<div class=\"alert alert-success\" role=\"alert\">Tableau des noms des cartes créé, veuillez selectionner et entrer le nom de chaque carte. </div>";
                break;
            case isset($_GET['reussiteCreerConfig']):
                echo "<div class=\"alert alert-success\" role=\"alert\">Configuration créée !</div>";
                break;
            default:
                break;
        }
        if(!empty($nomConfig) && !empty($csvData)){?>
            <h4>Nom de la configuration : <?php echo $nomConfig ?></h4>
            <div id="tableContainer">
                <table class="tableau table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th scope="col">Numero Carte</th>
                            <th scope="col">Nom Carte</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            for($i = 0; $i < count($csvData); $i++) {
                                echo "<tr>";
                                foreach ($csvData[$i] as $cell) {
                                    echo "<td>$cell</td>";
                                }
                                echo "</tr>";
                            }
                        ?>
                        </tr>
                    </tbody>
                </table>
            </div><?php
        }?>

        
    </main>
    <script>
        var ligneIndex = document.getElementById('ligneIndex');
        var btnModif = document.getElementById('ajoutNom');
        var nomCartes = document.getElementById('nomCarte');

        btnModif.disabled = true;
        nomCartes.disabled =true;
        // Attacher l'événement click pour sélectionner une ligne du tableau
        document.addEventListener('click', function(event) {
            const target = event.target;
            if (target.nodeName === 'TD') {
                const selectedRow = target.parentNode;
                const rowIndex = Array.from(selectedRow.parentNode.children).indexOf(selectedRow);

                const nomCarte = selectedRow.children[1].textContent;
                document.getElementById('nomCarte').value = nomCarte;

                // Vérifier si la ligne est déjà active
                const isActive = selectedRow.classList.contains("table-active");

                // Déselectionner toutes les autres lignes et surligner la ligne cliquée
                const rows = document.querySelectorAll("#tableContainer #myTable tbody tr");
                rows.forEach(row => {
                    row.classList.remove("table-active");
                    btnModif.disabled = true;
                    nomCartes.disabled = true;

                });

                // Si la ligne n'est pas déjà active, l'activer, sinon la désactiver
                if (!isActive) {
                    selectedRow.classList.add("table-active"); // Ajouter la classe "table-active" à la ligne sélectionnée
                    btnModif.disabled = false;
                    nomCartes.disabled = false;

                }

                // Mettre à jour l'index de ligne seulement si la ligne est active
                ligneIndex.value = isActive ? '' : rowIndex;
                console.log(ligneIndex.value);

                // Obtenez les valeurs de la deuxième colonne pour toutes les lignes
                const values = [];
                rows.forEach(row => {
                    const cellValue = row.children[1].textContent;
                    values.push(cellValue);
                });
                
            }
        });
    </script>
</body>
<?php 
session_write_close(); ?>