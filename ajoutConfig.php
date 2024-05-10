<?php 
session_start();
$csvData=[];
$nomConfig = $_POST["config"];
$nbCartes = $_POST["nbCartes"];
$nomCarte = $_POST["nomCarte"];
$btnValue = $_POST["btnValue"];
$ligneIndex = $_POST['ligneIndex'];
$configurations = __DIR__ . "/configurations.csv";

switch($btnValue){
    case "ajoutCorresp":
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
                            $dataActiv = ["Carte","Vannes/Etat","Activation"];
                            $activationCSV = $dossierCommonCSVFiles."/activation.csv";
                            $handle = fopen($activationCSV, 'w');
                            fputcsv($handle, $dataActiv,';');
                            fclose($handle);
                    
                            $dataEG = ["Code EG","Nom fichier CSV"];
                            $liaisonEGEtat = $dossierCommonCSVFiles."/liaisonEGEtat.csv";
                            $handle = fopen($liaisonEGEtat, 'w');
                            fputcsv($handle, $dataEG,';');
                            fclose($handle);
                    
                            $data="*.csv";
                            $data2="*";
                            $gitIgnoreState = $dossierStateCSV.'/.gitignore';
                            $gitIgnoreCommon = $dossierCommonCSVFiles.'/.gitignore';
                            $gitIgnoreConfig = __DIR__."/Configurations/.gitignore";
                            
                            $handle = fopen($gitIgnoreCommon,'w');
                            fwrite($handle, $data);
                            fclose($handle);
                    
                            mkdir($dossierStateCSV,0777, true);
                            $handle = fopen($gitIgnoreState,'w');
                            fwrite($handle, $data);
                            fclose($handle);

                            $handle = fopen($gitIgnoreConfig,'w');
                            fwrite($handle, $data2);
                            fclose($handle);

                            for($i=1;$i<=$line[1];$i++){
                                $dossierPhysicalCSV = $dossierConfig."/physicalCSV_CN$i";
                                mkdir($dossierPhysicalCSV,0777, true);
                    
                                $dossierSensorsCSV = $dossierPhysicalCSV."/sensorsCSV";
                                mkdir($dossierSensorsCSV,0777, true);
                                
                                $filename = $dossierSensorsCSV."/physicalCONFIG_sensors.csv";
                                $handle = fopen($filename,'w');
                                $content = [];

                                $content[0] = ["Carte","Capteur","Etat initial","Min value","Max value"];
                                fputcsv($handle, $content[0],';');
                                for ($j = 1; $j < 13; $j++) {
                                    $content[$j] = [$nom[$i - 1],"sensorCH$j","#","#","#"];
                                    fputcsv($handle, $content[$j],';');
                                }
                                fclose($handle);
                    
                                $dossierValvesCSV = $dossierPhysicalCSV."/valvesCSV";
                                mkdir($dossierValvesCSV,0777, true);
                                
                                $filename = $dossierValvesCSV."/physicalCONFIG_valves.csv";
                                $handle = fopen($filename,'w');
                                $content = [];
                                
                                $content[0] = ["Carte","Vannes","Etat initial","PORT GPIO"];
                                fputcsv($handle, $content[0],';');
                                for ($j = 1; $j < 13; $j++) {
                                    $content[$j] = [$nom[$i - 1],"valves$j","#","#"];
                                    fputcsv($handle, $content[$j],';');
                                }
                                fclose($handle);
                                
                            }
                        }else{
                            header('Location: Pages/pageAjoutConfig.php?erreur');
                            exit();
                        }
                    }else{
                        header('Location: Pages/pageAjoutConfig.php?erreur');
                        exit();
                    }
                }
            }
            fclose($handleConfig);
        }
        break;

    case "creerTab":
        if (!empty($nomConfig) && !empty($nbCartes)) {
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
            
        }
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
        
            // Si la configuration est trouvée, la modifier
            if ($found) {
                $currentConfig = $csvData[$currentConfigIndex];
                $nbCartes = $currentConfig[1];
                $noms = explode('|', $currentConfig[2]);
                if (in_array($nomCarte, $noms)) {
                    header('Location: Pages/pageAjoutConfig.php?erreur=nom_existe');
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
                        die("Impossible d'ouvrir le fichier $configurations pour écriture.");
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
                header('Location: Pages/pageAjoutConfig.php?erreur=not_found');
                exit();
            }
           

        }else{
            header('Location: Pages/pageAjoutConfig.php?erreur=line_not_clicked');
            exit();
        }
        break;        
    default:
        break;
}

session_write_close();
header('Location: Pages/pageAjoutConfig.php');
exit();
?>
