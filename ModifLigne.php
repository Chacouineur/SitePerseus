<?php
session_start();
$csvFileName = $_SESSION['csvFileName'];
$csvData = $_SESSION['csvData'];

// Initialiser un tableau pour stocker toutes les données du fichier CSV

if (!empty($_POST['carte']) && !empty($csvFileName) && !empty($_POST['btnValue'])) {
    $carte = $_POST['carte'];
    $vannesEtat = $_POST['vannesEtat'];
    $valeur = $_POST['valeur'];
    $timeDep = $_POST['timeDep'];
    $depVannes = isset($_POST['depVannes']) ? $_POST['depVannes'] : "#";
    $btnValue = $_POST['btnValue']; 
    $numLigne = $_POST['ligneIndex'];
    
    if($vannesEtat==null){
        $vannesEtat="#";
    }if($valeur==null){
        $valeur="#";
    }if($timeDep==null){
        $timeDep="#";
    }if($depVannes==null){
        $depVannes="#";
    }
    
    

    switch($btnValue){
        case "ajout":
            $monFichier = fopen($csvFileName, "a");
            if (!$monFichier){
                print("Impossible d'ouvrir le fichier");
                exit;
            }
            else{
                $ligne = [$carte, $vannesEtat, $valeur, $timeDep, $depVannes];
                    
                // Écriture de l'entête dans le fichier CSV
                fputcsv($monFichier, $ligne, ';'); 
                fclose($monFichier);
            }
            break;
        case "modif":
            if($numLigne){
                
            }
            break;
        case "suppr":
            if($numLigne){
                // Supprimer la ligne correspondant à $numLigne du tableau $csvData
                unset($csvData[$numLigne]);

                // Ouvrir le fichier CSV en mode écriture
                $monFichier = fopen($csvFileName, "w");

                if (!$monFichier){
                    print("Impossible d'ouvrir le fichier");
                    exit;
                }
                else{
                    // Parcourir les lignes restantes du tableau csvData et les écrire dans le fichier CSV
                    foreach ($csvData as $ligne) {
                        // Écrire chaque élément de la ligne dans le fichier CSV
                        fwrite($monFichier, implode(';', $ligne) . "\n");
                    }
                }

                fclose($monFichier);
            }
            
            break;
        default:
            echo "Erreur ! Aucun bouton n'a été appuyé.";
            break;
    }
    

    

    // Lire le contenu du fichier CSV dans un tableau
    $lines = file($csvFileName, FILE_IGNORE_NEW_LINES);
 
    // Parcourir chaque ligne du fichier CSV
    foreach ($lines as $line) {
        // Diviser chaque ligne en colonnes en utilisant le délimiteur ';' et stocker les colonnes dans un tableau
        $data = explode(';', $line);
        
        // Ajouter les données de chaque ligne dans le tableau csvData
        $fData[] = $data;
    }
    $_SESSION['csvData'] = $fData;

    // Rediriger vers la page AjoutCSV.php
    header('Location: Pages/pageAjoutCSV.php');
    exit();
} else {
    echo "Certains champs sont vides. Veuillez remplir tous les champs.";
}
?>
