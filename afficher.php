<?php
function afficherData($csvFileName,$nomConfig){
    // Lire le contenu du fichier CSV dans un tableau
    $currentDir = __DIR__ ;
    if(strpos($currentDir, 'Pages')===true){
        echo 1;
        $lines = file($currentDir . "/../Configurations/$nomConfig/commonCSVFiles/stateCSV/".$csvFileName, FILE_IGNORE_NEW_LINES);
    }else{
        echo 2;
        $lines = file($currentDir . "/Configurations/$nomConfig/commonCSVFiles/stateCSV/".$csvFileName, FILE_IGNORE_NEW_LINES);
    }
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

    return $csvData;
    
}
?>
