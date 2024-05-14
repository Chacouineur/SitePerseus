<?php 
    $titre = "Page Deploiements";
    $page = "../pages.css";
    require '../header.inc.php';
    session_start();
    unset($_SESSION['dataConfig']);
    unset($_SESSION['nomCOnfig']);
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
        <form method="post" class="mx-auto p-5 rounded" id="mappingForm">
            
            <label for="code" class="form-label">Adresses IP :</label>
            <div class="input-group" id="ipRange">
                
                <span class="input-group-text">De</span>
                <input type="text" class="form-control" name="ipDebut" id="debut" aria-describedby="codeHelp" placeholder="192.168.1.*" required>
                <span class="input-group-text">à</span>
                <input type="text" class="form-control" name="ipFin" id="fin" aria-describedby="nomHelp" placeholder="192.168.1.*" required>
            </div>

            <button type="submit" class="btn btn-primary" id="btnAddCorrespondance">Mapping</button>
        </form>
        <form method="post" class="mx-auto p-5 rounded" id="deploiement">
            
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
                    // Must be run as root
                    $arp_scan = shell_exec('arp-scan --interface=eth0 --localnet');

                    $arp_scan = explode("\n", $arp_scan);

                    $matches;
                    $ips = array();

                    foreach($arp_scan as $scan) {
                        
                        $matches = array();
                        
                        if(preg_match('/^([0-9\.]+)[[:space:]]+([0-9a-f:]+)[[:space:]]+(.+)$/', $scan, $matches) !== 1) {
                            continue;
                        }
                        
                        $ips = $matches[1];
                        //$mac = $matches[2];
                        //$desc = $matches[3];
                        
                        //echo "Found device with mac address $mac ($desc) and ip $ip\n";
                    }
                    foreach($ips as $ip) {
                        echo "<li class=\"list-group-item\">
                        <input type=\"checkbox\" class=\"form-check-input me-1\" name=\"checkboxes[]\" value=\"".$ip."\"></input>
                        <label class=\"form-check-label\">".$ip."</label>
                        </li>";
                    }
                    /*
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
                    */?>
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
    </main>
</body>
<?php session_write_close(); ?>