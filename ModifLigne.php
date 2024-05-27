<?php
session_start();
$csvFilePath = $_SESSION['csvFilePath'];
$csvData = $_SESSION['csvData'];

// Initialiser un tableau pour stocker toutes les données du fichier CSV

if (!empty($csvFilePath) && !empty($_POST['btnValue'])) {
    $carte = $_POST['carte'];
    $vannesEtat = $_POST['vannesEtat'];
    $valeur = $_POST['valeur'];
    $timeDep = $_POST['timeDep'];
    $depVannes = isset($_POST['checkboxes']) ? $_POST['checkboxes'] : [];

    $btnValue = $_POST['btnValue']; 
    $numLigne = $_POST['ligneIndex'];
    
    if(empty($vannesEtat)){
        $vannesEtat="#";
    }if($valeur !== '0' && $valeur !== '1'){
        $valeur="#";
    }if(empty($timeDep)){
        $timeDep="#";
    }if (empty($depVannes)) {
        $concatenatedString = "#";
    } else {
        $concatenatedString = implode('|', $depVannes);
    }    
    
    // Ouvrir le fichier CSV en mode écriture
    $monFichier = fopen($csvFilePath, "r+");  // 'r+' permet la lecture et l'écriture
    if (!$monFichier) {
        header('Location: Pages/pageAjoutCSV.php?erreurOuvrirfichier');
        exit();
    }

    switch($btnValue){
        case "modif":
            if ($numLigne && ($numLigne - 1) % 13 == 0) {
                // Vérifier si le nom de la carte de la ligne précédente est "OFFSET"
                if ($csvData[$numLigne - 1][0] === "OFFSET") {
                    // Utiliser le nom de la carte de la ligne suivante
                    $carte = $csvData[$numLigne + 1][0];
                }
                // Ne modifie pas la ligne
            } else {
                // Ignorer la modification du champ "Carte"
                $ligne = [$carte, $vannesEtat, $valeur, $timeDep, $concatenatedString];
                $csvData[$numLigne] = $ligne;
                $csvData[0] = ["Carte", "Vannes/Etat", "Valeur", "Timer dependance", "Dependance vannes"];
                $csvData[1] = ["OFFSET", "EG", "#", "#", "#"];
            
                $monFichier = fopen($csvFilePath, "w");
            
                if (!$monFichier) {
                    header('Location: Pages/pageAjoutCSV.php?erreurOuverture');
                    exit();
                }
            
                foreach ($csvData as $ligne) {
                    if (fputcsv($monFichier, $ligne, ';') === false) {
                        header('Location: Pages/pageAjoutCSV.php?erreurEcriture');
                        exit();
                    }
                }
            
                fclose($monFichier);

                // $valeurPath = explode('/',$csvFilePath );
                // $nomConfig = $valeurPath[2];
                // $pathActivationCSV = __DIR__."/Configurations/".$nomConfig."/commonCSVFiles/activation.csv";
                // if (($handle = fopen($pathActivationCSV, "r")) !== FALSE) {
                //     // Initialiser un tableau pour stocker les données
                //     $data = [];
                    
                //     // Lire toutes les lignes du fichier et stocker les données dans le tableau
                //     while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                //         $data[] = $row;
                //     }
                    
                //     // Fermer le fichier
                //     fclose($handle);
                    
                // } else {
                //     header('Location: Pages/pageAjoutCSV.php?erreurLectureActiv='.urlencode($pathActivationCSV));
                //     exit();
                // }

                // $indiceCarte = 0;
                // $nbCartes=0;
                // if (($handleConfig = fopen(__DIR__."/configurations.csv", 'r')) !== false) {              
                //     // Parcourir chaque ligne du fichier
                //     while (($line = fgetcsv($handleConfig, 1000, ";")) !== false) {
                //         if ($line[0] === $nomConfig) {
                //             $nbCartes = $line[1] ;
                //             $cartes = $line[2];
                //         }
                //     }
                //     fclose($handleConfig);
                // }
                
                // $nomCarte = explode('|',$cartes);
                // for($i=0;$i<$nbCartes;$i++){
                //     if($nomCarte[$i] === $carte){
                //         $indiceCarte = $i;
                //         // echo "Trouvé à la ligne :".$indiceCarte."<br>";
                //     }
                // }
                // // echo "1-".$numLigne."<br>";
                // $numLigne = (($numLigne-2) % 13);
                // // echo "2-".$numLigne."<br>";
                // $activLigne = $numLigne +2 +$indiceCarte*25;
                // // echo "3-".$activLigne."<br>";
                // $data[$activLigne] = [$carte,$vannesEtat,1];
                // // foreach($data[$activLigne] as $cell){
                // //     echo "|".$cell."|";
                // // }
                // $monFichier = fopen($pathActivationCSV, "w");
            
                // if (!$monFichier) {
                //     header('Location: Pages/pageAjoutCSV.php?erreurOuvertureActiv');
                //     exit();
                // }
            
                // foreach ($data as $ligne) {
                //     if (fputcsv($monFichier, $ligne, ';') === false) {
                //         header('Location: Pages/pageAjoutCSV.php?erreurEcritureActiv');
                //         exit();
                //     }
                // }
            
                // fclose($monFichier);
            }
            
            
            break;
            
        default:
            header('Location: Pages/pageAjoutCSV.php?erreurbtn');
            exit();
    }
    session_write_close(); 
    header('Location: Pages/pageAjoutCSV.php?reussiteModif');
    exit();
} else {
    header('Location: Pages/pageAjoutCSV.php?erreurChampsvides');
    exit();
}
?>
