<?php 
    $titre = "Page Deploiements";
    $page = "../pages.css";
    require '../header.inc.php';
    include '../rechercheConfig.php';
    include '../rechercheVersionApp.php';
    session_start();    
    unset($_SESSION['csvName']);
    unset($_SESSION['csvData']);
    unset($_SESSION['csvFileName']);
    unset($_SESSION['fileName']);
    unset($_SESSION['fileType']);
    unset($_SESSION['csvData2']);
    unset($_SESSION['statesFile']);
    unset($_SESSION['boards']);
    unset($_SESSION['csvEG']);
    unset($_SESSION['configName2']);

    $stock = !empty($_SESSION['stock']) ? $_SESSION['stock'] : [];
    $ips = !empty($_SESSION['ips']) ? $_SESSION['ips'] : [];
    $csvData = !empty($_SESSION['csvDataDeploiement']) ? $_SESSION['csvDataDeploiement'] : [];
    $configName = !empty($_SESSION['configName']) ? $_SESSION['configName'] : [];
    $versionAppSel = !empty($_SESSION['versionApp']) ? $_SESSION['versionApp'] : [];
    
    $btnValue = $_POST['btnValue'];

    $configurations = __DIR__ . "/Configuration/configurations.csv";

?>
<body>
    <?php include("headerPages.php"); ?>
    <main>
        
        <form method="post" class="mx-auto p-5 rounded" action="../deploiement.php" id="mappingForm">
        <?php
        if (PHP_OS_FAMILY !== 'Windows') { ?>
            <label for="code" class="form-label">Appuyez sur ce bouton pour récupérer les IPs connectées sur le réseau local :</label>
            <button type="submit" class="btn btn-primary" name="btnValue" value="analyserRes">Analyser le réseau local</button>
        <?php } ?>
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

        <form method="post" class="mx-auto p-5 rounded" action="../deploiement.php" id="deploiement">
            <div class="row">
                <div class="col-auto">
                    <label for="selectedLabels" class="form-label col-auto">Carte ip par carte :</label>
                    <?php
                    if (PHP_OS_FAMILY === 'Windows') { ?>
                        <input type="text" class="form-control" name="ip" id="ip" aria-describedby="codeHelp" placeholder="Entrez manuellement l'ip sinon" >
                    <?php }
                    else { ?>
                    <div class="input-group mb-3">
                        <select class="form-select" aria-label="Default select example" id="ipSelect">
                            <?php
                                foreach($ips as $index => $ip) {
                                    echo "<option value=\"$ip\">$stock[$index]</option>";
                                }
                            ?>
                        </select>
                        <input type="text" class="form-control" name="ip" id="ip" aria-describedby="codeHelp" placeholder="Entrez manuellement l'ip sinon" >
                    </div>
                    <?php } ?>
                </div>
                <div class="col-auto">
                    <label for="exampleInputVannesEtat" class="form-label">Utilisateur SSH :</label>
                    <input type="text" class="form-control" name="utilisateur" id="user" aria-describedby="codeHelp" placeholder="Par défaut : pi4" >
                </div>
                <div class="col-auto">
                    <label for="exampleInputValeur" class="form-label">Mot de passe SSH :</label>
                    <input type="text" class="form-control" name="motDePasse" id="mdp" aria-describedby="codeHelp" placeholder="Par défaut : pi" >
                </div>
            </div>            
            <input type="hidden" class="form-control" placeholder="ligneIndex" name="ligneIndex" id="ligneIndex">
            <button type="submit" class="btn btn-primary" name="btnValue" value="modif" id="modif" disabled>Modifier Ligne</button>
            <?php
            switch(true) {
                case isset($_GET['erreurPasDeConfigSel']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, pas de configuration sélectionnée !</div>";
                    break;
                case isset($_GET['tableauVide']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le tableau de déploiement est vide !</div>";
                    break;
                case isset($_GET['erreurNoIpFound']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, pas d'IP trouvées sur le réseau local !</div>";
                    break;
                case isset($_GET['erreurSSHConnMN']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur de connexion SSH sur le MN !</div>";
                    break;
                case isset($_GET['erreurSFTPConnMN']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur de connexion SFTP sur le MN !</div>";
                    break;
                case isset($_GET['erreurSSHConn']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur de connexion SSH sur une des cartes CAC !</div>";
                    break;
                case isset($_GET['erreurSFTPConn']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur de connexion SFTP sur une des cartes CAC !</div>";
                    break;
                case isset($_GET['erreurOBCProgramRestart']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du redémarrage du programme OpenPOWERLINK MN !</div>";
                    break;
                case isset($_GET['erreurCACProgramRestart']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du redémarrage d'un programme OpenPOWERLINK CN !</div>";
                    break;
                case isset($_GET['erreurCopieCSVCommuns']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la copie des fichiers CSV communs sur le MN !</div>";
                    break;
                case isset($_GET['erreurCompileMN']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la compilation du MN !</div>";
                    break;
                case isset($_GET['erreurCopieNbNodes']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la copie des fichiers NbNodes sur le MN !</div>";
                    break;
                case isset($_GET['erreurCopieMnobd']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la copie des fichiers Mnobd sur le MN !</div>";
                    break;
                case isset($_GET['erreurExtractOplStackMN']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de l'extraction du stack OpenPOWERLINK sur le MN !</div>";
                    break;
                case isset($_GET['erreurCSVcommunsMNCreerZip']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier ZIP pour les CSV communs sur le MN !</div>";
                    break;
                case isset($_GET['erreurCSVcommunsMNZipIntrouvable']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier ZIP pour les CSV communs sur le MN est introuvable !</div>";
                    break;
                case isset($_GET['erreurCSVcommunsMNTeleversement']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du téléversement du fichier ZIP pour les CSV communs sur le MN !</div>";
                    break;
                case isset($_GET['erreurCSVcommunsMNExtraction']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de l'extraction du fichier ZIP pour les CSV communs sur le MN !</div>";
                    break;
                case isset($_GET['erreurMnobdCreerZip']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier ZIP pour Mnobd sur le MN !</div>";
                    break;
                case isset($_GET['erreurmnobdZipIntrouvable']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier ZIP pour Mnobd est introuvable sur le MN !</div>";
                    break;
                case isset($_GET['erreurmnobdTeleversement']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du téléversement du fichier ZIP pour Mnobd sur le MN !</div>";
                    break;
                case isset($_GET['erreurmnobdCopie']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la copie du fichier ZIP pour Mnobd sur le MN !</div>";
                    break;
                case isset($_GET['erreurOPLStackMNCreerZip']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier ZIP pour le stack OpenPOWERLINK sur le MN !</div>";
                    break;
                case isset($_GET['erreurOPLStackMNZipIntrouvable']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier ZIP pour le stack OpenPOWERLINK sur le MN est introuvable !</div>";
                    break;
                case isset($_GET['erreurOPLStackMNTeleversement']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du téléversement du fichier ZIP pour le stack OpenPOWERLINK sur le MN !</div>";
                    break;
                case isset($_GET['erreurOPLStackMNExtraction']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de l'extraction du fichier ZIP pour le stack OpenPOWERLINK sur le MN !</div>";
                    break;
                case isset($_GET['erreurFichierConfigurationCSVmanquant']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier CSV 'configurations.csv' n'existe pas !</div>";
                    break;
                case isset($_GET['erreurConfigurationCSV']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier CSV 'configurations.csv' n'est pas conforme, éléments illisibles !</div>";
                    break;
                case isset($_GET['erreurDeploiementCSVecriture']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier CSV 'deploiement.csv' de la configuration n'est pas accessible en écriture !</div>";
                    break;
                case isset($_GET['erreurChampsNonRemplis']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, tous les champs ne sont pas remplis !</div>";
                    break;
                case isset($_GET['erreurNbNodesCreerZip']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier ZIP pour NbNodes !</div>";
                    break;
                case isset($_GET['erreurNbNodesZipIntrouvable']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier ZIP pour NbNodes est introuvable !</div>";
                    break;
                case isset($_GET['erreurNbNodesTeleversement']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du téléversement du fichier ZIP pour NbNodes !</div>";
                    break;
                case isset($_GET['erreurNbNodesCopie']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la copie du fichier ZIP pour NbNodes !</div>";
                    break;
                case isset($_GET['erreurOPLStackCreerZip']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier ZIP pour le stack OpenPOWERLINK !</div>";
                    break;
                case isset($_GET['erreurOPLStackZipIntrouvable']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier ZIP pour le stack OpenPOWERLINK est introuvable !</div>";
                    break;
                case isset($_GET['erreurOPLStackTeleversement']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du téléversement du fichier ZIP pour le stack OpenPOWERLINK !</div>";
                    break;
                case isset($_GET['erreurOPLStackExtraction']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de l'extraction du fichier ZIP pour le stack OpenPOWERLINK !</div>";
                    break;
                case isset($_GET['erreurAppCACCreerZip']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier ZIP pour l'application des CAC !</div>";
                    break;
                case isset($_GET['erreurAppCACZipIntrouvable']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier ZIP pour l'application des CAC est introuvable !</div>";
                    break;
                case isset($_GET['erreurAppCACTeleversement']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du téléversement du fichier ZIP pour l'application des CAC !</div>";
                    break;
                case isset($_GET['erreurAppCACExtraction']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de l'extraction du fichier ZIP pour l'application des CAC !</div>";
                    break;
                case isset($_GET['erreurNodeIdCreerZip']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier ZIP pour NodeId !</div>";
                    break;
                case isset($_GET['erreurNodeIdZipIntrouvable']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier ZIP pour NodeId est introuvable !</div>";
                    break;
                case isset($_GET['erreurNodeIdTeleversement']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du téléversement du fichier ZIP pour NodeId !</div>";
                    break;
                case isset($_GET['erreurNodeIdCopie']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la copie du fichier NodeId !</div>";
                    break;
                case isset($_GET['erreurCSVphysiquesCreerZip']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier ZIP pour les CSV physiques !</div>";
                    break;
                case isset($_GET['erreurCSVphysiquesZipIntrouvable']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier ZIP pour les CSV physiques est introuvable !</div>";
                    break;
                case isset($_GET['erreurCSVphysiquesTeleversement']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du téléversement du fichier ZIP pour les CSV physiques !</div>";
                    break;
                case isset($_GET['erreurCSVphysiquesExtraction']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de l'extraction du fichier ZIP pour les CSV physiques !</div>";
                    break;
                case isset($_GET['erreurCSVcommunsCreerZip']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de la création du fichier ZIP pour les CSV communs !</div>";
                    break;
                case isset($_GET['erreurCSVcommunsZipIntrouvable']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur, le fichier ZIP pour les CSV communs est introuvable !</div>";
                    break;
                case isset($_GET['erreurCSVcommunsTeleversement']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors du téléversement du fichier ZIP pour les CSV communs !</div>";
                    break;
                case isset($_GET['erreurCSVcommunsExtraction']):
                    echo "<div class=\"alert alert-danger\" role=\"alert\">Erreur lors de l'extraction du fichier ZIP pour les CSV communs !</div>";
                    break;
                case isset($_GET['reseauAnalyse']):
                    echo "<div class=\"alert alert-success\" role=\"alert\">Réseau analysé, les IPs peuvent être sélectionnées.</div>";
                    break;
                case isset($_GET['tableauRempli']):
                    echo "<div class=\"alert alert-success\" role=\"alert\">Tableau du déploiement affiché.</div>";
                    break;
                case isset($_GET['reussiteModif']):
                    echo "<div class=\"alert alert-success\" role=\"alert\">Ligne modifiée.</div>";
                    break;
                case isset($_GET['deploiementReussi']):
                    echo "<div class=\"alert alert-success\" role=\"alert\">Déploiement sur toutes les cartes réussi.</div>";
                    break;
                default:
                    break;
            }
            ?>
        </form>
        <div id="tableContainer">
            <table class="tableau table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th scope="col">Numéro de carte</th>
                            <th scope="col">Carte</th>
                            <th scope="col">IP locale sélectionnée</th>
                            <th scope="col">Utilisateur SSH</th>
                            <th scope="col">Mot de passe SSH</th>
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
        </div>

        <?php
        if (PHP_OS_FAMILY === 'Windows') {
            $ipOBC = isset($_SESSION['ipOBC']) ? $_SESSION['ipOBC'] : '';
        }
        $userOBC = isset($_SESSION['userOBC']) ? $_SESSION['userOBC'] : '';
        $mdpOBC = isset($_SESSION['mdpOBC']) ? $_SESSION['mdpOBC'] : '';
        ?>

        <form method="post" class="mx-auto p-5 rounded" action="../SSHcommands.php" id="deploiement">
            <label for="selectedLabels" class="form-label row">Entrez les informations de connexion au MN (l'OBC) :</label>
            <?php if (PHP_OS_FAMILY === 'Windows') { ?>
            <label for="selectedLabels" class="form-label">Entrez l'IP du MN :</label>
            <input type="text" class="form-control" name="ipOBC" id="ipOBC" aria-describedby="codeHelp" placeholder="Entrez manuellement l'ip du MN" value="<?php echo $ipOBC; ?>" required>
            <label for="selectedLabels" class="form-label">Entrez l'utilisateur SSH du MN :</label>
            <input type="text" class="form-control" name="userOBC" id="userOBC" aria-describedby="codeHelp" placeholder="Entrez l'utilisateur SSH du MN" value="<?php echo $userOBC; ?>" required>
            <label for="selectedLabels" class="form-label">Entrez le mot de passe SSH du MN :</label>
            <input type="text" class="form-control" name="mdpOBC" id="mdpOBC" aria-describedby="codeHelp" placeholder="Entrez le mot de passe SSH du MN" value="<?php echo $mdpOBC; ?>" required>
            <?php } else { ?>
            <label for="selectedLabels" class="form-label">Entrez l'utilisateur local du MN :</label>
            <input type="text" class="form-control" name="userOBC" id="userOBC" aria-describedby="codeHelp" placeholder="Entrez l'utilisateur local du MN" value="<?php echo $userOBC; ?>" required>
            <label for="selectedLabels" class="form-label">Entrez le mot de passe local du MN :</label>
            <input type="text" class="form-control" name="mdpOBC" id="mdpOBC" aria-describedby="codeHelp" placeholder="Entrez le mot de passe local du MN" value="<?php echo $mdpOBC; ?>" required>
            <?php } ?>
            <label for="selectedLabels" class="form-label">Choix de la version de l'application OpenPOWERLINK :</label>
            <select class="form-select" name="versionApp" id="versionApp" placeholder="Selectionnez une version"required>
                <?php
                if (!empty($foldersApp)) {
                    foreach ($foldersApp as $versionApp) {
                        $selected = ($versionApp == $versionAppSel) ? 'selected' : '';   // Si le fichier correspond à $csvName, marquez-le comme sélectionné
                        echo "<option value=\"$versionApp\" $selected>$versionApp</option>";
                    }
                }else{
                    echo "<option value=\"\">Veuillez ajouter openPOWERLINK_V2_CAC_CN.zip et openPOWERLINK_V2_CAC_MN.zip dans « FichiersDeploiement/versionsApp/'version'/ » </option>";
                }
                ?>
            </select>
            <label for="selectedLabels" class="form-label">Choix des éléments à déployer :</label>
            <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                <input type="checkbox" class="btn-check" id="btncheck5" name="MNoplStack" autocomplete="off">
                <label class="btn btn-outline-success" for="btncheck5">MN - OpenPOWERLINK stack et application</label>

                <input type="checkbox" class="btn-check" id="btncheck6" name="MNappParam" autocomplete="off">
                <label class="btn btn-outline-success" for="btncheck6">MN - Paramètres d'application et compilation</label>

                <input type="checkbox" class="btn-check" id="btncheck7" name="MNcsvCommuns" autocomplete="off">
                <label class="btn btn-outline-success" for="btncheck7">MN - Fichiers CSV communs</label>

                <input type="checkbox" class="btn-check" id="btncheck1" name="CNoplStack" autocomplete="off">
                <label class="btn btn-outline-success" for="btncheck1">CN - OpenPOWERLINK stack et application</label>

                <input type="checkbox" class="btn-check" id="btncheck2" name="CNappParam" autocomplete="off">
                <label class="btn btn-outline-success" for="btncheck2">CN - Paramètres d'application et compilation</label>

                <input type="checkbox" class="btn-check" id="btncheck3" name="CNcsvPhysiques" autocomplete="off">
                <label class="btn btn-outline-success" for="btncheck3">CN - Fichiers CSV physiques</label>

                <input type="checkbox" class="btn-check" id="btncheck4" name="CNcsvCommuns" autocomplete="off">
                <label class="btn btn-outline-success" for="btncheck4">CN - Fichiers CSV communs</label>

            </div>
            <button type="submit" class="btn btn-success" name="btnValue" value="deployer" id="deployer" disabled>Déployer</button>
        </form>


        <script>
        var ligneIndex = document.getElementById('ligneIndex');
        var btnModif = document.getElementById('modif');
        var ipSelect = document.getElementById('ipSelect');
        var ip = document.getElementById('ip');
        var user = document.getElementById('user');
        var mdp = document.getElementById('mdp');


        btnModif.disabled = true;
        ip.disabled = true;
        user.disabled = true;
        mdp.disabled = true;

        // Attacher l'événement click pour sélectionner une ligne du tableau
        document.addEventListener('click', function(event) {
            const target = event.target;
            if (target.nodeName === 'TD') {
                const selectedRow = target.parentNode;
                const rowIndex = Array.from(selectedRow.parentNode.children).indexOf(selectedRow);
                
                if (ipSelect) {
                    ipSelect.value = 0;
                }

                ip.value = selectedRow.children[2].textContent;
                user.value = selectedRow.children[3].textContent;
                mdp.value = selectedRow.children[4].textContent;

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
                });

                // Si la ligne n'est pas déjà active, l'activer, sinon la désactiver
                if (!isActive) {
                    selectedRow.classList.add("table-active"); // Ajouter la classe "table-active" à la ligne sélectionnée
                    btnModif.disabled = false;
                    ip.disabled = false;
                    user.disabled = false;
                    mdp.disabled = false;

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
        if (ipSelect) {
            document.getElementById('ipSelect').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('ip').value = selectedValue;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const table = document.getElementById('myTable');
            const deployButton = document.getElementById('deployer');
            const modifButton = document.getElementById('modif');

            function checkTableCells() {
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                let allCellsFilled = true;

                if (rows.length === 0) {
                    allCellsFilled = false;
                } else {
                    Array.from(rows).forEach(row => {
                        const cells = row.getElementsByTagName('td');
                        Array.from(cells).forEach(cell => {
                            if (cell.textContent.trim() === '' || cell.textContent.trim() === '#') {
                                allCellsFilled = false;
                            }
                        });
                    });
                }

                deployButton.disabled = !allCellsFilled;
            }

            // Initial check
            checkTableCells();

            // Re-check whenever the modifier button is clicked
            modifButton.addEventListener('click', checkTableCells);
            table.addEventListener('click', checkTableCells);
        });

        document.addEventListener('DOMContentLoaded', (event) => {
            const btncheck1 = document.getElementById('btncheck1');
            const btncheck2 = document.getElementById('btncheck2');
            const btncheck3 = document.getElementById('btncheck3');
            const btncheck4 = document.getElementById('btncheck4');
            const btncheck5 = document.getElementById('btncheck5');
            const btncheck6 = document.getElementById('btncheck6');
            const btncheck7 = document.getElementById('btncheck7');

            const syncCheckboxes = (checkbox1, checkbox2) => {
                checkbox1.addEventListener('change', () => {
                    checkbox2.checked = checkbox1.checked;
                });

                checkbox2.addEventListener('change', () => {
                    checkbox1.checked = checkbox2.checked;
                });
            };

            syncCheckboxes(btncheck1, btncheck5);
            syncCheckboxes(btncheck2, btncheck6);
            syncCheckboxes(btncheck4, btncheck7);

            const checkboxes = document.querySelectorAll('.btn-check');
            const deployButton = document.getElementById('deployer');

            const updateAllCheckboxes = (checked) => {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = checked;
                });
            };

            const toggleAllCheckboxes = (event) => {
                const isChecked = event.target.checked;
                updateAllCheckboxes(isChecked);
                deployButton.disabled = !Array.from(checkboxes).some(cb => cb.checked);
            };

            btncheck1.addEventListener('change', toggleAllCheckboxes);
            btncheck5.addEventListener('change', toggleAllCheckboxes);

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', () => {
                    deployButton.disabled = !Array.from(checkboxes).some(cb => cb.checked);
                });
            });

            deployButton.disabled = !Array.from(checkboxes).some(cb => cb.checked);
        });
        
    </script>
    </main>
</body>
<?php session_write_close(); ?>