<?php
include 'rechercheCSV.php';
include 'afficher.php';
session_start();
unset($_SESSION['fileType']);
$btnValue = $_POST['btnValue'];
$config = $_POST['config'];

$boards = !empty($_SESSION['boards']) ? $_SESSION['boards'] : [];
$configName = !empty($_SESSION['configName']) ? $_SESSION['configName'] : [];

$csvFileName = !empty($_SESSION['fileName']) ? $_SESSION['fileName'] : [];

$csvData = $_SESSION['csvData2'];
$valvesPath = getCSVValves($card, $board, $config);
$sensorsPath = getCSVSensors($card, $board, $config);

// Vérifiez si le fichier existe avant de tenter de le supprimer
if (!empty($btnValue)) {
    switch ($btnValue) {
        case 'config':
            unset($_SESSION['fileName']);
            $statesFiles = getCSVState($config);
            $_SESSION['statesFile'] = $statesFiles;

            $boards = getBoard($config);
            $_SESSION['boards'] = $boards;

            $_SESSION['configName'] = $config;

            session_write_close();
            header('Location: Pages/pageModifCSV.php');
            exit();
            break;
        case 'afficher':
            unset($_SESSION['fileName']);
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
                exit("Impossible de lire le fichier CSV.");
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

                $monFichier = fopen($filePath, "r+");
                if (!$monFichier) {
                    die("Impossible d'ouvrir le fichier");
                }

                if ($numLigne && ($numLigne - 1) % 13 == 0) {
                    if ($csvData[$numLigne - 1][0] === "OFFSET") {
                        $carte = $csvData[$numLigne + 1][0];
                    }
                } else {
                    $ligne = [$csvData[$numLigne][0], $vannesEtat, $valeur, $timeDep, $concatenatedString];
                    $csvData[$numLigne] = $ligne;
                    $csvData[0] = ["Carte", "Vannes/Etat", "Valeur", "Timer dependance", "Dependance vannes"];
                    $csvData[1] = ["OFFSET", "EG", "#", "#", "#"];

                    echo $csvData[$numLigne][0] . " | ";
                    echo $vannesEtat . " | ";
                    echo $valeur . " | ";
                    echo $timeDep . " | ";
                    echo $concatenatedString . " | ";
                    
                    $monFichier = fopen($filePath, "w");
                    if (!$monFichier) {
                        die("Impossible d'ouvrir le fichier $filePath pour écriture.");
                    }

                    foreach ($csvData as $ligne) {
                        if (fputcsv($monFichier, $ligne, ';') === false) {
                            die("Erreur lors de l'écriture dans le fichier $filePath.");
                        }
                    }
                    fclose($monFichier);
                    $csvData = afficherData($csvFileName, $configName);
                    $_SESSION['csvData2'] = $csvData;
                }
            }
            break;
        default:
            break;
    }
    session_write_close();
} else {
    echo "Le fichier n'existe pas et/ou aucun bouton n'a été appuyé";
}
?>
