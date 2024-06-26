<?php 
    $titre = "Page Ajout CSV";
    $page = "../pageAjoutCSV.css";
    require '../header.inc.php';
    session_start();
    include '../tabDatas.php';
    include '../rechercheConfig.php';
    unset($_SESSION['csvName']);
    unset($_SESSION['csvDataDeploiement']);
    unset($_SESSION['fileName']);
    unset($_SESSION['fileType']);
    unset($_SESSION['csvData2']);
    unset($_SESSION['statesFile']);
    unset($_SESSION['boards']);
    unset($_SESSION['csvEG']);
    unset($_SESSION['configName2']);

    // Vérifier si la variable de session 'csvFileName' est définie
    if(isset($_SESSION['csvFileName']) && isset($_SESSION['configName'])){
        $csvFileName = $_SESSION['csvFileName'];
        $nomConfig = $_SESSION['configName'];
        $csvData =tabData($csvFileName,$nomConfig); 
        $_SESSION['csvData'] = $csvData;
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
    <?php include("headerPages.php"); ?>
    
    <main>
        <form method="post" action="../AjoutCorresp.php" class="mx-auto p-5 rounded" >
            <div class="mb-3" id="config">
                <label for="config" class="form-label">Configuration :</label>
                <select class="form-select" name="config" id="config" placeholder="Selectionnez une configuration"required>
                    <?php
                    if (!empty($folders)) {
                        foreach ($folders as $config) {
                            $selected = ($config == $nomConfig) ? 'selected' : '';  // Si le fichier correspond à $csvName, marquez-le comme sélectionné
                            echo "<option value=\"$config\" $selected>$config</option>";
                        }
                    }else{
                        echo "<option value=\"\">Veuillez creer une configuration dans « Ajouter Config » </option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3" id="code">
                <label for="code" class="form-label">Code État Général :</label>
                <input type="text" class="form-control" name="code" id="code" aria-describedby="codeHelp" placeholder="0x****" required>
            </div>
            
            <div class="mb-3 " id="etatGeneral">
                <label for="nom" class="form-label">Nom du fichier État Général :</label>
                <input type="text" class="form-control" name="nom" id="nom" aria-describedby="nomHelp" placeholder="Etat_*.csv" required>
            </div>

            <button type="submit" class="btn btn-primary" id="btnAddCorrespondance">Ajouter Correspondance</button>
            <?php 
                switch(true) {
                    case isset($_GET['erreurFichier']):
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, impossible d'ouvrir le fichier de configuration.</div>";
                        break;
                    case isset($_GET['erreurValeursExistent']):
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, les valeurs existent déjà dans le fichier.</div>";
                        break;
                    case isset($_GET['erreurValeursAjout']):
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, la valeur n'a pas pu être ajoutée au fichier.</div>";
                        break;
                    case isset($_GET['erreurCreationFichier']):
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier.</div>";
                        break;
                    case isset($_GET['erreurChampsVide']):
                        echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, les champs 'code' et 'nom' ne doivent pas être vides.</div>";
                        break;
                    case isset($_GET['reussite']):
                        echo "<div class=\"alert alert-success\" role=\"alert\">Le fichier a été créé et la valeur ajoutée avec succès.</div>";
                        break;
                    default:
                        break;
                }
                ?>

        </form>
        <?php if($csvFileName == []){?>
            <h5>Aucun fichier fichier n est choisi ! Veuillez creer un fichier ci dessus.</h5><?php
        }else{?>
            <h5>Le fichier ci dessous est : <?php echo $csvFileName; ?></h5>
            <form method="post" action="../ModifLigne.php" class="mx-auto p-5 rounded" id="ligne">
                <div class="row">
                    <div class="col-auto">
                        <label for="exampleInputCarte" class="form-label">Carte :</label>
                        <input type="text" class="form-control" name="carte" id="exampleInputCarte" aria-describedby="codeHelp" placeholder="CACMO/CACOE/etc" readonly>
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
                        <input type="number" min="0" step="any" class="form-control" name="timeDep" id="exampleInputTimeDep" aria-describedby="codeHelp" placeholder="Valeur timer">
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

                            <input type="text" class="form-control" id="selectedLabels" placeholder="Choisissez une valeur" aria-label="Texte des labels sélectionnés" readonly>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="ligneIndex" name="ligneIndex">
                <input type="hidden" id="csvFileName" name="csvFileName" value="<?php echo $csvFileName; ?>">
                <input type="hidden" id="csvConfigName" name="csvConfigName" value="<?php echo $nomConfig; ?>">
                <button type="submit" class="btn btn-primary" name="btnValue" value="modif" disabled>Modifier Ligne</button>
                <?php 
                    switch(true) {
                        case isset($_GET['erreurOuvrirfichier']):
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, impossible d'ouvrir le fichier CSV spécifié !</div>";
                            break;
                        case isset($_GET['erreurOuverture']):
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, ouverture du fichier CSV en écriture a échoué !</div>";
                            break;
                        case isset($_GET['erreurEcriture']):
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, écriture dans le fichier CSV a échoué !</div>";
                            break;
                        case isset($_GET['erreurbtn']):
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, bouton d'action non reconnu !</div>";
                            break;
                        case isset($_GET['erreurChampsvides']):
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, champs requis non remplis ou bouton non sélectionné !</div>";
                            break;
                        case isset($_GET['reussiteModif']):
                            echo "<div class=\"alert alert-success\" role=\"alert\">Modification réussie !</div>";
                            break;
                        case isset($_GET['erreurLectureActiv']):
                            $pathActivationCSV = htmlspecialchars($_GET['erreurLectureActiv']);
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, impossible de lire le fichier d'activation : $pathActivationCSV</div>";
                            break;
                        case isset($_GET['erreurOuvertureActiv']):
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, impossible d'ouvrir le fichier d'activation pour écriture.</div>";
                            break;
                        case isset($_GET['erreurEcritureActiv']):
                            echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, écriture dans le fichier d'activation échouée.</div>";
                            break;
                        default:
                            break;
                    }
                ?>

            </form>

            <table class="tableau table table-hover" id="myTable">
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

                               
                                var checkboxes = document.getElementById("checkboxes").querySelector('ul');
                                checkboxes.innerHTML = ''; // Supprimer toutes les cases à cocher existantes
         
                               
                                // Mise à jour des valeurs des champs de formulaire
                                carte.value = cells[0].textContent;
                                vannesEtat.value = cells[1].textContent;
                                valeur.value = cells[2].textContent;
                                timerDep.value = cells[3].textContent;
                                depVannes.value = cells[4].textContent;
                                modifierBtn.disabled = false; // Désactiver les boutons
                                
                                // Mise à jour de l'index de la ligne
                                ligneIndex.value = rowIndex+1; // Pour le champ caché
                                
                                // Gérer la surbrillance de la ligne
                                var rows = document.querySelectorAll("#myTable tbody tr");
                                rows.forEach(row => {
                                    if (rowIndex === Array.prototype.indexOf.call(row.parentNode.children, row)) {
                                        if (!row.classList.contains("table-active")) {
                                            row.classList.remove("table-active"); // Supprimer la classe "table-active" de toutes les lignes
                                            row.classList.add("table-active");
                                            var csvFileName = document.getElementById('csvFileName').getAttribute('value');
                                            var csvConfigName = document.getElementById('csvConfigName').getAttribute('value');

                                            csvFileName = "../Configurations/"+csvConfigName+"/commonCSVFiles/stateCSV/" + csvFileName;
                                            fetch(csvFileName)
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
                                                    
                                                    checkCheckboxes(cells);

                                                    
                                                })
                                                .catch(error => {
                                                    console.error('Erreur lors du chargement du fichier :', error);
                                                });
                                            
                                        } else {
                                            row.classList.remove("table-active"); // Supprimer la classe "table-active" pour la désurbrillance
                                            modifierBtn.disabled = true; // Désactiver les boutons
                                            
                                        }
                                    } else {
                                        row.classList.remove("table-active"); // Désurbriller les autres lignes

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
                
                function checkCheckboxes(cells) {
                    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                    var cellContent = cells[4].textContent; // Récupérer le contenu de cells[4]

                    // Diviser la chaîne en un tableau de valeurs
                    var values = cellContent.split('|');

                    // Parcourir chaque case à cocher
                    checkboxes.forEach(function(checkbox) {
                        // Vérifier si la valeur de la case à cocher est dans la liste des valeurs
                        if (values.includes(checkbox.value)) {
                            // Cocher la case à cocher si sa valeur correspond à une valeur dans la liste
                            checkbox.checked = true;
                        }
                    });
                }

            </script><?php
        }?>
    </main>
</body>
<?php session_write_close(); ?>
