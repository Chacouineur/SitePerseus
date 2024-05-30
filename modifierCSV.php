<?php
include 'rechercheCSV.php';
include 'tabDatas.php';


session_start();

unset($_SESSION['fileType']);
$btnValue = $_POST['btnValue'];
$config = $_POST['config'];
;
$boards = !empty($_SESSION['boards']) ? $_SESSION['boards'] : [];
$configName = !empty($_SESSION['configName2']) ? $_SESSION['configName2'] : [];

$csvFileName = !empty($_SESSION['fileName']) ? $_SESSION['fileName'] : [];

$csvData = $_SESSION['csvData2'];

// Vérifiez si le fichier existe avant de tenter de le supprimer
if (!empty($btnValue)) {
    switch ($btnValue) {
        case 'config':
            unset($_SESSION['fileName']);
            $statesFiles = getCSVState($config);
            $_SESSION['statesFile'] = $statesFiles;
            echo "Config = ".$config ."<br>";
            foreach($statesFiles as $statesFile){
                echo "StateFile = ".$statesFile ."<br>";
            }
            $boards = getBoard($config);
            foreach($boards as $board){
                echo "Board = ".$board ."<br>";
            }
            $_SESSION['boards'] = $boards;

            $_SESSION['configName2'] = $config;

            session_write_close();
            header('Location: Pages/pageModifCSV.php');
            exit();
            break;
        case 'afficher':
            $fileEG = $_POST['FileEG'];
            $fileActiv = $_POST['FileActiv'];
            $fileSensor = $_POST['FileSensor'];
            $fileValve = $_POST['FileValve'];
            if (!empty($fileEG)) {
                $filePath = __DIR__ . "/Configurations/" . $configName . "/commonCSVFiles/stateCSV/" . $fileEG;
                $_SESSION['fileName'] = $fileEG;
            } elseif (!empty($fileActiv)) {
                $filePath = getCSVActivation($configName);
                $_SESSION['fileName'] = $fileActiv;
            } elseif (!empty($fileSensor)) {
                $filePath = getCSVSensors($fileSensor, $boards, $configName);
                $_SESSION['fileName'] = $fileSensor;
                $_SESSION['fileType'] = 'sensor';
            } elseif (!empty($fileValve)) {
                $filePath = getCSVValves($fileValve, $boards, $configName);
                $_SESSION['fileName'] = $fileValve;
                $_SESSION['fileType'] = 'valve';
            }
            $lines = file($filePath, FILE_IGNORE_NEW_LINES);
            if ($lines === false) {
                echo "Impossible de lire le fichier CSV.";
            }

            $csvData = [];
            foreach ($lines as $line) {
                $data = explode(';', $line);
                $csvData[] = $data;
            }

            $_SESSION['csvData2'] = $csvData;
            session_write_close();
            header('Location: Pages/pageModifCSV.php');
            exit();
            break;
        case 'modifState':
            $filePath = __DIR__ . "/Configurations/" . $configName . "/commonCSVFiles/stateCSV/" . $csvFileName;
            
            if (!empty($csvFileName) && !empty($_POST['btnValue'])) {
                $carte = $_POST['carte'];
                $vannesEtat = $_POST['vannesEtat'];
                $valeur = $_POST['valeur'];
                $timeDep = $_POST['timeDep'];
                $depVannes = isset($_POST['checkboxes']) ? $_POST['checkboxes'] : [];

                $numLigne = $_POST['ligneIndex'];

                if (empty($vannesEtat)) {
                    $vannesEtat = "#";
                }
                if ($valeur !== '0' && $valeur !== '1') {
                    $valeur = "#";
                }
                if (empty($timeDep)) {
                    $timeDep = "#";
                }
                if (empty($depVannes)) {
                    $concatenatedString = "#";
                } else {
                    $concatenatedString = implode('|', $depVannes);
                }

                if ($numLigne && ($numLigne - 1) % 13 == 0) {
                    // Vérifier si le nom de la carte de la ligne précédente est "OFFSET"
                    if ($csvData[$numLigne - 1][0] === "OFFSET") {
                        // Utiliser le nom de la carte de la ligne suivante
                        $carte = $csvData[$numLigne + 1][0];
                    }
                    // Ne modifie pas la ligne
                } else {
                    // Ignorer la modification du champ "Carte"
                    $ligne = [$carte, $vannesEtat, $valeur, $timeDep, $concatenatedString];
                    $csvData[$numLigne] = $ligne;
                    $csvData[0] = ["Carte", "Vannes/Etat", "Valeur", "Timer dependance", "Dependance vannes"];
                    $csvData[1] = ["OFFSET", "EG", "#", "#", "#"];
                
                    $monFichier = fopen($filePath, "w");
                
                    if (!$monFichier) {
                        header('Location: Pages/pageModifCSV.php?erreurOuverture');
                        exit();
                    }
                
                    foreach ($csvData as $ligne) {
                        if (fputcsv($monFichier, $ligne, ';') === false) {
                            header('Location: Pages/pageModifCSV.php?erreurEcriture');
                            exit();
                        }
                    }
                
                    fclose($monFichier);
                    $csvData = tabData($csvFileName, $configName);
                    $_SESSION['csvData2'] = $csvData;
                }    
            }else{
                echo "Fichier introuvable!";
            }
            break;


            case "modifSensor":
                $filePath = getCSVSensors($csvFileName, $boards, $configName);

                if (!empty($csvFileName) && !empty($_POST['btnValue'])) {
                    //Récuperation des valeurs du formulaire
                    $carte = $_POST['carte'];
                    $capteur = $_POST['capteur'];
                    $type = $_POST['type'];
                    $modbusRemoteSlaveAdress = $_POST['modbusRemoteSlaveAdress'];
                    $modbusStartAdress = $_POST['modbusStartAdress'];
                    $modbusBaudRate = $_POST['modbusBaudRate'];
                    $modbusParity = $_POST['modbusParity'];
                    $modbusDataBits = $_POST['modbusDataBits'];
                    $modbusStopBits = $_POST['modbusStopBits'];
                    $numLigne = $_POST['ligneIndex'];

                    //mettre "#" dans le champ de la variable si elle est nulle
                    $carte = empty($carte) ? '#' : $carte;
                    $capteur = empty($capteur) ? '#' : $capteur;
                    $type = empty($type) ? '#' : $type;
                    $modbusRemoteSlaveAdress = $modbusRemoteSlaveAdress === '' ? '#' : $modbusRemoteSlaveAdress;
                    $modbusStartAdress = $modbusStartAdress === '' ? '#' : $modbusStartAdress;
                    $modbusBaudRate = empty($modbusBaudRate) ? '#' : $modbusBaudRate;
                    $modbusParity = empty($modbusParity) ? '#' : $modbusParity;
                    $modbusDataBits = empty($modbusDataBits) ? '#' : $modbusDataBits;
                    $modbusStopBits = empty($modbusStopBits) ? '#' : $modbusStopBits;
                    //Valeur de la ligne modifié
                    $ligne = [$carte, $capteur, $type, $modbusRemoteSlaveAdress, $modbusStartAdress, $modbusBaudRate, $modbusParity, $modbusDataBits, $modbusStopBits];
                    
                    // Lire le fichier CSV existant dans un tableau
                    $csvData = [];
                    if (($handle = fopen($filePath, 'r')) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            $csvData[] = $data;
                        }
                        fclose($handle);
                    }
                    //mettre la ligne modifié à l'indice cliqué $numLigne dans le tableau récupéré  $csvData[]
                    $csvData[$numLigne] = $ligne;             
                    //réécrire l'entete du fichier    
                    $csvData[0] = ["Carte", "Capteur", "Type", "Modbus remote slave address", "Modbus start address", "Modbus baud rate", "Modbus parity", "Modbus Data bits", "Modbus Stop bit"];

                    // Ouvrir le fichier pour l'écriture
                    $monFichier = fopen($filePath, "w");
                    if (!$monFichier) {
                        echo "Impossible d'ouvrir le fichier $filePath pour écriture.";
                        exit;
                    }

                    // Écrire les données dans le fichier CSV
                    foreach ($csvData as $ligne) {
                        // Nettoyage des sauts de ligne indésirables
                        $ligne = array_map(function($field) {
                            return str_replace(["\n", "\r"], '', $field);
                        }, $ligne);

                        if (fputcsv($monFichier, $ligne, ';') === false) {
                            echo "Erreur lors de l'écriture dans le fichier $filePath.";
                            exit;
                        }
                    }
                    fclose($monFichier);
                    $csvData = tabDataPhysical($filePath);
                    $_SESSION['csvData2'] = $csvData;
                    $_SESSION['fileType'] = 'sensor';

                    // Lecture du fichier activation.csv afin de mettre ces valeurs dans un tableau $data[]
                    $pathActivationCSV = __DIR__ . "/Configurations/" . $configName . "/commonCSVFiles/activation.csv";
                    if (($handle = fopen($pathActivationCSV, "r")) !== FALSE) {
                        // Initialiser un tableau pour stocker les données
                        $data = [];
                        
                        // Lire toutes les lignes du fichier et stocker les données dans le tableau
                        while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            $data[] = $row;
                        }
                        
                        // Fermer le fichier
                        fclose($handle);
                        
                    } else {
                        header('Location: Pages/pageModifCSV.php');
                        exit();
                    }
                    //lire le fichier du capteur afin de mettre les valeurs dans un tableau s'il existe
                    if (($handle = fopen($filePath, "r")) !== FALSE) {
                        // Initialiser un tableau pour stocker les données
                        $dataSensor = [];
                        
                        // Lire toutes les lignes du fichier et stocker les données dans le tableau
                        while (($rowS = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            //faire un tableau de toutes les valeurs de la 2e colonne sans l'entete
                            if ($rowS[1] !== 'Capteur') {
                                $dataSensor[] = $rowS;
                            }
                        }
                        // Fermer le fichier
                        fclose($handle);
                        
                    } else {
                        header('Location: Pages/pageModifCSV.php');
                        exit();
                    }
                    $indiceCarte = 0;
                    $nbCartes = 0;
                    //lis le fichier configuration.csv
                    if (($handleConfig = fopen(__DIR__ . "/configurations.csv", 'r')) !== false) {              
                        // Parcourir chaque ligne du fichier
                        while (($line = fgetcsv($handleConfig, 1000, ";")) !== false) {
                            if ($line[0] === $configName) {
                                $nbCartes = $line[1];
                                $cartes = $line[2];
                            }
                        }
                        fclose($handleConfig);
                    }
                    //récupère l'indice de la carte. Ex: $cartes=['CACMO','CACOE'] donc si $carte est CACMO, j'obtiens $indiceCarte = 0 et $nomCard
                    $nomCarte = explode('|', $cartes);
                    for ($i = 0; $i < $nbCartes; $i++) {
                        if ($nomCarte[$i] === $carte) {
                            $indiceCarte = $i;
                            $nomCard = $nomCarte[$i];
                        }
                    }
                    
                    for ($i = 0; $i < 12; $i++) {
                        $indice = $i + 14 + $indiceCarte * 25;
                        if ($dataSensor[$i][1] !== 'sensor' . ($i + 1)) {
                            $data[$indice] = [$carte, $dataSensor[$i][1], 1];
                            foreach ($data[$indice] as $cell) {
                                echo $cell . " | ";
                            }
                            echo "<br>";
                        }
                    }
                    
                    $handle = fopen($pathActivationCSV, "w");

                    if (!$handle) {
                        header('Location: Pages/pageModifCSV.php');
                        exit();
                    }
                    foreach ($data as $ligne) {
                        if (fputcsv($handle, $ligne, ';') === false) {
                            header('Location: Pages/pageModifCSV.php');
                            exit();
                        }
                    }
                    fclose($handle);
                }
                break;
            case 'modifValve':
                $filePath = getCSVValves($csvFileName, $boards, $configName);

                if (!empty($csvFileName) && !empty($_POST['btnValue'])) {
                    $carte = $_POST['carte'];
                    $vannesEtat = $_POST['vannesEtat'];
                    $etatInit = $_POST['EtatInit'];
                    $portGPIO = $_POST['portGPIO'];
                    $numLigne = $_POST['ligneIndex'];

                    $carte = empty($carte) ? '#' : $carte;
                    $vannesEtat = empty($vannesEtat) ? '#' : $vannesEtat;
                    $etatInit = $etatInit === '' ? '#' : $etatInit;
                    $portGPIO = $portGPIO === '' ? '#' : $portGPIO;

                    $ligne = [$carte, $vannesEtat, $etatInit, $portGPIO];
                    $csvData[$numLigne] = $ligne;                 
                    $csvData[0] = ["Carte", "Vannes", "Etat initial", "PORT GPIO"];

                    // Lire le fichier CSV existant dans un tableau
                    $csvData = [];
                    if (($handle = fopen($filePath, 'r')) !== FALSE) {
                        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            $csvData[] = $data;
                        }
                        fclose($handle);
                    }

                    $csvData[$numLigne] = $ligne;                 
                    $csvData[0] = ["Carte", "Vannes", "Etat initial", "PORT GPIO"];

                    // Ouvrir le fichier pour l'écriture
                    $monFichier = fopen($filePath, "w");
                    if (!$monFichier) {
                        echo "Impossible d'ouvrir le fichier $filePath pour écriture.";
                        exit;
                    }

                    // Écrire les données dans le fichier CSV
                    foreach ($csvData as $ligne) {
                        // Nettoyage des sauts de ligne indésirables
                        $ligne = array_map(function($field) {
                            return str_replace(["\n", "\r"], '', $field);
                        }, $ligne);

                        if (fputcsv($monFichier, $ligne, ';') === false) {
                            echo "Erreur lors de l'écriture dans le fichier $filePath.";
                            exit;
                        }
                    }
                    fclose($monFichier);
                    $csvData = tabDataPhysical($filePath);
                    $_SESSION['csvData2'] = $csvData;
                    $_SESSION['fileType'] = 'valve';

                    // Lecture du fichier activation.csv afin de mettre ces valeurs dans un tableau $data[]
                    // Les noms des valves de physicalCONFIG_valves.csv seront ensuite injectés dans la 2e colonne du tableau $data[]
                    $pathActivationCSV = __DIR__ . "/Configurations/" . $configName . "/commonCSVFiles/activation.csv";
                    if (($handle = fopen($pathActivationCSV, "r")) !== FALSE) {
                        // Initialiser un tableau pour stocker les données
                        $data = [];
                        
                        // Lire toutes les lignes du fichier et stocker les données dans le tableau
                        while (($row = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            $data[] = $row;
                        }
                        
                        // Fermer le fichier
                        fclose($handle);
                        
                    } else {
                        header('Location: Pages/pageModifCSV.php');
                        exit();
                    }
                    if (($handle = fopen($filePath, "r")) !== FALSE) {
                        // Initialiser un tableau pour stocker les données
                        $dataValve = [];
                        
                        // Lire toutes les lignes du fichier et stocker les données dans le tableau
                        while (($rowV = fgetcsv($handle, 1000, ";")) !== FALSE) {
                            if ($rowV[1] !== 'Vannes') {
                                $dataValve[] = $rowV;
                            }
                        }
                        
                        // Fermer le fichier
                        fclose($handle);
                        
                    } else {
                        header('Location: Pages/pageModifCSV.php');
                        exit();
                    }
                    $indiceCarte = 0;
                    $nbCartes = 0;
                    
                    if (($handleConfig = fopen(__DIR__ . "/configurations.csv", 'r')) !== false) {              
                        // Parcourir chaque ligne du fichier
                        while (($line = fgetcsv($handleConfig, 1000, ";")) !== false) {
                            if ($line[0] === $configName) {
                                $nbCartes = $line[1];
                                $cartes = $line[2];
                            }
                        }
                        fclose($handleConfig);
                    }
                    
                    $nomCarte = explode('|', $cartes);
                    for ($i = 0; $i < $nbCartes; $i++) {
                        if ($nomCarte[$i] === $carte) {
                            $indiceCarte = $i;
                            $nomCard = $nomCarte[$i];
                        }
                    }
                    
                    for ($i = 0; $i < 12; $i++) {
                        $indice = $i + 2 + $indiceCarte * 25;
                        if ($dataValve[$i][1] !== 'valves' . ($i + 1)) {
                            $data[$indice] = [$carte, $dataValve[$i][1], 1];
                        }
                    }
                    
                    $handle = fopen($pathActivationCSV, "w");

                    if (!$handle) {
                        header('Location: Pages/pageModifCSV.php');
                        exit();
                    }
                    foreach ($data as $ligne) {
                        if (fputcsv($handle, $ligne, ';') === false) {
                            header('Location: Pages/pageModifCSV.php');
                            exit();
                        }
                    }

                    fclose($handle);
                }

                break;
            case 'modifActiv':
                
                $filePath = getCSVActivation($configName);
                $numLigne = $_POST['ligneIndex'];
                if (!empty($csvFileName) && !empty($_POST['btnValue']) && $numLigne > 1) {
                    $carte = $_POST['carte'];
                    $vannesEtat = $_POST['vannesEtat'];
                    $exampleRadios = $_POST['exampleRadios'];                    
            
                    $carte = empty($carte) ? '#' : $carte;
                    $vannesEtat = empty($vannesEtat) ? '#' : $vannesEtat;
                    $exampleRadios = $exampleRadios==='' ? '#' : $exampleRadios;
            
                    $ligne = [$carte, $vannesEtat, $exampleRadios];
                    $csvData[$numLigne] = $ligne;                 
                    $csvData[0] = ["Carte", "Vannes/Etat", "Activation"];
                    
                    $monFichier = fopen($filePath, "r+");
                    if (!$monFichier) {
                        echo "Impossible d'ouvrir le fichier $filePath pour écriture.";
                        exit;
                    }
            
                    foreach ($csvData as $ligne) {
                        // Nettoyage des sauts de ligne indésirables
                        $ligne = array_map(function($field) {
                            return str_replace(["\n", "\r"], '', $field);
                        }, $ligne);
            
                        if (fputcsv($monFichier, $ligne, ';') === false) {
                            echo "Erreur lors de l'écriture dans le fichier $filePath.";
                            exit;
                        }
                    }
                    fclose($monFichier);
                    $csvData = tabDataPhysical($filePath);
                    $_SESSION['csvData2'] = $csvData;
                }
                break;
        default:
            break;
    }
    session_write_close();
    header('Location: Pages/pageModifCSV.php');
    exit();
} else {
    echo "Le fichier n'existe pas et/ou aucun bouton n'a été appuyé";
}
?>
