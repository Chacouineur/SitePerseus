<?php
// Chemin du répertoire contenant les fichiers CSV
$directoryPath = "../Configurations";

// Initialiser un tableau pour stocker les noms de dossiers
$folders = [];

// Ouvrir le répertoire
if ($handle = opendir($directoryPath)) {
    // Parcourir chaque fichier dans le répertoire
    while (false !== ($entry = readdir($handle))) {
        // Ignorer les dossiers spécifiés
        if ($entry == "." || $entry == ".." || $entry == ".gitignore") {
            continue;
        }
        // Vérifier si l'entrée est un dossier
        if (is_dir($directoryPath . '/' . $entry)) {
            // Ajouter le dossier à la liste
            $folders[] = $entry;
        }
    }
    // Fermer le gestionnaire de répertoire
    closedir($handle);
}

?>