<?php 
session_start();
$csvData=[];
$nomConfig = $_POST["config"];
$nomCarte = $_POST["nomCarte"];
$btnValue = $_POST["btnValue"];
$ligneIndex = $_POST['ligneIndex'];
$configurations = __DIR__ . "/configurations.csv";

if (!empty($_SESSION['nbCartes']) && $btnValue !== 'creerTab') {
    $nbCartes = $_SESSION['nbCartes'];
} elseif ($btnValue === 'creerTab') {
    $nbCartes = $_POST["nbCartes"];
} else {
    header('Location: Pages/pageDeploiements.php?erreurPasDeNbCartes');
    exit;
}

switch($btnValue){
    case "ajoutConfig":
        $nomConfig = $_SESSION['nomConfig'];
        // Ouvrir le fichier configurations.csv en lecture
        if (($handleConfig = fopen($configurations, 'r')) !== false) {
            $found = false;
            
            // Parcourir chaque ligne du fichier
            while (($line = fgetcsv($handleConfig, 1000, ";")) !== false) {
                // Vérifier si la ligne correspond à $nomConfig
                if ($line[0] === $nomConfig) {
                    $found = true;
                    // Vérifier si la troisième colonne ne contient pas de #
                    if (strpos($line[2], '#')===false && $found===true) {
                        $nom = explode('|',$line[2]);
                        $dossierConfig = __DIR__."/Configurations/$nomConfig";
                        $dossierCommonCSVFiles = $dossierConfig."/commonCSVFiles";
                        $dossierStateCSV = $dossierCommonCSVFiles."/stateCSV";
                        if(!file_exists($dossierConfig)){
                            mkdir($dossierConfig,0777, true);
                    
                            mkdir($dossierCommonCSVFiles,0777, true);

                            $dataActiv = [];

                            $activationCSV = $dossierCommonCSVFiles . "/activation.csv";
                            $handle = fopen($activationCSV, 'w');

                            $dataActiv = ["Carte", "Vannes/Etat", "Activation"];
                            fputcsv($handle, $dataActiv, ';');

                            $offset = ["OFFSET", "EC", "1"];
                            fputcsv($handle, $offset, ';');

                            for ($i = 0; $i < $line[1]; $i++) {
                                $nomCarte = $nom[$i];
                                
                                for ($j = 1; $j <= 12; $j++) {
                                    $dataActiv = [$nomCarte, "valve$j", "0"];
                                    fputcsv($handle, $dataActiv, ';');
                                }

                                for ($j = 1; $j <= 12; $j++) {
                                    $dataActiv = [$nomCarte, "sensor$j", "0"];
                                    fputcsv($handle, $dataActiv, ';');
                                }
                                
                                fputcsv($handle, $offset, ';');
                            }

                            fclose($handle);
                             
                            $dataEG = ["Code EG","Nom fichier CSV"];
                            $liaisonEGEtat = $dossierCommonCSVFiles."/liaisonEGEtat.csv";
                            $handle = fopen($liaisonEGEtat, 'w');
                            fputcsv($handle, $dataEG,';');
                            fclose($handle);
                            
                            $data2="*";
                            $gitIgnoreConfig = $dossierConfig . "/.gitignore";
                    
                            mkdir($dossierStateCSV,0777, true);

                            $handle = fopen($gitIgnoreConfig,'w');
                            fwrite($handle, $data2);
                            fclose($handle);

                            $data1="#define NB_NODES $nbCartes";
                            $filename = $dossierConfig."/nbNodes.h";
                            $handle = fopen($filename,'w');
                            fwrite($handle, $data1);
                            fclose($handle);

                            for($i=1;$i<=$line[1];$i++){
                                $dossierPhysicalCSV = $dossierConfig."/physicalCSV_CN$i";
                                mkdir($dossierPhysicalCSV,0777, true);
                                mkdir($dossierPhysicalCSV."/physicalCONFIG",0777, true);
                                $filename = $dossierPhysicalCSV."/physicalCONFIG/physicalCONFIG_sensors.csv";
                                $handle = fopen($filename,'w');
                                $content = [];
                                $content[0] = ["Carte","Capteur","Type","Modbus remote slave address","Modbus start address","Modbus baud rate","Modbus parity","Modbus Data bits","Modbus Stop bit"];
                                fputcsv($handle, $content[0],';');
                                for ($j = 1; $j < 13; $j++) {
                                    $content[$j] = [$nom[$i - 1],"sensor$j","#","#","#","#","#","#","#"];
                                    fputcsv($handle, $content[$j],';');
                                }
                                fclose($handle);
                                $filename = $dossierPhysicalCSV."/physicalCONFIG/physicalCONFIG_valves.csv";
                                $handle = fopen($filename,'w');
                                $content = [];
                                $content[0] = ["Carte","Vannes","Etat initial","PORT GPIO"];
                                fputcsv($handle, $content[0],';');
                                for ($j = 1; $j < 13; $j++) {
                                    $content[$j] = [$nom[$i - 1],"valves$j","#","#"];
                                    fputcsv($handle, $content[$j],';');
                                }
                                fclose($handle);
                                $data1="#define NODEID $i\n";
                                $data2="#define NB_NODES $nbCartes";
                                $filename = $dossierPhysicalCSV."/nodeId.h";
                                $handle = fopen($filename,'w');
                                fwrite($handle, $data1);
                                fwrite($handle, $data2);
                                fclose($handle);
                                }
                        }else{
                            header('Location: Pages/pageAjoutConfig.php?erreurDossierConfigExiste');
                            exit();
                        }
                    }else{
                        header('Location: Pages/pageAjoutConfig.php?erreurNomsCartes');
                        exit();
                    }
                }
            }
            fclose($handleConfig);
        }
        header('Location: Pages/pageAjoutConfig.php?reussiteCreerConfig');
        break;

    case "creerTab":
        if (!empty($nomConfig) && !empty($nbCartes)) {
            $vide = implode('|', array_fill(0, $nbCartes, '#'));
            //Vérifie si le fichier configuration.csv existe. S'il n'existe pas, il est créé et ajout de la config dans le fichier
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
                    //la configuration existe deja donc redirection avec message d'erreur
                    header('Location: Pages/pageAjoutConfig.php?erreurConfigExiste');
                    exit;
                } else {
                    //la configuration n'existe pas donc ajout de la config dans le fichier
                    $handle = fopen($configurations, 'a');
                    if (fputcsv($handle, [$nomConfig, $nbCartes, $vide], ';')) {
                        $_SESSION['nomConfig'] = $nomConfig;
                    }
                    fclose($handle);
                }
            }
            $nomsCNs = explode('|', $vide); 
            // Crée un tableau qui sera utilisé pour contenir le nom des cartes
            for($i = 0; $i < $nbCartes; $i++) {
                $numCarte = "CN" . ($i+1);
                $nomCarte = $nomsCNs[$i];        
                $csvData[] = [$numCarte, $nomCarte];
            }
            $_SESSION['dataConfig'] = $csvData;
            $_SESSION['nbCartes'] = $nbCartes;
        }
        else {
            header('Location: Pages/pageAjoutConfig.php?erreurChampsNonRenseignes');
            exit();
        }
        header('Location: Pages/pageAjoutConfig.php?reussiteCreerTab');
        break;
    case "modif":
        if (!empty($_SESSION['nomConfig']) && isset($ligneIndex) && !empty($nomCarte)) {
            $nomConfig = $_SESSION['nomConfig'];
            $found = false;
        
            // Lire le fichier pour stocker toutes les données et repérer la ligne de la configuration
            if (($handle = fopen($configurations, 'r')) !== false) {
                while (($line = fgetcsv($handle, 1000, ";")) !== false) {
                    $csvData[] = $line;
                    if ($line[0] === $nomConfig) {
                        $found = true;
                        $currentConfigIndex = count($csvData) - 1; // Sauvegarder l'index de la configuration
                    }
                }
                fclose($handle);
            }
        
            // Si la configuration est trouvée dans configurations.csv, la modifier
            if ($found) {
                $currentConfig = $csvData[$currentConfigIndex]; //la configuration en cours de modif
                $nbCartes = $currentConfig[1];
                $noms = explode('|', $currentConfig[2]); //transforme "#|#" en noms[0]="#" et noms[1]="#"
                if (in_array($nomCarte, $noms)) {
                    //si le nom existe deja, redirection avec message d'erreur
                    header('Location: Pages/pageAjoutConfig.php?erreurNomExiste');
                    exit();
                }
                else{
                    $noms[$ligneIndex-1] = $nomCarte; // Modifier le nom de la carte spécifique
                
                    $currentConfig[2] = implode('|', $noms); // Réassembler la chaîne des noms de cartes
                    $csvData[$currentConfigIndex] = $currentConfig; // Mettre à jour la ligne dans le tableau
                    
                    // Réécrire le fichier CSV avec les nouvelles données
                    if (($handle = fopen($configurations, 'w')) !== false) {
                        foreach ($csvData as $line) {
                            fputcsv($handle, $line, ';');
                        }
                        fclose($handle);
                    } else {
                        header('Location: Pages/pageDeploiements.php?erreurConfigurationCSVecriture');
                        exit;
                    }
                    $csvData =[];
                    // Créer les nouvelles lignes pour chaque carte
                    for($i = 0; $i < $currentConfig[1]; $i++) {
                        $numCarte = "CN" . ($i+1);
                        $nomCarte = $noms[$i];        
                        $csvData[] = [$numCarte, $nomCarte];
                    }

                    $_SESSION['dataConfig']= $csvData;
                }
                
            } else {
                header('Location: Pages/pageAjoutConfig.php?erreurNonTrouve');
                exit();
            }
           

        }else{
            header('Location: Pages/pageAjoutConfig.php?erreurLigneNonCliquee');
            exit();
        }
        header('Location: Pages/pageAjoutConfig.php?reussiteModif');
        break;        
    default:
        break;
}

session_write_close();
exit();
?>
