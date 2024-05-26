<?php
// Chemin du répertoire contenant les fichiers CSV
$directoryAppPath = "../FichiersDeploiement/versionsApp";

// Initialiser un tableau pour stocker les noms de dossiers
$foldersApp = [];

// Ouvrir le répertoire
if ($handle = opendir($directoryAppPath)) {
    // Parcourir chaque fichier dans le répertoire
    while (false !== ($entry = readdir($handle))) {
        // Ignorer les dossiers spécifiés
        if ($entry == "." || $entry == ".." || $entry == ".gitignore") {
            continue;
        }
        // Vérifier si l'entrée est un dossier
        if (is_dir($directoryAppPath . '/' . $entry)) {
            // Ajouter le dossier à la liste
            $foldersApp[] = $entry;
        }
    }
    // Fermer le gestionnaire de répertoire
    closedir($handle);
}

?>