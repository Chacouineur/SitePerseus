<?php

$fileName = $_POST['FileName'];
$btnValue = $_POST['btnValue']; 
$config = $_POST['config'];
session_start();
if($btnValue !== "config"){
    $path = __DIR__."/Configurations/".$_SESSION['configName']."/commonCSVFiles/stateCSV/".$_POST['FileName'];
}else{
    $path = __DIR__."/Configurations/".$config."/commonCSVFiles/stateCSV/";

}

function getCSVFiles($folder) {
    // Chemin du répertoire contenant les fichiers CSV
    $directoryPath = __DIR__."/Configurations/".$folder."/commonCSVFiles/stateCSV/";

    // Initialiser un tableau pour stocker les noms de fichiers CSV
    $csvFiles = [];

    // Ouvrir le répertoire
    if ($handle = opendir($directoryPath)) {
        // Parcourir chaque fichier dans le répertoire
        while (false !== ($entry = readdir($handle))) {
            // Vérifier si le fichier est un fichier CSV et différent de liaisonEGEtat.csv
            if ($entry != "." && $entry != ".." && strtolower(pathinfo($entry, PATHINFO_EXTENSION)) == 'csv') {
                // Ajouter le fichier à la liste
                $csvFiles[] = $entry;
            }
        }
        // Fermer le gestionnaire de répertoire
        closedir($handle);
    }

    return $csvFiles;
}

// Vérifiez si le fichier existe avant de tenter de le supprimer
if ((file_exists($path)||is_dir($path)) && !empty($btnValue)) {
    switch($btnValue){
        case 'afficher':
            $_SESSION['csvName'] = $fileName;
            $lines = file($path, FILE_IGNORE_NEW_LINES);
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
            if($path == __DIR__."/Configurations/".$_SESSION['configName']."/commonCSVFiles/stateCSV/".$fileName){
                unset($_SESSION['csvName']);
            }
            if (unlink($path)) {
                // Chemin du fichier liaisonEGEtat.csv
                $liaisonFileName = __DIR__."/Configurations/".$_SESSION['configName']."/commonCSVFiles/liaisonEGEtat.csv";

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
        case 'config':
            $_SESSION['csvEG']=getCSVFiles($config);
            $_SESSION['configName'] = $config;
            session_write_close();
            //header('Location: Pages/pageSuppCSV.php');
            exit();
            break;
        default:
            break;
    }
    
    
} else {
    echo "Le fichier n existe pas et/ ou aucun bouton na ete appuye";
}

?>
