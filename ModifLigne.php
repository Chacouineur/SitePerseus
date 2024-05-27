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
