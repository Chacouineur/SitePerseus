<?php
// Chemin du répertoire contenant les fichiers CSV
$directoryPath = __DIR__;

// Initialiser un tableau pour stocker les noms de fichiers CSV
$csvFiles = [];

// Ouvrir le répertoire
if ($handle = opendir($directoryPath)) {
    // Parcourir chaque fichier dans le répertoire
    while (false !== ($entry = readdir($handle))) {
        // Vérifier si le fichier est un fichier CSV et différent de liaisonEGEtat.csv
        if ($entry != "." && $entry != ".." && strtolower(pathinfo($entry, PATHINFO_EXTENSION)) == 'csv' && $entry != "liaisonEGEtat.csv") {
            // Ajouter le fichier à la liste
            $csvFiles[] = $entry;
        }
    }
    // Fermer le gestionnaire de répertoire
    closedir($handle);
}

?>