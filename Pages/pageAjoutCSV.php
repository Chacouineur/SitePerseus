<?php 
    $titre = "Page Ajout CSV";
    $page = "../pageAjoutCSV.css";
    require '../header.inc.php';
    session_start();

    // Vérifier si la variable de session 'csvData' est définie
    if(isset($_SESSION['csvData'])) 
        $csvData = $_SESSION['csvData'];
    else {
        // Initialisez $csvData comme un tableau vide si la session n'a pas encore été définie
        $csvData = [];
    }
    // Vérifier si la variable de session 'csvFileName' est définie
    if(isset($_SESSION['csvFileName'])) 
        $csvFileName = $_SESSION['csvFileName'];
    else {
        // Initialisez $csvFileName comme un tableau vide si la session n'a pas encore été définie
        $csvFileName = [];
    }

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

    <form method="post" action="../AjoutCorresp.php" class="mx-auto p-5 rounded" >
    
        <div class="mb-3" id="code">
            <label for="code" class="form-label">Code État Général :</label>
            <input type="text" class="form-control" name="code" id="code" aria-describedby="codeHelp" placeholder="0x****" required>
        </div>
        
        <div class="mb-3 " id="etatGeneral">
            <label for="nom" class="form-label">Nom du fichier État Général :</label>
            <input type="text" class="form-control" name="nom" id="nom" aria-describedby="nomHelp" placeholder="Etat_*.csv" required>
        </div>

        <button type="submit" class="btn btn-primary" id="btnAddCorrespondance">Ajouter Correspondance</button>
    </form>
    <?php if($csvFileName == []){?>
        <h5>Aucun fichier fichier n est choisi ! Veuillez creer un fichier ci dessus.</h5><?php
    }else{?>
        <h5>Le fichier ci dessous est : <?php echo $csvFileName; ?></h5>
        <form method="post" action="../ModifLigne.php" class="mx-auto p-5 rounded" id="ligne">
            <div class="row">
                <div class="col-auto">
                    <label for="exampleInputCarte" class="form-label">Carte :</label>
                    <input type="text" class="form-control" name="carte" id="exampleInputCarte" aria-describedby="codeHelp" placeholder="CACMO/CACOE/etc" required>
                </div>
                <div class="col-auto">
                    <label for="exampleInputVannesEtat" class="form-label">Vannes/Etat :</label>
                    <input type="text" class="form-control" name="vannesEtat" id="exampleInputVannesEtat" aria-describedby="codeHelp" placeholder="VCE/VBCE/EG/VPr0/etc" >
                </div>
                <div class="col-auto">
                    <label for="exampleInputValeur" class="form-label">Valeur :</label>
                    <input type="number" class="form-control" name="valeur" id="exampleInputValeur" aria-describedby="codeHelp" placeholder="0 ou 1" min="0" max="1" >
                </div>
                <div class="col-auto">
                    <label for="exampleInputTimeDep" class="form-label">Timer dépendance :</label>
                    <input type="number" step="0.1" min="0.1" class="form-control" name="timeDep" id="exampleInputTimeDep" aria-describedby="codeHelp" placeholder="Valeur timer">
                </div>
                <div class="col-auto">
                    <label for="exampleInputDepVannes" class="form-label">Dépendance vannes:</label>
                    <input type="text" class="form-control" name="depVannes" id="exampleInputDepVannes" aria-describedby="codeHelp" placeholder="Modification dans « Modifier Fichier »" disabled>
                </div>
            </div>
            <input type="hidden" id="ligneIndex" name="ligneIndex">
            <button type="submit" class="btn btn-primary" name="btnValue" value="ajout">Ajouter Ligne</button>
            <button type="submit" class="btn btn-primary" name="btnValue" value="modif">Modifier Ligne</button>
            <button type="submit" class="btn btn-primary" name="btnValue" value="suppr">Supprimer Ligne</button>
        </form>

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


        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var table = document.getElementById('myTable');

                table.addEventListener('click', function(event) {
                    var target = event.target; // où a eu lieu le clic
                    while (target && target.nodeName !== "TR") { // remonter jusqu'à l'élément TR
                        target = target.parentNode;
                    }
                    if (target && target.parentNode) { // Si un TR a été cliqué
                        var rowIndex = Array.prototype.indexOf.call(target.parentNode.children, target);
                        var cells = target.children; // Les cellules TD/TH de la ligne
                        if (cells.length > 0) {
                            var carte = document.getElementById('exampleInputCarte');
                            var vannesEtat = document.getElementById('exampleInputVannesEtat');
                            var valeur = document.getElementById('exampleInputValeur');
                            var timerDep = document.getElementById('exampleInputTimeDep');
                            var ligneIndex = document.getElementById('ligneIndex'); // Pour le champ caché

                            // Mise à jour des valeurs des champs de formulaire
                            carte.value = cells[0].textContent;
                            vannesEtat.value = cells[1].textContent;
                            valeur.value = cells[2].textContent;
                            timerDep.value = cells[3].textContent;

                            // Mise à jour de l'index de la ligne
                            ligneIndex.value = rowIndex+1; // Pour le champ caché

                            ligneIndexDisplay.textContent = 'Ligne sélectionnée: ' + (ligneIndex.value); // Pour l'affichage
                        }
                    }
                });
                
            });
            // Sélectionner toutes les lignes de la table
            const rows = document.querySelectorAll("#myTable tbody tr");

            // Ajouter un gestionnaire d'événement de clic à chaque ligne
            rows.forEach(row => {
                row.addEventListener("click", () => {
                    // Supprimer la classe 'selected' de toutes les lignes
                    rows.forEach(r => r.classList.remove("selected"));
                    // Ajouter la classe 'selected' à la ligne cliquée
                    row.classList.add("selected");
                });
            });

        </script><?php
    }?>

</body>