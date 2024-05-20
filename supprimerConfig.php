<?php
$config = $_POST['config'];
$path = __DIR__."/Configurations/".$config;

if (is_dir($path)) {
    // Fonction pour supprimer récursivement un dossier et son contenu
    function deleteDirectory($dir) {
        // Si ce n'est pas un dossier ou un lien symbolique, le supprimer avec unlink
        if (!is_dir($dir)) {
            return unlink($dir);
        }

        // Parcourt tous les éléments dans le dossier
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            // Appel récursif pour supprimer les sous-dossiers et fichiers
            if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                // Si la suppression échoue, changer les permissions et réessayer
                chmod($dir . DIRECTORY_SEPARATOR . $item, 0777);
                if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                    return false;
                }
            }
        }

        // Supprime le dossier lui-même
        return rmdir($dir);
    }

    // Appel de la fonction pour supprimer le dossier spécifié
    if (deleteDirectory($path)) {
        $configCSVPath = __DIR__."/configurations.csv";

        // Lire le contenu du fichier dans un tableau
        $lines = file($configCSVPath, FILE_IGNORE_NEW_LINES);

        // Vérifier si la lecture du fichier a réussi
        if ($lines === false) {
            header('Location: Pages/pageSuppConfig.php?erreurFichierConfigurations');
            exit("Impossible de lire le fichier configurations.csv.");
        }

        // Initialiser un tableau pour stocker les lignes mises à jour
        $updatedLines = [];

        // Parcourir chaque ligne du fichier
        foreach ($lines as $line) {
            // Diviser la ligne en colonnes en utilisant le délimiteur ';'
            $data = str_getcsv($line, ';');

            // Vérifier si le nom de la configuration à supprimer apparaît dans la première colonne
            if ($data[0] != $config) {
                // Ajouter la ligne au tableau des lignes mises à jour
                $updatedLines[] = $line;
            }
        }

        // Écrire le contenu mis à jour dans le fichier configurations.csv
        if(file_put_contents($configCSVPath, implode("\n", $updatedLines)."\n")){
            header('Location: Pages/pageSuppConfig.php?reussiteSuppr');
            exit();
        }else{
            header('Location: Pages/pageSuppConfig.php?erreurEcritureConfig');
            exit();
        }
    } else {
        header('Location: Pages/pageSuppConfig.php?erreurDossierSuppr');
        exit();
    }
} else {
    header('Location: Pages/pageSuppConfig.php?erreurDossier');
    exit();
}
?>
