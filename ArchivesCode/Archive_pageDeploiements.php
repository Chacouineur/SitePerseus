<?php 
    $titre = "Page Deploiements";
    $page = "../pages.css";
    require '../header.inc.php';
    include '../rechercheConfig.php';
    session_start();    
    unset($_SESSION['csvName']);
    unset($_SESSION['dataConfig']);
    unset($_SESSION['nomConfig']);
    $stock = !empty($_SESSION['stock']) ? $_SESSION['stock'] : [];
    $ips = !empty($_SESSION['ips']) ? $_SESSION['ips'] : [];
    $btnValue = $_POST['btnValue']; 
    if(isset($_SESSION['csvData'])){
        $csvData = $_SESSION['csvData'];
    } else {
        // Initialisez $csvFileName comme un tableau vide si la session n'a pas encore été définie
        $csvData = [];
    }
    if(isset($_SESSION['configName'])){
        $configName = $_SESSION['configName'];
    }else{
        $configName=[];
    }

    $configurations = __DIR__ . "/Configuration/configurations.csv";

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
                            <a class="nav-link" href="pageAjoutCSV.php">Ajouter Fichier</a>
                        </li>

                        <li class="nav-item ">
                            <a class="nav-link" href="pageModifCSV.php">Modifier Fichier</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="pageSuppCSV.php">Supprimer Fichier</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="pageDeploiements.php" style="font-weight: bold;">Déploiements</a>
                        </li>
                        
                    </ul>

                </div>
            </div>
        </nav>

    </header>
    <main>
        <?php
        if (PHP_OS_FAMILY === 'Windows') {?>
            <form method="post" class="mx-auto p-5 rounded" action="../deploiement.php" id="mappingForm">
                <label for="code" class="form-label">Adresses IP :</label>
                <div class="input-group" id="ipRange">
                    
                    <span class="input-group-text">De</span>
                    <input type="text" class="form-control" name="ipDebut" id="debut" aria-describedby="codeHelp" placeholder="192.168.1.*">
                    <span class="input-group-text">à</span>
                    <input type="text" class="form-control" name="ipFin" id="fin" aria-describedby="nomHelp" placeholder="192.168.1.*">
                </div>
                <button type="submit" class="btn btn-primary" name="btnValue" value="analyserRes" id="btnAddCorrespondance">Analyser le réseau local</button>

                <div class="mb-3" id="config">
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
                            <button class="btn btn-primary" type="submit" name="btnValue" id="btnConfig" value="btnConfig">Selectionner Config</button>
                        </div>
                    </div>
            </form>
        <?php }
        else {
            ?>
            <form method="post" class="mx-auto p-5 rounded" action="../deploiement.php" id="mappingForm">
                <label for="code" class="form-label">Appuyez sur ce bouton pour récupérer les IPs connectées sur le réseau local :</label>
                <button type="submit" class="btn btn-primary" name="btnValue" value="analyserRes">Analyser le réseau local</button>
                <div class="mb-3">
                    <label for="config" class="form-label">Configuration :</label>
                    <div class="input-group mb-3">
                        <select class="form-select" name="config" id="config" placeholder="Selectionnez une configuration"required>
                            <?php
                            if (!empty($folders)) {
                                foreach ($folders as $config) {
                                    $selected = ($config == $configName) ? 'selected' : '';   // Si le fichier correspond à $csvName, marquez-le comme sélectionné
                                    echo "<option value=\"$config\" $selected>$config</option>";
                                }
                            }else{
                                echo "<option value=\"\">Veuillez creer une configuration dans « Ajouter Config » </option>";
                            }
                            ?>
                        </select>
                        <button class="btn btn-primary" type="submit" name="btnValue" id="btnConfig" value="btnConfig">Selectionner Config</button>
                    </div>
                </div>
            </form>
        <?php } ?>
        <form method="post" class="mx-auto p-5 rounded" action="../deploiement.php" id="deploiement">
            <div class="row">
                <div class="col-auto">
                    <label for="selectedLabels" class="form-label col-auto">Carte ip par carte :</label>
                    <div class="input-group mb-3">
                        <select class="form-select" aria-label="Default select example" id="">
                            <option>Selectionne Carte(s)</option>
                                <?php
                                    foreach($ips as $index => $ip) {
                                        echo "<option value=\"$ip\">$stock[$index]</option>";
                                    }
                                ?>
                        </select>
                        <input type="text" class="form-control" name="ip" id="ip" aria-describedby="codeHelp" placeholder="Entrez manuellement l'ip sinon" >
                    </div>
                </div>
                <div class="col-auto">
                    <label for="exampleInputVannesEtat" class="form-label">Utilisateur SSH :</label>
                    <input type="text" class="form-control" name="utilisateur" id="user" aria-describedby="codeHelp" placeholder="Par défaut : pi4" >
                </div>
                <div class="col-auto">
                    <label for="exampleInputValeur" class="form-label">Mot de passe SSH :</label>
                    <input type="number" class="form-control" name="motDePasse" id="mdp" aria-describedby="codeHelp" placeholder="Par défaut : pi" >
                </div>
                <div class="col-auto">
                    <label for="exampleInputValeur" class="form-label">Port SSH :</label>
                    <input type="number" class="form-control" name="port" id="port" aria-describedby="codeHelp" placeholder="Par défaut : 22" >
                </div>
            </div>            
            <input type="hidden" class="form-control" placeholder="ligneIndex" name="ligneIndex" id="ligneIndex">
            <button type="submit" class="btn btn-primary" name="btnValue" value="modif" id="modif" disabled>Modifier Ligne</button>
        </form>
        
        <table class="tableau table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">Numéro de carte</th>
                        <th scope="col">Carte</th>
                        <th scope="col">IP locale sélectionnée</th>
                        <th scope="col">Utilisateur SSH</th>
                        <th scope="col">Mot de passe SSH</th>
                        <th scope="col">Port SSH</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Vérifiez si $csvData est défini avant d'utiliser la boucle foreach
                    if(isset($csvData)) {
                        // Boucle à travers $csvData à partir de la deuxième ligne
                        for($i = 0; $i < count($csvData); $i++) {
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
        var ligneIndex = document.getElementById('ligneIndex');
        var btnModif = document.getElementById('modif');
        var ip = document.getElementById('ip');
        var user = document.getElementById('user');
        var mdp = document.getElementById('mdp');
        var port = document.getElementById('port');


        btnModif.disabled = true;
        ip.disabled = true;
        user.disabled = true;
        mdp.disabled = true;
        port.disabled = true;

        // Attacher l'événement click pour sélectionner une ligne du tableau
        document.addEventListener('click', function(event) {
            const target = event.target;
            if (target.nodeName === 'TD') {
                const selectedRow = target.parentNode;
                const rowIndex = Array.from(selectedRow.parentNode.children).indexOf(selectedRow);

                ip.value = selectedRow.children[2].textContent;
                user.value = selectedRow.children[3].textContent;
                mdp.value = selectedRow.children[4].textContent;
                port.value = selectedRow.children[5].textContent;

                // Vérifier si la ligne est déjà active
                const isActive = selectedRow.classList.contains("table-active");

                // Déselectionner toutes les autres lignes et surligner la ligne cliquée
                const rows = document.querySelectorAll("#tableContainer #myTable tbody tr");
                rows.forEach(row => {
                    row.classList.remove("table-active");
                    btnModif.disabled = true;
                    ip.disabled = true;
                    user.disabled = true;
                    mdp.disabled = true;
                    port.disabled = true;

                });

                // Si la ligne n'est pas déjà active, l'activer, sinon la désactiver
                if (!isActive) {
                    selectedRow.classList.add("table-active"); // Ajouter la classe "table-active" à la ligne sélectionnée
                    btnModif.disabled = false;
                    ip.disabled = false;
                    user.disabled = false;
                    mdp.disabled = false;
                    port.disabled = false;

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
    </main>
</body>
<?php session_write_close(); ?>