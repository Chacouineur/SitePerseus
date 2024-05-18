<?php 
    $titre = "Page Ajout Config";
    $page = "../pages.css";
    require '../header.inc.php';
    session_start();
    unset($_SESSION['csvName']);
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
    <header>
        <nav class="navbar navbar-expand-lg" id="nav">
            <div class="container-fluid">
                <a class="navbar-brand" href="../index.php">
                    <img src="../logo.png" alt="Logo" width="100" height="100" class="d-inline-block align-text-center">HOME
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item ">
                            <a class="nav-link" href="pageAjoutConfig.php" style="font-weight: bold;">Ajouter Config</a>
                        </li>
                        
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
            <button type="submit" class="btn btn-primary" name="btnValue" id="btnAddCorrespondance" value="ajoutCorresp">Ajouter Configuration</button>
        </form>
        <?php 
        switch(true) {
            case isset($_GET['erreur']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Vous n'avez pas rempli tous les champs ou vous n'avez pas mis tous les noms des cartes dans le tableau !</div>";
                break;
            case isset($_GET['nom_existe']):
                echo "<div class=\"alert alert-danger\" role=\"alert\">Une configuration ayant le même nom existe déjà !</div>";
                break;
            case isset($_GET['reussiteCreerTab']):
                echo "<div class=\"alert alert-success\" role=\"alert\">Tableau des noms des cartes créé, veuillez selectionner et entrer le nom de chaque carte. </div>";
                break;
            case isset($_GET['reussite']):
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