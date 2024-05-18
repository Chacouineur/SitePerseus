<?php

require __DIR__ . '/vendor/autoload.php';

use Nmap\Address;
use Nmap\Host;
use Nmap\Nmap;
use Nmap\Port;
use Nmap\Hostname;
use Nmap\XmlOutputParser;       

$btnValue = $_POST['btnValue']; 
$nomConfig = $_POST['config'];
$ligneIndex = $_POST['ligneIndex'];
$ipSSH = $_POST['ip'];
$userSSH = $_POST['utilisateur'];
$mdpSSH = $_POST['motDePasse'];
$portSSH = $_POST['port'];

$configurations = __DIR__ . "/configurations.csv";
$deploiementCSV = __DIR__."/Configurations/".$nomConfig."/deploiement.csv";
session_start();

switch($btnValue) {
    case'analyserRes':
        unset($_SESSION['stock']);
        unset($_SESSION['ips']);
        $ips = [];
        $stock = [];
        
        
        if (PHP_OS_FAMILY === 'Windows') {
            $nmap = new Nmap();
            //$nmap->disableReverseDNS();
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

            if(!empty($_POST['ipDebut'])&& !empty($_POST['ipFin'])){
                $ipDebut = $_POST['ipDebut'];
                $ipFin = $_POST['ipFin'];
            
                // Divisez l'adresse IP par le caractère '.'
                $partsDebut = explode('.', $ipDebut);
                $partsFin = explode('.', $ipFin);

                // Vérifiez que les trois premières parties sont identiques
                if ($partsDebut[0] !== $partsFin[0] || $partsDebut[1] !== $partsFin[1] || $partsDebut[2] !== $partsFin[2]) {
                    echo "Les trois premières parties des adresses IP de début et de fin doivent être identiques.";
                    header('Location: Pages/pageDeploiements.php?erreurIpRange');
                    exit;
                }
            
                // Obtenez la dernière partie de l'adresse IP
                $lastPartDeb = $partsDebut[3];
                $lastPartFin = $partsFin[3];
            
                if($lastPartDeb<=$lastPartFin){
                    // Register the custom error handler
                    set_error_handler('customErrorHandler');
            
                    for ($i = $lastPartDeb; $i <= $lastPartFin; $i++) {
                        $ip = $partsDebut[0] . '.' . $partsDebut[1] . '.' . $partsDebut[2] . '.' . $i;
                        $files = glob('/tmp/nmap-scan-output*');
                        foreach ($files as $file) {
                            unlink($file);
                        }
                        try {
                            // Scan the current IP address
                            $hosts = $nmap->scan([$ip], [22]);
                            // Check if the host is active
                            if (!empty($hosts)) {
                                $hostname = count($hosts) > 0 && count($hosts[0]->getHostnames()) > 0 ? $hosts[0]->getHostnames()[0]->getName() : 'No hostname';
                                $ips[] = $ip;
                                $stock[] = $ip.' | '.$hostname;
                            }
                        } catch (Exception $e) {
                            // Handle the exception (optional)
                            // For now, we are just ignoring it
                            echo 'inactive ip' . "<br>";
                        }
                    }
                }
            }
        } 
        else {
            // Must be run as root
            $arp_scan = shell_exec('arp-scan --interface=eth0 --localnet');
    
            $arp_scan = explode("\n", $arp_scan);
            $descs = [];
        
            foreach($arp_scan as $scan) {
                
                $matches = [];
                
                if(preg_match('/^([0-9\.]+)[[:space:]]+([0-9a-f:]+)[[:space:]]+(.+)$/', $scan, $matches) !== 1) {
                    continue;
                }
                
                $ips[] = $matches[1];
            }
            // Debug: Output IPs and descriptions
            if (empty($ips)) {
                echo 'No IPs found in arp-scan output.';
                header('Location: Pages/pageDeploiements.php?noIpFound');
                exit;
            }
            foreach($ips as $index => $ip) {
                $last_line = shell_exec('avahi-resolve -a '.$ip);
                $parts = explode("\t", $last_line);
                $hostname = trim($parts[1]);
                $stock[$index] = $ip." | ".$hostname;
            }
        }
        $_SESSION['stock'] = $stock;
        $_SESSION['ips'] = $ips;
        session_write_close();
        header('Location: Pages/pageDeploiements.php');
        break;
    case 'btnConfig': 
        $csvData=[];
        $_SESSION['configName'] = $nomConfig;
        $nomCartes = "";
        $nbCartes = 0;

        if (file_exists($configurations)) {
            $handle = fopen($configurations, 'r');
            $found = false;
            while (($line = fgetcsv($handle, 1000, ";")) !== false) {
                if ($line[0] === $nomConfig) {
                    $found = true;
                    $nbCartes = $line[1];
                    $nomCartes = $line[2];
                    break;
                }
            }
            
            fclose($handle);
        }
        if(!empty($nomCartes) && !empty($nbCartes))
        {
            $nomsCNs = explode('|', $nomCartes); 
            if(!file_exists($deploiementCSV)) {
                $handle = fopen($deploiementCSV, 'w');
                fputcsv($handle, ['Nom config', 'Numéro carte', 'Nom Carte', 'IP', 'Utilisateur SSH', 'Mot de passe SSH', 'Port SSH'], ';');
                for($i = 0; $i < $nbCartes; $i++) {
                    $numCarte = "CN" . ($i+1);
                    fputcsv($handle, [$nomConfig, $numCarte, $nomsCNs[$i],'#','#','#','#'], ';');
                    $csvData[] = [$numCarte, $nomsCNs[$i],'#','#','#','#'];
                }
            }
            else {
                $handle = fopen($deploiementCSV, 'r');
                $header = fgetcsv($handle, 1000, ";");
                while (($line = fgetcsv($handle, 1000, ";")) !== false) {
                    $csvData[] = [$line[1], $line[2], $line[3], $line[4], $line[5], $line[6]];
                }
                
            }
            $_SESSION['csvData']= $csvData;
            fclose($handle);
        }
        session_write_close();
        header('Location: Pages/pageDeploiements.php?tableauRempli');
        break;
    case 'modif':
        if (!empty($_SESSION['nomConfig']) && isset($ligneIndex) && isset($ipSSH) 
            && isset($userSSH) && isset($mdpSSH) && isset($portSSH)) {
            $currentConfig = $csvData[$ligneIndex];
            
            $currentConfig[3] = $ipSSH;
            $currentConfig[4] = $userSSH;
            $currentConfig[5] = $mdpSSH;
            $currentConfig[6] = $portSSH;

            $csvData[$ligneIndex] = $currentConfig; // Mettre à jour la ligne dans le tableau
            
            // Réécrire le fichier CSV avec les nouvelles données
            if (($handle = fopen($configurations, 'w')) !== false) {
                foreach ($csvData as $line) {
                    fputcsv($handle, $line, ';');
                }
                fclose($handle);
            } else {
                die("Impossible d'ouvrir le fichier $configurations pour écriture.");
            }
            $csvData = [];
            // Créer les nouvelles lignes pour chaque carte
            $handle = fopen($deploiementCSV, 'r');
            $header = fgetcsv($handle, 1000, ";");
            while (($line = fgetcsv($handle, 1000, ";")) !== false) {
                $csvData[] = [$line[1], $line[2], $line[3], $line[4], $line[5], $line[6]];
            }
            $_SESSION['csvData']= $csvData;
        }
        else{
            header('Location: Pages/pageDeploiements.php?erreur=line_not_clicked');
            exit();
        }
        header('Location: Pages/pageDeploiements.php?reussiteModif');
        exit();
        break;
    default:
        break;
}

?>
