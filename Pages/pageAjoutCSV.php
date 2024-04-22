<?php 
    $titre = "Page Ajout CSV";
    $page = "../pageAjoutCSV.css";
    require '../header.inc.php';
    session_start();
    include '../afficher.php';
    unset($_SESSION['csvName']);

    // Vérifier si la variable de session 'csvFileName' est définie
    if(isset($_SESSION['csvFileName'])){
        $csvFileName = $_SESSION['csvFileName'];
        afficherData($csvFileName); 
    } else {
        // Initialisez $csvFileName comme un tableau vide si la session n'a pas encore été définie
        $csvFileName = [];
    }

    // Vérifier si la variable de session 'csvData' est définie
    if(isset($_SESSION['csvData'])) 
        $csvData = $_SESSION['csvData'];
    else {
        // Initialisez $csvData comme un tableau vide si la session n'a pas encore été définie
        $csvData = [];
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
    <main>
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
                        <label for="selectedLabels" class="form-label">Dépendance vannes:</label>
                        <div class="input-group">
                            <div class="multiselect">
                                <div class="selectBox" onclick="showCheckBoxes()">
                                    <select class="form-select" aria-label="Default select example" id="">
                                        <option>Select an option</option>
                                    </select>
                                    <div class="overSelect"></div>
                                </div>
                                <div id="checkboxes">
                                <ul class="list-group">
                                </ul>
                                </div>
                            </div>

                            <input type="text" class="form-control" id="selectedLabels" placeholder="Choisissez une valeur" aria-label="Texte des labels sélectionnés" disabled>
                            <input type="hidden" class="form-control" id="selectedValues" placeholder="Valeurs sélectionnées" name="select" aria-label="Valeurs sélectionnées" disabled>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="ligneIndex" name="ligneIndex">
                <input type="hidden" id="csvFileName" name="csvFileName" value="<?php echo $csvFileName; ?>">
                <button type="submit" class="btn btn-primary" name="btnValue" value="ajoutMax">Ajouter Carte</button>
                <button type="submit" class="btn btn-primary" name="btnValue" value="modif" disabled>Modifier Ligne</button>
                <button type="submit" class="btn btn-primary" name="btnValue" value="suppr"disabled>Supprimer Carte</button>
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
            
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>

            <script>
                var expanded = false;
                function showCheckBoxes(){
                    const checkboxes = document.getElementById("checkboxes");
                    if (!expanded) {
                        checkboxes.style.display = "block";
                        expanded = true;
                    } else {
                        checkboxes.style.display = "none";
                        expanded = false;
                    }
                }

                document.addEventListener('DOMContentLoaded', function () {
                    var table = document.getElementById('myTable');
                    var selectedRowIndex = null; // Pour stocker l'index de la ligne sélectionnée



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
                                var depVannes = document.getElementById('selectedLabels'); 
                                var ligneIndex = document.getElementById('ligneIndex'); 
                                var modifierBtn = document.querySelector('button[name="btnValue"][value="modif"]');
                                var supprimerBtn = document.querySelector('button[name="btnValue"][value="suppr"]');

                                var checkboxes = document.getElementById("checkboxes").querySelector('ul');
                                checkboxes.innerHTML = ''; // Supprimer toutes les cases à cocher existantes
         
                               
                                // Mise à jour des valeurs des champs de formulaire
                                carte.value = cells[0].textContent;
                                vannesEtat.value = cells[1].textContent;
                                valeur.value = cells[2].textContent;
                                timerDep.value = cells[3].textContent;
                                modifierBtn.disabled = false; // Désactiver les boutons
                                supprimerBtn.disabled = false;
                                

                                // Mise à jour de l'index de la ligne
                                ligneIndex.value = rowIndex+1; // Pour le champ caché
                                
                                // Gérer la surbrillance de la ligne
                                var rows = document.querySelectorAll("#myTable tbody tr");
                                rows.forEach(row => {
                                    if (rowIndex === Array.prototype.indexOf.call(row.parentNode.children, row)) {
                                        if (!row.classList.contains("selected")) {
                                            row.classList.add("selected"); // Ajouter la classe "selected" pour la surbrillance
                                            var csvFileName = document.getElementById('csvFileName').getAttribute('value');
                                            var csvFilePath = "../" + csvFileName;

                                            fetch(csvFilePath)
                                                .then(response => response.text())
                                                .then(data => {
                                                    var lines = data.split('\n'); // Split the string into an array of lines
                                                    // Filter out lines starting with "OFFSET"
                                                    var withoutOFFSET = lines.filter(line => line.split(';')[0] !== 'OFFSET');
                                                    // Further filter to exclude lines where the second column is '#'
                                                    var filteredRows = withoutOFFSET.filter(line => {
                                                        const columns = line.split(';');
                                                        return columns[1] !== '#' && columns[0] === carte.value; // Assuming 'carte.value' is defined elsewhere
                                                    });
                                                    // Map the filtered lines to create an array of the second column's values and their original line number
                                                    var result = filteredRows.map((line, index) => {
                                                        const columns = line.split(';');
                                                        let originalIndex = lines.indexOf(line) - 1; 
                                                        return [columns[1], originalIndex];
                                                    });
                                                    console.log(result);

                                                    var displayedValues = [];
                                                    
                                                    result.forEach(item => {
                                                        // Vérifier si la valeur n'est pas déjà affichée
                                                        if (!displayedValues.includes(item[1])) {
                                                            addCheckboxWithValue(item[1], item[0]); // Ajouter une case à cocher pour chaque élément de result
                                                            displayedValues.push(item[1]); // Ajouter la valeur à la liste des valeurs déjà affichées
                                                        }
                                                    });

                                                    function addCheckboxWithValue(value, labelValue) {
                                                        var listItem = document.createElement("li");
                                                        listItem.className = "list-group-item";
                                                        var checkbox = document.createElement("input");
                                                        checkbox.type = "checkbox";
                                                        checkbox.className = "form-check-input me-1";
                                                        checkbox.name = "checkboxes[]"; // Nom de la case à cocher pour l'envoi POST ou GET
                                                        checkbox.value = value; // Définir la valeur de la checkbox
                                                        checkbox.onclick = updateSelectedValues; // Écouteur d'événements pour mettre à jour les valeurs sélectionnées

                                                        var label = document.createElement("label");
                                                        label.className = "form-check-label";
                                                        label.textContent = labelValue;
                                                        
                                                        listItem.appendChild(checkbox);
                                                        listItem.appendChild(label);
                                                        checkboxes.appendChild(listItem);
                                                    }   
                                                })
                                                .catch(error => {
                                                    console.error('Erreur lors du chargement du fichier :', error);
                                                });

                                        } else {
                                            row.classList.remove("selected"); // Supprimer la classe "selected" pour la désurbrillance
                                            modifierBtn.disabled = true; // Désactiver les boutons
                                            supprimerBtn.disabled = true;
                                            
                                        }
                                    } else {
                                        row.classList.remove("selected"); // Désurbriller les autres lignes

                                    }
                                });
                            }
                        }
                    });
                });
                
                function updateSelectedValues() {
                    var selectedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
                    var selectedValues = Array.from(selectedCheckboxes).map(cb => cb.value).join('|');
                    document.getElementById('selectedLabels').value = selectedValues;
                }
            </script><?php
        }?>
    </main>
</body>
<?php session_write_close(); ?>