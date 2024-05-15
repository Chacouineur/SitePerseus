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
        if (PHP_OS_FAMILY === 'Windows') {
            // Display the form only if the OS is Windows
            echo '
            <form method="post" class="mx-auto p-5 rounded" id="mappingForm">
                <label for="code" class="form-label">Adresses IP :</label>
                <div class="input-group" id="ipRange">
                    
                    <span class="input-group-text">De</span>
                    <input type="text" class="form-control" name="ipDebut" id="debut" aria-describedby="codeHelp" placeholder="192.168.1.*" required>
                    <span class="input-group-text">à</span>
                    <input type="text" class="form-control" name="ipFin" id="fin" aria-describedby="nomHelp" placeholder="192.168.1.*" required>
                </div>
                <button type="submit" class="btn btn-primary" id="btnAddCorrespondance">Mapping</button>
            </form>';
        }
        else {
            echo '
            <form method="post" class="mx-auto p-5 rounded" id="mappingForm">
                <label for="code" class="form-label">Appuyez sur ce bouton pour récupérer les IPs connectées sur le réseau local :</label>
                <button type="submit" class="btn btn-primary" name="btnAnalyser">Analyser le réseau local</button>
            </form>';
        }
        ?>
        <form method="post" class="mx-auto p-5 rounded" id="deploiement">
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
                    <button class="btn btn-primary" type="submit" name="btnValue" id="btnConfig" value="config">Selectionner Config</button>
                </div>
            </div>
            <label for="selectedLabels" class="form-label">Cartes :</label>
            <div class="input-group">
                <div class="multiselect">
                    <div class="selectBox" onclick="showCheckBoxes()">
                        <select class="form-select" aria-label="Default select example" id="">
                            <option>Selectionne Carte(s)</option>
                        </select>
                        <div class="overSelect"></div>
                    </div>
                    <div id="checkboxes">
                    <ul class="list-group">
                    
                    <?php     
                    require __DIR__ . '/../vendor/autoload.php';

                    use Nmap\Address;
                    use Nmap\Host;
                    use Nmap\Nmap;
                    use Nmap\Port;
                    use Nmap\Hostname;
                    use Nmap\XmlOutputParser;       

                    // Custom error handler function
                    function customErrorHandler($errno, $errstr, $errfile, $errline) {
                        // Check if the error is a fatal error
                        if ($errno & (E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR)) {
                            // Log the error or handle it as needed
                            // For now, we are just returning true to ignore the error
                            return true;
                        }
                        
                        // Let PHP's default error handler handle other errors
                        return false;
                    }     

                    set_error_handler('customErrorHandler');

                    if (PHP_OS_FAMILY === 'Windows') {
                        if(!empty($_POST['ipDebut'])&& !empty($_POST['ipFin'])){
                            $ipDebut = $_POST['ipDebut'];
                            $ipFin = $_POST['ipFin'];
                        
                            // Divisez l'adresse IP par le caractère '.'
                            $partsDebut = explode('.', $ipDebut);
                            $partsFin = explode('.', $ipFin);
                        
                            // Obtenez la dernière partie de l'adresse IP
                            $lastPartDeb = end($partsDebut);
                            $lastPartFin = end($partsFin);
                        
                            if($lastPartDeb<=$lastPartFin){
                                // Register the custom error handler
                                set_error_handler('customErrorHandler');
                        
                                for ($i = $lastPartDeb; $i <= $lastPartFin; $i++) {
                                    $ip = '192.168.10.' . $i;
                                    $files = glob('/tmp/nmap-scan-output*');
                                    foreach ($files as $file) {
                                        unlink($file);
                                    }
                                    try {
                                        $nmap = new Nmap();
                                        // Scan the current IP address
                                        $hosts = $nmap->scan([$ip], [22]);
                                        // Check if the host is active
                                        if (!empty($hosts)) {
                                            $addresses = $hosts[0]->getIpv4Addresses();
    
                                            foreach ($addresses as $address) {
                                                echo "<li class=\"list-group-item\">
                                                    <input type=\"checkbox\" class=\"form-check-input me-1\" name=\"checkboxes[]\" value=\"".$address->getAddress()."\"></input>
                                                    <label class=\"form-check-label\">".$address->getAddress()."</label>
                                                </li>";
                                            }
                                            
                                            $hostnames = $hosts[0]->getHostnames();
    
                                            foreach ($hostnames as $hostname) {
                                                echo "Hostname: " . $hostname->getName() . "<br>";
                                            }
                                        }
                                    } catch (Exception $e) {
                                        // Handle the exception (optional)
                                        // For now, we are just ignoring it
                                        echo 'inactive ip' . "<br>";
                                    }
                                }
                            }
                        }
                    } else {    
                        if(isset($_POST['btnAnalyser'])){
                            unset($_SESSION['stock']);
                            unset($_SESSION['ips']);
                            // Must be run as root
                            $arp_scan = shell_exec('arp-scan --interface=eth0 --localnet');
        
                            $arp_scan = explode("\n", $arp_scan);
        
                            $ips = [];
                            $descs = [];
        
                            $files = glob('/tmp/nmap-scan-output*');
                            foreach ($files as $file) {
                                unlink($file);
                            }
                        
                            foreach($arp_scan as $scan) {
                                
                                $matches = [];
                                
                                if(preg_match('/^([0-9\.]+)[[:space:]]+([0-9a-f:]+)[[:space:]]+(.+)$/', $scan, $matches) !== 1) {
                                    continue;
                                }
                                
                                $ips[] = $matches[1];
                                $descs[] = $matches[3];
                            }
        
                            // Debug: Output IPs and descriptions
                            if (empty($ips)) {
                                echo 'No IPs found in arp-scan output.';
                                exit;
                            }
                            $stock[]=[];
                            foreach($ips as $index => $ip) {
                                try {
                                    $nmap = new Nmap();
        
                                    // Scan the current IP address
                                    $hosts = $nmap->scan([$ip], [22]);  
                                    $hostname = count($hosts) > 0 && count($hosts[0]->getHostnames()) > 0 ? $hosts[0]->getHostnames()[0]->getName() : 'No hostname';

                                    $stock[] = $ip."|".$descs[$index]."|".$hostname;

                                } catch (Exception $e) {
                                    // Handle the exception (optional)
                                    // For now, we are just ignoring it
                                    echo 'inactive ip: ' . $ip . "<br>";
                                    echo $e->getMessage() . "<br>";
                                }
                            }
                            $_SESSION['stock'] = $stock;
                            $_SESSION['ips'] = $ips;
                        }
                        foreach($ips as $index => $ip) {
                            echo "<li class=\"list-group-item\">
                            <input type=\"checkbox\" class=\"form-check-input me-1\" name=\"checkboxes[]\" value=\"$ip\"></input>
                            <label class=\"form-check-label\">".$stock[$index+1]."</label>
                            </li>";
                        }
                    }
                    ?>
                    </ul>
                    </div>
                </div>
            </div>
        
        </form>
        
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
            
        </script>
        
        <table class="tableau table table-hover" id="myTable">
                <thead>
                    <tr>
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
        <?php
            $vide = implode('|', array_fill(0, $nbCartes, '#'));
        
            if (!file_exists($configurations)) {
                $handle = fopen($configurations, 'w');
                fputcsv($handle, ['Nom', 'Nombre CNs', 'Noms CNs'], ';');
                fputcsv($handle, [$nomConfig, $nbCartes, $vide], ';');
                fclose($handle);
                $_SESSION['nomConfig'] = $nomConfig;
            } else {
                // Le fichier existe, lire son contenu pour vérifier si le nom de la configuration existe déjà
                $handle = fopen($configurations, 'r');
                $found = false;
                while (($line = fgetcsv($handle, 1000, ";")) !== false) {
                    if ($line[0] === $nomConfig) {
                        $found = true;
                        break;
                    }
                }
                fclose($handle);
                if ($found) {
                    header('Location: Pages/pageAjoutConfig.php?erreur');
                    exit;
                } else {
                    $handle = fopen($configurations, 'a');
                    if (fputcsv($handle, [$nomConfig, $nbCartes, $vide], ';')) {
                        $_SESSION['nomConfig'] = $nomConfig;
                        echo $_SESSION['nomConfig'];
                    }
                    fclose($handle);
                }
            }
            $nomsCNs = explode('|', $vide); 
            // Créer les nouvelles lignes pour chaque carte
            for($i = 0; $i < $nbCartes; $i++) {
                $numCarte = "CN" . ($i+1);
                $nomCarte = $nomsCNs[$i];        
                $csvData[] = [$numCarte, $nomCarte];
            }
            $_SESSION['dataConfig']= $csvData;
        ?>
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
                    nomCartes.disabled =true;

                });

                // Si la ligne n'est pas déjà active, l'activer, sinon la désactiver
                if (!isActive) {
                    selectedRow.classList.add("table-active"); // Ajouter la classe "table-active" à la ligne sélectionnée
                    btnModif.disabled = false;
                    nomCartes.disabled =false;

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