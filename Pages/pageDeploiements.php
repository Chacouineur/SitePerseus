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
                            <a class="nav-link" href="pageModifCSV.php" style="font-weight: bold;">Modifier Fichier</a>
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
require __DIR__ . '/../vendor/autoload.php';

use Nmap\Nmap;

// Vérifiez si les données POST sont présentes
if (!empty($_POST['ipDebut']) && !empty($_POST['ipFin'])) {
    $ipDebut = $_POST['ipDebut'];
    $ipFin = $_POST['ipFin'];

    // Divisez l'adresse IP par le caractère '.'
    $partsDebut = explode('.', $ipDebut);
    $partsFin = explode('.', $ipFin);

    // Obtenez la dernière partie de l'adresse IP
    $lastPartDeb = end($partsDebut);
    $lastPartFin = end($partsFin);

    $diff = $lastPartFin - $lastPartDeb + 1;

    // Déterminez le nombre de processus à utiliser
    $processes = min($diff, 100); // Utilisez un maximum de 100 processus

    if ($diff >= 0) {
        try {
            // Créer un tableau pour stocker les identifiants de processus
            $pids = [];

            // Créer les processus pour chaque adresse IP
            for ($i = $lastPartDeb; $i <= $lastPartFin; $i++) {
                $pid = pcntl_fork();

                if ($pid == -1) {
                    // Gestion de l'erreur de création du processus
                    die('Erreur lors de la création du processus');
                } elseif ($pid) {
                    // Processus parent
                    $pids[] = $pid;
                } else {
                    // Processus enfant
                    $ip = '192.168.1.' . $i;
                    $hosts = Nmap::create()->scan([$ip], [80, 443]);

                    // Vérifiez si des hôtes ont été trouvés pour cette adresse IP
                    if (!empty($hosts)) {
                        foreach ($hosts as $host) {
                            $addresses = $host->getAddresses(); // Obtenez les adresses pour cet hôte
                            $hostname = $host->getHostnames(); // Obtenez les noms d'hôte pour cet hôte
                            foreach ($addresses as $address) {
                                echo "<li class=\"list-group-item\">
                                    <input type=\"checkbox\" class=\"form-check-input me-1\" name=\"checkboxes[]\" value=\"".$address->getAddress()."\"></input>
                                    <label class=\"form-check-label\">".$address->getAddress()."</label>
                                </li>";
                            }
                        }
                    }

                    // Sortir du processus enfant pour éviter la duplication
                    exit();
                }
            }

            // Attendre la fin de tous les processus enfants
            foreach ($pids as $pid) {
                pcntl_waitpid($pid, $status);
            }
        } catch (Exception $e) {
            // Gérer l'exception ici
            echo 'Erreur: ' . $e->getMessage();
        }
    } else {
        // Traiter le cas où la plage d'adresses IP est invalide
        echo 'La plage d\'adresses IP est invalide.';
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
    </main>
</body>
<?php session_write_close(); ?>