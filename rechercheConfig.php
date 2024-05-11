<?php
$configurations = __DIR__ . "../configurations.csv";

// Tableau pour stocker les noms de configuration
$configNames = [];

// Ouvrir le fichier configurations.csv en lecture
if (($handle = fopen($configurations, 'r')) !== false) {
    // Lire chaque ligne du fichier
    while (($line = fgetcsv($handle, 1000, ";")) !== false) {
        // Ignorer la première ligne qui contient les en-têtes
        if ($line[0] !== 'Nom') {
            // Ajouter le nom de la configuration au tableau
            $configNames[] = $line[0];
        }
    }
    fclose($handle);
}

?>
