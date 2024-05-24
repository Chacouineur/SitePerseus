<?php
function tabData($csvFileName,$nomConfig){
    // Lire le contenu du fichier CSV dans un tableau
    $currentDir = __DIR__ ;
    if(strpos($currentDir, 'Pages')===true){
        $lines = file($currentDir . "/../Configurations/$nomConfig/commonCSVFiles/stateCSV/".$csvFileName, FILE_IGNORE_NEW_LINES);
    }else{
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

function tabDataPhysical($filePath){
    $path = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if ($path === false) {
        exit("Impossible de lire le fichier CSV.");
    }
    // Initialiser un tableau pour stocker les données du CSV
    $csvData = [];

    // Parcourir chaque ligne du fichier CSV
    foreach ($path as $line) {
        // Diviser chaque ligne en colonnes en utilisant le délimiteur ';' et stocker les colonnes dans un tableau
        $data = array_map('trim', explode(';', $line));
        
        // Ajouter les données de chaque ligne dans le tableau csvData
        $csvData[] = $data;
    }

    return $csvData;
}
?>
