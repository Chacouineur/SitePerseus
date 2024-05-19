<?php
// Chemin du répertoire contenant les fichiers CSV
$directoryPath = "../physicalCSV";

// Initialiser un tableau pour stocker les noms de fichiers CSV
$csvValvesFiles = [];

// Ouvrir le répertoire
if ($handle = opendir($directoryPath)) {
    // Parcourir chaque fichier dans le répertoire
    while (false !== ($entry = readdir($handle))) {
        // Vérifier si le fichier est un fichier CSV et différent de liaisonEGEtat.csv
        if ($entry != "." && $entry != ".." && strtolower(pathinfo($entry, PATHINFO_EXTENSION)) == 'csv') {
            // Ajouter le fichier à la liste
            $csvValvesFiles[] = $entry;
        }
    }
    // Fermer le gestionnaire de répertoire
    closedir($handle);
}

// Custom sorting function to sort files based on the numerical part of their names
usort($csvValvesFiles, function($a, $b) {
    // Extract the numerical part of the file names
    preg_match('/\d+/', $a, $matchesA);
    preg_match('/\d+/', $b, $matchesB);
    
    // Convert the extracted numerical parts to integers for comparison
    $numA = intval($matchesA[0]);
    $numB = intval($matchesB[0]);
    
    // Compare the numerical parts
    return $numA - $numB;
});

?>