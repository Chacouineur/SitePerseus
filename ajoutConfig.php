<?php 
$nomConfig = $_POST["config"];
$nbCartes = $_POST["nbCartes"];
$noms = $_POST["noms"];
$nomCarte = $_POST["nomCarte"];
$nom = explode('|', $noms);
$btnValue = $_POST["btnValue"];
$ligneIndex = $_POST['ligneIndex'];
if ((!empty($nomConfig) && !empty($nbCartes)) && $btnValue == "ajoutCorresp"){
    echo "ajoutCorresp";
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

        $data="*csv";
        $gitIgnoreState = $dossierStateCSV.'/.gitignore';
        $gitIgnoreCommon = $dossierCommonCSVFiles.'/.gitignore';
        
        $handle = fopen($gitIgnoreCommon,'w');
        fwrite($handle, $data);
        fclose($handle);

        mkdir($dossierStateCSV,0777, true);
        $handle = fopen($gitIgnoreState,'w');
        fwrite($handle, $data);
        fclose($handle);
        for($i=1;$i<=$nbCartes;$i++){
            $dossierPhysicalCSV = $dossierConfig."/physicalCSV_CN$i";
            mkdir($dossierPhysicalCSV,0777, true);

            $dossierSensorsCSV = $dossierPhysicalCSV."/sensorsCSV";
            mkdir($dossierSensorsCSV,0777, true);
            // Nom du fichier
            $filename = $dossierSensorsCSV."/physicalCONFIG_sensors.csv";
            $handle = fopen($filename,'w');
            $content = [];
            // Contenu du fichier
            $content[0] = ["Carte","Capteur","Etat initial","Min value","Max value"];
            fputcsv($handle, $content[0],';');
            for ($j = 1; $j < 13; $j++) {
                $content[$j] = [$nom[$i - 1],"sensorCH$j","#","#","#"];
                fputcsv($handle, $content[$j],';');
            }
            fclose($handle);

            $dossierValvesCSV = $dossierPhysicalCSV."/valvesCSV";
            mkdir($dossierValvesCSV,0777, true);
            // Nom du fichier
            $filename = $dossierValvesCSV."/physicalCONFIG_valves.csv";
            $handle = fopen($filename,'w');
            $content = [];
            // Contenu du fichier
            $content[0] = ["Carte","Vannes","Etat initial","PORT GPIO"];
            fputcsv($handle, $content[0],';');
            for ($j = 1; $j < 13; $j++) {
                $content[$j] = [$nom[$i - 1],"valves$j","#","#"];
                fputcsv($handle, $content[$j],';');
            }
            fclose($handle);

        }
    }
}
if((!empty($nomConfig) && !empty($nbCartes) && isset($ligneIndex) && !empty($nomCarte)) && $btnValue == "modif"){
    // Chemin vers le fichier CSV
    $configurations = __DIR__ . "/configurations.csv";

    $nom[$ligneIndex]=$nomCarte;
    $noms = implode('|',$nom);

    // Si le fichier n'existe pas encore
    if (!file_exists($configurations)) {
        $dataConfig = [['Nom', 'Nombre CNs', 'Noms CNs'], [$nomConfig, $nbCartes, $noms]];
        $handle = fopen($configurations, 'w');
        foreach ($dataConfig as $line) {
            fputcsv($handle, $line, ';');
        }
        fclose($handle);
    } else {
        // Le fichier existe déjà, on va chercher les données existantes
        $handle = fopen($configurations, 'r');
        $csvData = [];
        while (($line = fgetcsv($handle, 1000, ";")) !== false) {
            $csvData[] = $line;
        }
        fclose($handle);

        // Chercher la ligne correspondant au nom de la configuration
        $configIndex = -1;
        foreach ($csvData as $index => $line) {
            if ($line[0] === $nomConfig) {
                $configIndex = $index;
                break;
            }
        }

        // Si la configuration existe déjà, on met à jour la ligne
        if ($configIndex !== -1) {
            $csvData[$configIndex] = [$nomConfig, $nbCartes, $noms];
        } else {
            // Si la configuration n'existe pas, on ajoute une nouvelle ligne
            $csvData[] = [$nomConfig, $nbCartes, $noms];
        }

        // Écrire les données dans le fichier CSV
        $monFichier = fopen($configurations, "w");
        if (!$monFichier) {
            die("Impossible d'ouvrir le fichier $configurations pour écriture.");
        }

        foreach ($csvData as $line) {
            if (fputcsv($monFichier, $line, ';') === false) {
                die("Erreur lors de l'écriture dans le fichier $configurations.");
            }
        }
        fclose($monFichier);
    }
}
echo "erreur si rien";
header('Location: Pages/pageAjoutConfig.php');
exit();
?>
