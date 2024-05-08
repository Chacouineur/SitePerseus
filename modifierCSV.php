<?php
$fileName = $_POST['FileName'];
$btnValue = $_POST['btnValue']; 
if(file_exists(__DIR__."/commonCSVFiles/stateCSV/".$fileName)){
    $filePath = __DIR__."/commonCSVFiles/stateCSV/".$fileName;
}
elseif (file_exists(__DIR__."/commonCSVFiles/".$fileName)) {
    $filePath =__DIR__."/commonCSVFiles/".$fileName;
}
elseif(file_exists(__DIR__."/physicalCSV/sensorsCSV/".$fileName)){
    $filePath = __DIR__."/physicalCSV/sensorsCSV/".$fileName;
}
elseif(file_exists(__DIR__."/physicalCSV/valvesCSV/".$fileName)){
    $filePath = __DIR__."/physicalCSV/valvesCSV/".$fileName;
}else{
    $filePath = '';
}
session_start();

// Vérifiez si le fichier existe avant de tenter de le supprimer
if (file_exists($filePath) && !empty($btnValue)) {
    switch($btnValue){
        case 'afficher':
            $_SESSION['fileName'] = $fileName;
            $lines = file($filePath, FILE_IGNORE_NEW_LINES);
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
            $_SESSION['csvData2'] = $csvData;
            session_write_close();
            header('Location: Pages/pageModifCSV.php');
            exit();
            break;
        case 'modifier':
            
            break;
        default:
            break;
    }
    
    
} else {
    echo "Le fichier n existe pas et/ ou aucun bouton na ete appuye";
}

?>
