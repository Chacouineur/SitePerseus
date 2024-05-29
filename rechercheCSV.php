<?php

function getCSVState($folder) {
    $directoryPath = __DIR__."/Configurations/".$folder."/commonCSVFiles/stateCSV/";
    $csvFiles = [];
    if ($handle = opendir($directoryPath)) {
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && strtolower(pathinfo($entry, PATHINFO_EXTENSION)) == 'csv') {
                $csvFiles[] = $entry;
            }
        }
        closedir($handle);
    }
    return $csvFiles;
}

function getBoard($folder) {
    $path = "configurations.csv";
    $boards =[];
    $handle = fopen($path,"r");
    while (($line = fgetcsv($handle, 1000, ";")) !== false) {
        if ($line[0] === $folder) {
            $boards = $line[2];
        }
    }
    $board = explode('|',$boards);
    fclose($handle);
    return $board;
}

function getCSVSensors($card,$board,$folder){
    $currentDir = __DIR__ ;
    for($i=0;$i<count($board);$i++){
        if($card === $board[$i]){
            if(strpos($currentDir, 'Pages')===true){
                $filePath = __DIR__."/../Configurations/".$folder."/physicalCSV_CN".($i+1)."/physicalCONFIG/physicalCONFIG_sensors.csv";
            }
            else{
                $filePath = __DIR__."/Configurations/".$folder."/physicalCSV_CN".($i+1)."/physicalCONFIG/physicalCONFIG_sensors.csv";
            }
            
        }
    }
    return $filePath;
}

function getCSVValves($card,$board,$folder){
    $currentDir = __DIR__ ;
    for($i=0;$i<count($board);$i++){
        if($card === $board[$i]){
            if(strpos($currentDir, 'Pages')===true){
                $filePath = __DIR__."/../Configurations/".$folder."/physicalCSV_CN".($i+1)."/physicalCONFIG/physicalCONFIG_valves.csv";
            }
            else{
                $filePath = __DIR__."/Configurations/".$folder."/physicalCSV_CN".($i+1)."/physicalCONFIG/physicalCONFIG_valves.csv";
            }
            
        }
    }
    return $filePath;
}

function getCSVActivation($folder){
    $currentDir = __DIR__ ;
    if(strpos($currentDir, 'Pages')===true){
        $filePath = __DIR__."/../Configurations/".$folder."/commonCSVFiles/activation.csv";
    }else{
        $filePath = __DIR__."/Configurations/".$folder."/commonCSVFiles/activation.csv";
    }
    
    return $filePath;
}

function getCorrespondance($fileName, $folder) {
    $filePath = __DIR__ . "/Configurations/" . $folder . "/commonCSVFiles/liaisonEGEtat.csv";
    
    if (($handle = fopen($filePath, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            // Vérifier si la deuxième colonne est égale à $fileName
            if (isset($data[1]) && $data[1] == $fileName) {
                fclose($handle); 
                return $data[0]; // Retourner la valeur de la correspondance EG
            }
        }
        fclose($handle); 
    } else {
        echo "Impossible d'ouvrir le fichier $filePath.";
        exit;
    }

    return null; // Retourner null si aucune correspondance n'est trouvée
}

?>