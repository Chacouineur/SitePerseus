<?php
$filePath = __DIR__."/commonCSVFiles/stateCSV/".$_POST['FileName'];
$fileName = $_POST['FileName'];
$btnValue = $_POST['btnValue']; 
session_start();

// Vérifiez si le fichier existe avant de tenter de le supprimer
if (file_exists($filePath) && !empty($btnValue)) {
    switch($btnValue){
        case 'afficher':
            $_SESSION['csvName'] = $fileName;
            $lines = file($filePath, FILE_IGNORE_NEW_LINES);
            // Vérifier si la fonction file() a réussi à lire le fichier
            if ($lines === false) {
                exit("Impossible de lire le fichier CSV.");
            }

            // Initialiser un tableau pour stocker les données du CSV
            $csvData = [];

            // Parcourir chaque ligne du fichier CSV
            foreach ($lines as $line) {
                // Diviser chaque ligne en colonnes en utilisant le délimiteur ';' et stocker les colonnes dans un tableau
                $data = explode(';', $line);
                
                // Ajouter les données de chaque ligne dans le tableau csvData
                $csvData[] = $data;
            }

            // Stocker les données dans la session
            $_SESSION['csvData'] = $csvData;
            session_write_close();
            header('Location: Pages/pageSuppCSV.php');
            exit();
            break;
        case 'supprimer':
            if($filePath == __DIR__."/commonCSVFiles/stateCSV/".$fileName){
                unset($_SESSION['csvName']);
            }
            if (unlink($filePath)) {
                // Chemin du fichier liaisonEGEtat.csv
                $liaisonFileName = __DIR__.'/commonCSVFiles/liaisonEGEtat.csv';

                // Lire le contenu du fichier dans un tableau
                $lines = file($liaisonFileName, FILE_IGNORE_NEW_LINES);

                // Vérifier si la lecture du fichier a réussi
                if ($lines === false) {
                    exit("Impossible de lire le fichier liaisonEGEtat.csv.");
                }

                // Initialiser un tableau pour stocker les lignes mises à jour
                $updatedLines = [];

                // Parcourir chaque ligne du fichier
                foreach ($lines as $line) {
                    // Diviser la ligne en colonnes en utilisant le délimiteur ';'
                    $data = explode(';', $line);

                    // Vérifier si le nom du fichier à supprimer apparaît dans la deuxième colonne ou dans la ligne
                    if ($data[1] != $fileName) {
                        // Ajouter la ligne au tableau des lignes mises à jour
                        $updatedLines[] = $line;
                    }
                }

                // Écrire le contenu mis à jour dans le fichier liaisonEGEtat.csv
                file_put_contents($liaisonFileName, implode("\n", $updatedLines)."\n");
                session_write_close();
                header('Location: Pages/pageSuppCSV.php');
                exit();
            } else {
                echo "Erreur lors de la suppression du fichier.";
            }
            break;
        default:
            break;
    }
    
    
} else {
    echo "Le fichier n existe pas et/ ou aucun bouton na ete appuye";
}

?>
