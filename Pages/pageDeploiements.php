<?php 
    $titre = "Page Deploiements";
    $page = "../pages.css";
    require '../header.inc.php';
    session_start();
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

        // Register the custom error handler
        set_error_handler('customErrorHandler');

        echo 'Scanning...' . "<br>";

        for ($i = 20; $i <= 26; $i++) {
            $ip = '192.168.1.' . $i;
            echo $ip;
            $files = glob('/tmp/nmap-scan-output*');
            foreach ($files as $file) {
                unlink($file);
            }
            try {
                $nmap = new Nmap();
                // Scan the current IP address
                $hosts = $nmap->scan([$ip], [ 21, 22, 80 ]);
                echo 'ok' . "<br>";
                // Check if the host is active
                if (!empty($hosts)) {
                    $addresses = $hosts[0]->getAddresses();

                    foreach ($addresses as $address) {
                        echo "IP Address: " . $address->getAddress() . "<br>";
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
        ?>
    </main>
</body>
<?php session_write_close(); ?>