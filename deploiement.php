<?php

require __DIR__ . '/vendor/autoload.php';

use Nmap\Address;
use Nmap\Host;
use Nmap\Nmap;
use Nmap\Port;
use Nmap\Hostname;
use Nmap\XmlOutputParser;       

$btnValue = $_POST["btnAnalyser"];
$fileName = $_POST['FileName'];
$nbCartes = $_POST["nbCartes"];
$btnValue = $_POST['btnValue']; 
$config = $_POST['config'];
session_start();

$path = __DIR__."/Configurations/".$config."deploiement.csv";

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
                $files = glob('/tmp/nmap-scan-output*');
                foreach ($files as $file) {
                    unlink($file);
                }
                try {
                    $last_line = shell_exec('avahi-resolve -a '.$ip);
                    $parts = explode("\t", $last_line);
                    $hostname = trim($parts[1]);
                    $stock[$index] = $ip." | ".$hostname;
                } catch (Exception $e) {
                    // Handle the exception (optional)
                    // For now, we are just ignoring it
                    echo 'inactive ip: ' . $ip . "<br>";
                    echo $e->getMessage() . "<br>";
                }
            }
        }
        $_SESSION['stock'] = $stock;
        $_SESSION['ips'] = $ips;
        session_write_close();
        header('Location: Pages/pageDeploiements.php');
        break;
    case 'config':
        $_SESSION['configName'] = $config;
        // Créer les nouvelles lignes pour chaque carte
        for($i = 0; $i < $nbCartes; $i++) {
            $numCarte = "CN" . ($i+1);
            $nomCarte = $nomsCNs[$i];        
            $csvData[] = [$numCarte, $nomCarte];
        }
        $_SESSION['dataConfig']= $csvData;
        session_write_close();
        header('Location: Pages/pageDeploiements.php');
        break;
    default:
    break;
}

?>
