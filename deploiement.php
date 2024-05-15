<?php
require __DIR__ . '/../vendor/autoload.php';

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
        } 
        else {    
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
