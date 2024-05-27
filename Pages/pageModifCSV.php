<?php 
    $titre = "Page Modification CSV";
    $page = "../pages.css";
    require '../header.inc.php';
    include '../rechercheConfig.php';
    
    session_start();
    unset($_SESSION['csvFileName']);
    unset($_SESSION['csvName']);
    unset($_SESSION['csvData']);
    unset($_SESSION['csvDataDeploiement']);
    unset($_SESSION['csvEG']);
    unset($_SESSION['configName']);

    
    $fileName = isset($_SESSION['fileName']) ? $_SESSION['fileName'] : [];
    $fileType = isset($_SESSION['fileType']) ? $_SESSION['fileType'] : [];
    $csvData = isset($_SESSION['csvData2']) ? $_SESSION['csvData2'] : [];
    $configName = isset($_SESSION['configName2']) ? $_SESSION['configName2'] : [];
    $stateFiles = isset($_SESSION['statesFile']) ? $_SESSION['statesFile'] : [];
    $boards = isset($_SESSION['boards']) ? $_SESSION['boards'] : [];

?>
<body>
    <?php include("headerPages.php"); ?>
    <main>
        <div class="row">
            <div class="col-4" id='colFichiers' >
                <form method="post" action="../modifierCSV.php" class="ml-5 mr-5 mb-3" id="firstForm">
                    <div class="row mb-3">
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
                </form>
                <?php 
                if(!empty($configName)){?>
                    <form method="post" action="../modifierCSV.php" class="ml-5 mr-5 mb-3" id="ligne">
                        <div class="row mb-3">
                            <div class="col" id="formSelect">
                                <label for="selectFile" class="form-label">Fichier CSV configuration physique vannes de :</label>
                                <div class="input-group mb-3">
                                    <select class="form-select" name="FileValve" id="selectFile" placeholder="Selectionnez un fichier"required>
                                        <?php
                                        if (!empty($boards)) {
                                            foreach ($boards as $board) {
                                                $selected = ($board == $fileName) ? 'selected' : '';  // Si le fichier correspond à $fileName, marquez-le comme sélectionné
                                                echo "<option value=\"$board\" $selected>$board</option>";

                                            }
                                        }else{
                                            echo "<option value=\"\">Veuillez creer un fichier dans « Ajouter Fichier » </option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" name="btnValue" value="afficher">Afficher le fichier</button>
                                </div>
                            </div>
                        </div>  
                    </form>
                    <form method="post" action="../modifierCSV.php" class="ml-5 mr-5 mb-3" id="ligne">
                        <div class="row mb-3">
                            <div class="col" id="formSelect">
                                <label for="selectFile" class="form-label">Fichier CSV configuration physique capteurs de :</label>
                                <div class="input-group mb-3">
                                    <select class="form-select" name="FileSensor" id="selectFile" placeholder="Selectionnez un fichier"required>
                                        <?php
                                        if (!empty($boards)) {
                                            foreach ($boards as $board) {
                                                $selected = ($board == $fileName) ? 'selected' : '';  // Si le fichier correspond à $fileName, marquez-le comme sélectionné
                                                echo "<option value=\"$board\" $selected>$board</option>";

                                            }
                                        }else{
                                            echo "<option value=\"\">Veuillez creer un fichier dans « Ajouter Fichier » </option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" name="btnValue" value="afficher">Afficher le fichier</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form method="post" action="../modifierCSV.php" class="ml-5 mr-5 mb-3" id="firstForm">
                        <div class="row">
                            <div class="col" id="formSelect">
                                <label for="selectFile" class="form-label">Fichier CSV etats :</label>
                                <div class="input-group mb-3">
                                    <select class="form-select" name="FileEG" id="selectFile" placeholder="Selectionnez un fichier"required>
                                        <?php
                                        if (!empty($stateFiles)) {
                                            foreach ($stateFiles as $stateFile) {
                                                $selected = ($stateFile == $fileName) ? 'selected' : '';  // Si le fichier correspond à $fileName, marquez-le comme sélectionné
                                                echo "<option value=\"$stateFile\" $selected>$stateFile</option>";

                                            }
                                        }else{
                                            echo "<option value=\"\">Veuillez creer un fichier dans « Ajouter Fichier » </option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" class="btn btn-primary" name="btnValue" value="afficher">Afficher le fichier</button>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                    <form method="post" action="../modifierCSV.php" class="ml-5 mr-5 mb-3" id="ligne">
                        <div class="row mb-3">
                            <div class="col" id="formSelect">
                                <label for="selectFile" class="form-label">Fichier CSV activation :</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="FileActiv" id="activation" value="activation.csv" readonly>
                                    <button type="submit" class="btn btn-primary" name="btnValue" value="afficher">Afficher le fichier</button> 
                                </div>
                            </div>
                        </div>

                    </form>
                    
                <?php } ?>
            </div>
            <div class="col" id='colForulaire' >
            <?php if($fileName == [] && $csvData == []){?>
                    <h4>Aucun fichier fichier n est choisi ! Veuillez choisir un fichier a afficher.</h5><?php
                }else{
                    if ($fileType === "sensor") {?>
                        <h4>Le fichier ci dessous correspond a la carte : <?php echo "$fileName"; ?></h5>
                        <form method="post" action="../modifierCSV.php" class="mb-5" id="secondForm">
                            <input type="hidden" name="carte" id="carte" >
                            <input type="hidden" name="capteur" id="capteur" >
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="type" class="form-label">Type :</label>
                                    <select class="form-select" name="type" id="type" disabled>
                                        <option value="#">Non defini</option>
                                        <option value="1">MCP3008</option>
                                        <option value="2">Modbus</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="modbusRemoteSlaveAdress" class="form-label">Modbus remote slave address :</label>
                                    <input type="text" class="form-control" name="modbusRemoteSlaveAdress" id="modbusRemoteSlaveAdress" placeholder="Ex: " disabled >
                                </div>
                                <div class="col">
                                    <label for="modbusStartAdress" class="form-label">Modbus start address :</label>
                                    <input type="text" class="form-control" name="modbusStartAdress" id="modbusStartAdress" placeholder="Ex: 3" disabled>
                                </div>
                                <div class="col">
                                    <label for="modbusBaudRate" class="form-label">Modbus baud rate :</label>
                                    <select class="form-select" name="modbusBaudRate" id="modbusBaudRate" disabled>
                                        <option value="#">Non defini</option>
                                        <option value="1200">1200</option>
                                        <option value="2400">2400</option>
                                        <option value="4800">4800</option>
                                        <option value="9600">9600</option>
                                        <option value="19200">19200</option>
                                        <option value="38400">38400</option>
                                        <option value="57600">57600</option>
                                        <option value="115200">115200</option>
                                        <option value="230400">230400</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="modbusParity" class="form-label">Modbus parity :</label>
                                    <select class="form-select" name="modbusParity" id="modbusParity" disabled>
                                        <option value="#">Non defini</option>
                                        <option value="N">None</option>
                                        <option value="E">Even</option>
                                        <option value="O">Odd</option>
                                    </select>
                                </div>  
                                <div class="col">
                                    <label for="modbusDataBits" class="form-label">Modbus Data bits :</label>
                                    <select class="form-select" name="modbusDataBits" id="modbusDataBits" disabled>
                                        <option value="#">Non defini</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                    </select>
                                </div>  
                                <div class="col">
                                    <label for="modbusStopBits" class="form-label">Modbus Stop bit :</label>
                                    <select class="form-select" name="modbusStopBits" id="modbusStopBits" disabled>
                                        <option value="#">Non defini</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div> 
                            </div>
                            <input type="hidden" id="ligneIndex" name="ligneIndex">
                            <input type="hidden" id="csvFileName" name="csvFileName" value="sensors">
                            <button type="submit" class="btn btn-primary" name="btnValue" id="btnModif" value="modifSensor" disabled>Modifier Ligne</button>
                        </form>
                    <?php
                    } elseif ($fileType === "valve") { ?>
                        <h4>Le fichier ci dessous correspond a la carte : <?php echo "$fileName"; ?></h5>
                        <form method="post" action="../modifierCSV.php" class="mb-5" id="secondForm">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="exampleInputCarte" class="form-label">Carte :</label>
                                    <input type="text" class="form-control" name="carte" id="exampleInputCarte" placeholder="CACMO/CACOE/etc" readonly disabled>
                                </div>
                                <div class="col">
                                    <label for="exampleInputVannesEtat" class="form-label">Vannes/Etat :</label>
                                    <input type="text" class="form-control" name="vannesEtat" id="exampleInputVannesEtat" placeholder="VCE/VBCE/EG/VPr0/etc"  disabled>
                                </div>
                                <div class="col">
                                    <label for="EtatInit" class="form-label">Etat initial :</label>
                                    <input type="number" class="form-control" name="EtatInit" id="EtatInit" placeholder="Valeur Etat Init" min="0" max="1" disabled>
                                </div>
                                <div class="col">
                                    <label for="portGPIO" class="form-label">PORT GPIO :</label>
                                    <input type="number" class="form-control" name="portGPIO" id="portGPIO" placeholder="Valeur port GPIO" min="0" disabled>
                                </div>
                            </div>
                            <input type="hidden" id="ligneIndex" name="ligneIndex">
                            <input type="hidden" id="csvFileName" name="csvFileName" value="valves">
                            <button type="submit" class="btn btn-primary" name="btnValue" id="btnModif" value="modifValve" disabled>Modifier Ligne</button>
                        </form>
                    <?php
                    }elseif ($fileName === "activation.csv") { ?>
                        <h4>Le fichier ci dessous est : activation.csv</h5>
                        <form method="post" action="../modifierCSV.php" class="mb-5" id="secondForm">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="exampleInputCarte" class="form-label">Carte :</label>
                                    <input type="text" class="form-control" name="carte" id="exampleInputCarte" placeholder="CACMO/CACOE/etc" disabled>
                                </div>
                                <div class="col">
                                    <label for="exampleInputVannesEtat" class="form-label">Vannes/Etat :</label>
                                    <input type="text" class="form-control" name="vannesEtat" id="exampleInputVannesEtat" placeholder="VCE/VBCE/EG/VPr0/etc" disabled >
                                </div>
                                <div class="col">
                                    <label for="exampleRadios1" and for="exampleRadios2" class="form-label">Activation :</label>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios0" value="0" checked disabled>
                                        <label class="form-check-label" for="exampleRadios1" >Desactiver</label >
                                    </div>
                                    <div class="form-check ml-3">
                                        <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="1" disabled>
                                        <label class="form-check-label" for="exampleRadios2">Activer</label>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="ligneIndex" name="ligneIndex">
                            <input type="hidden" id="csvFileName" name="csvFileName" value="activation">
                            <button type="submit" class="btn btn-primary" name="btnValue" id="btnModif" value="modifActiv" >Modifier Ligne</button>
                        </form>
                    <?php
                    } else { ?>
                        <h4>Le fichier ci dessous est : <?php echo "$fileName"; ?></h5>
                        <form method="post" action="../modifierCSV.php" class="mb-5" id="secondForm">
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="exampleInputCarte" class="form-label">Carte :</label>
                                    <input type="text" class="form-control" name="carte" id="exampleInputCarte" aria-describedby="codeHelp" placeholder="CACMO/CACOE/etc" readonly disabled required >
                                </div>
                                <div class="col">
                                    <label for="exampleInputVannesEtat" class="form-label">Vannes/Etat :</label>
                                    <input type="text" class="form-control" name="vannesEtat" id="exampleInputVannesEtat" aria-describedby="codeHelp" placeholder="VCE/VBCE/EG/VPr0/etc" disabled>
                                </div>
                                <div class="col">
                                    <label for="exampleInputValeur" class="form-label">Valeur :</label>
                                    <input type="number" class="form-control" name="valeur" id="exampleInputValeur" aria-describedby="codeHelp" placeholder="0 ou 1" min="0" max="1" disabled>
                                </div>
                                <div class="col">
                                    <label for="exampleInputTimeDep" class="form-label">Timer dépendance :</label>
                                    <input type="number" min="0" class="form-control" name="timeDep" id="exampleInputTimeDep" aria-describedby="codeHelp" placeholder="Valeur timer" disabled>
                                </div>
                                <div class="col">
                                    <label for="selectedLabels" class="form-label">Dépendance vannes:</label>
                                    <div class="input-group">
                                        <div class="multiselect">
                                            <div class="selectBox" onclick="showCheckBoxes()">
                                                <select class="form-select" aria-label="Default select example" id="">
                                                    <option>Selectionne Carte(s)</option>
                                                </select>
                                                <div class="overSelect"></div>
                                            </div>
                                            <div id="checkboxes" >
                                            <ul class="list-group" disabled>
                                            </ul>
                                            </div>
                                        </div>

                                        <input type="text" class="form-control rounded" id="selectedLabels" placeholder="Choisissez valeur" aria-label="Texte des labels sélectionnés" disabled>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="ligneIndex" name="ligneIndex">
                            <input type="hidden" id="csvFileName" name="csvFileName" value="<?php echo '../Configurations/' . $configName . '/commonCSVFiles/stateCSV/' . $fileName; ?>">
                            <button type="submit" class="btn btn-primary" name="btnValue" id="btnModif" value="modifState" disabled>Modifier Ligne</button>
                        </form>
                    <?php }?>
                    
                    <table class="tableau table table-hover " id="myTable">
                        <thead>
                            <tr>
                                <?php
                                if (isset($csvData) && !empty($csvData)){
                                    foreach ($csvData[0] as $cell) {
                                        echo "<td>$cell</td>";
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Vérifiez si $csvData est défini avant d'utiliser la boucle foreach
                            if(isset($csvData) && !empty($csvData)) {
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
                            var csvFile = document.getElementById('csvFileName');
                            var modifierBtn = document.getElementById('btnModif');
                            var ligneIndex = document.getElementById('ligneIndex'); 

                            table.addEventListener('click', function(event) {
                                var target = event.target; // où a eu lieu le clic
                                while (target && target.nodeName !== "TR") { // remonter jusqu'à l'élément TR
                                    target = target.parentNode;
                                }
                                if (target && target.parentNode) { // Si un TR a été cliqué
                                    var rowIndex = Array.prototype.indexOf.call(target.parentNode.children, target);
                                    var cells = target.children; // Les cellules TD/TH de la ligne
                                    if (cells.length > 0) {
                                        var csvFileName = csvFile.value; // Utilisez .value pour obtenir la valeur
                                        
                                        
                                        if(csvFileName.includes('stateCSV')){
                                            var carte = document.getElementById('exampleInputCarte');
                                            var vannesEtat = document.getElementById('exampleInputVannesEtat');
                                            var valeur = document.getElementById('exampleInputValeur');
                                            var timerDep = document.getElementById('exampleInputTimeDep');
                                            var depVannes = document.getElementById('selectedLabels'); 
                                            var checkboxes = document.getElementById("checkboxes").querySelector('ul');
                                            
                                            checkboxes.innerHTML = ''; // Supprimer toutes les cases à cocher existantes
                    
                                            // Mise à jour des valeurs des champs de formulaire
                                            carte.value = cells[0].textContent;
                                            vannesEtat.value = cells[1].textContent;
                                            valeur.value = cells[2].textContent;
                                            timerDep.value = cells[3].textContent;
                                            depVannes.value = cells[4].textContent;
                                        }else if(csvFileName==='activation'){
                                            var carte = document.getElementById('exampleInputCarte');
                                            var vannesEtat = document.getElementById('exampleInputVannesEtat');
                                            var radioBtn0 = document.getElementById('exampleRadios0'); 
                                            var radioBtn1 = document.getElementById('exampleRadios1'); 

                                            carte.value = cells[0].textContent;
                                            vannesEtat.value = cells[1].textContent;
                                            if (cells[2].textContent=== '0' || cells[2].textContent==='#'){
                                                radioBtn0.checked = true ;
                                            }else{
                                                radioBtn1.checked = true ;
                                            }
                                        }else if(csvFileName==='sensors'){
                                            var carte2 = document.getElementById('carte');
                                            var capteur = document.getElementById('capteur');
                                            var type = document.getElementById('type'); 
                                            var modbusRemoteSlaveAdress = document.getElementById('modbusRemoteSlaveAdress'); 
                                            var modbusStartAdress = document.getElementById('modbusStartAdress'); 
                                            var modbusBaudRate = document.getElementById('modbusBaudRate'); 
                                            var modbusParity = document.getElementById('modbusParity'); 
                                            var modbusDataBits = document.getElementById('modbusDataBits'); 
                                            var modbusStopBits = document.getElementById('modbusStopBits'); 

                                            carte2.value = cells[0].textContent;
                                            capteur.value = cells[1].textContent;
                                            type.value = cells[2].textContent;
                                            modbusRemoteSlaveAdress.value = cells[3].textContent;
                                            modbusStartAdress.value = cells[4].textContent;
                                            modbusBaudRate.value = cells[5].textContent;
                                            modbusParity.value = cells[6].textContent;
                                            modbusDataBits.value = cells[7].textContent;
                                            modbusStopBits.value = cells[8].textContent;
                                            console.log(modbusStopBits.value);
                                        }else if(csvFileName==='valves'){
                                            var carte = document.getElementById('exampleInputCarte');
                                            var vannesEtat = document.getElementById('exampleInputVannesEtat');
                                            var etatInit = document.getElementById('EtatInit');
                                            var portGPIO = document.getElementById('portGPIO');

                                            carte.value = cells[0].textContent;
                                            vannesEtat.value = cells[1].textContent;
                                            etatInit.value = cells[2].textContent;
                                            portGPIO.value = cells[3].textContent;
                                        }
                                        
                                        modifierBtn.disabled = false; // Désactiver les boutons
                                        
                                        // Mise à jour de l'index de la ligne
                                        ligneIndex.value = rowIndex+1; // Pour le champ caché
                                        
                                        // Gérer la surbrillance de la ligne
                                        var rows = document.querySelectorAll("#myTable tbody tr");
                                        rows.forEach(row => {
                                            if (rowIndex === Array.prototype.indexOf.call(row.parentNode.children, row)) {
                                                if (!row.classList.contains("table-active")) {
                                                    row.classList.add("table-active");

                                                    if(csvFileName.includes('stateCSV')){
                                                        carte.disabled = false;
                                                        vannesEtat.disabled = false;
                                                        valeur.disabled = false;
                                                        timerDep.disabled = false;
                                                        checkboxes.disabled = false;
                                                    }else if(csvFileName==='activation'){
                                                        carte.disabled = false;
                                                        vannesEtat.disabled = false;
                                                        radioBtn0.disabled = false;
                                                        radioBtn1.disabled = false;
                                                    }else if(csvFileName==='sensors'){
                                                        type.disabled = false;
                                                        modbusRemoteSlaveAdress.disabled = false;
                                                        modbusStartAdress.disabled = false;
                                                        modbusBaudRate.disabled = false;
                                                        modbusParity.disabled = false;
                                                        modbusDataBits.disabled = false;
                                                        modbusStopBits.disabled = false;
                                                    }else if(csvFileName==='valves'){
                                                        carte.disabled = false;
                                                        vannesEtat.disabled = false;
                                                        etatInit.disabled = false;
                                                        portGPIO.disabled = false;
                                                    }

                                                    if(csvFileName.includes('stateCSV')){
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
                                                    }
                                                } else {
                                                    row.classList.remove("table-active"); // Supprimer la classe "table-active" pour la désurbrillance
                                                    modifierBtn.disabled = true; // Désactiver les boutons
                                                    if(csvFileName.includes('stateCSV')){
                                                        carte.disabled = true;
                                                        vannesEtat.disabled = true;
                                                        valeur.disabled = true;
                                                        timerDep.disabled = true;
                                                        checkboxes.disabled = true;
                                                    }else if(csvFileName==='activation'){
                                                        carte.disabled = true;
                                                        vannesEtat.disabled = true;
                                                        radioBtn0.disabled = true;
                                                        radioBtn1.disabled = true;
                                                    }else if(csvFileName==='sensors'){
                                                        type.disabled = true;
                                                        modbusRemoteSlaveAdress.disabled = true;
                                                        modbusStartAdress.disabled = true;
                                                        modbusBaudRate.disabled = true;
                                                        modbusParity.disabled = true;
                                                        modbusDataBits.disabled = true;
                                                        modbusStopBits.disabled = true;
                                                    }else if(csvFileName==='valves'){
                                                        carte.disabled = true;
                                                        vannesEtat.disabled = true;
                                                        etatInit.disabled = true;
                                                        portGPIO.disabled = true;
                                                    }
                                                  
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

                    </script>
                <?php } ?>
            </div>
        </div>
    </main>
</body>

<?php session_write_close(); ?>