<?php
$filePath = $_POST['FileName'];
$btnValue = $_POST['btnValue']; 
session_start();

// Vérifiez si le fichier existe avant de tenter de le supprimer
if (file_exists($filePath) && !empty($btnValue)) {
    switch($btnValue){
        case 'afficher':
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
            session_write_close();
            if (unlink($filePath)) {
                // Nom du fichier liaisonEGEtat.csv
                $liaisonFileName = 'liaisonEGEtat.csv';

                // Lire le contenu du fichier CSV dans un tableau
                $lines = file($liaisonFileName, FILE_IGNORE_NEW_LINES);

                // Vérifier si le fichier a pu être lu
                if ($lines === false) {
                    exit("Impossible de lire le fichier CSV.");
                }

                // Parcourir chaque ligne du fichier CSV
                foreach ($lines as $key => $line) {
                    // Vérifier si la ligne contient le nom du fichier à supprimer
                    if (strpos($line, $filePath) !== false) {
                        // Supprimer la ligne du tableau
                        unset($lines[$key]);
                        // Sortir de la boucle car la ligne a été trouvée et supprimée
                        break;
                    }
                }

                // Écrire le contenu mis à jour dans le fichier CSV
                file_put_contents($liaisonFileName, implode("\n", $lines));
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
