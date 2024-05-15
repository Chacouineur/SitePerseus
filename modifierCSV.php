<?php
include 'rechercheCSV.php';
session_start();
$btnValue = $_POST['btnValue'];
$config = $_POST['config']; 

$boards = !empty($_SESSION['boards']) ? $_SESSION['boards'] : [];
$configName = !empty($_SESSION['configName']) ? $_SESSION['configName'] : [];


// Vérifiez si le fichier existe avant de tenter de le supprimer
if (!empty($btnValue)) {
    switch($btnValue){
        case 'config':
            $statesFiles =getCSVState($config);
            $_SESSION['statesFile']=$statesFiles;
            
            $boards = getBoard($config);
            $_SESSION['boards']=$boards;

            $_SESSION['configName']=$config;

            session_write_close();
            header('Location: Pages/pageModifCSV.php');
            exit();
            break;
        case 'afficher':
            $fileEG = $_POST['FileEG'];
            $fileActiv = $_POST['FileActiv'];
            $fileSensor = $_POST['FileSensor'];
            $fileValve = $_POST['FileValve'];
            if(!empty($fileEG)){
                $filePath = __DIR__."/Configurations/".$configName."/commonCSVFiles/stateCSV/".$fileEG;
                $_SESSION['fileName'] = $fileEG;
            }elseif(!empty($fileActiv)){
                $filePath = getCSVActivation($configName);
                $_SESSION['fileName'] = $fileActiv;
            }elseif(!empty($fileSensor)){
                $filePath = getCSVSensors($fileSensor,$boards,$configName);
                $_SESSION['fileName'] = $fileSensor;
            }elseif(!empty($fileValve)){
                $filePath = getCSVValves($fileValve,$boards,$configName);
                $_SESSION['fileName'] = $fileValve;
            }
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
            $valvesPath = getCSVValves($card,$board,$config);
            $sensorsPath = getCSVSensors($card,$board,$config); 
            break;
        default:
            break;
    }
    
    
} else {
    echo "Le fichier n existe pas et/ ou aucun bouton na ete appuye";
}

?>
