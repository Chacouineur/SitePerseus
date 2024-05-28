<?php
$nom = "";
include 'rechercheCSV.php';
session_start(); // Démarrer la session
if (!empty($_POST['code']) && !empty($_POST['nom'])) {
    $codeEG = $_POST['code'];
    $nom = $_POST['nom'];
    $nomConfig = $_POST['config'];

    $_SESSION['configName'] = $nomConfig;

    $filePath = __DIR__ . "/Configurations/$nomConfig/commonCSVFiles/liaisonEGEtat.csv";

    // Ouvrir le fichier en mode "ajout"
    $file = fopen($filePath, "a");

    // Vérifier si l'extension ".csv" est déjà présente dans $nom
    if (!preg_match('/\.csv$/', $nom)) {
        // Si l'extension n'est pas présente, ajoutez-la
        $csvFilePath = __DIR__ . "/Configurations/$nomConfig/commonCSVFiles/stateCSV/" . $nom . '.csv';
        $nom = $nom . '.csv';
    } else {
        // Si l'extension est déjà présente, utilisez simplement $nom
        $csvFilePath = __DIR__ . "/Configurations/$nomConfig/commonCSVFiles/stateCSV/" . $nom;
    }

    $_SESSION['csvFileName'] = $nom;
    $_SESSION['csvFilePath'] = $csvFilePath;

    if (!$file) {
        unset($_SESSION['csvFileName']);
        unset($_SESSION['csvFilePath']);
        header('Location: Pages/pageAjoutCSV.php?erreurFichier');
        exit();
    }

    $configurations = __DIR__ . "/configurations.csv";

    $cartesConfig = [];
    $nbConfig = [];

    if (($handle = fopen($configurations, 'r')) !== false) {
        while (($line = fgetcsv($handle, 1000, ";")) !== false) {
            // Ignorer la première ligne qui contient les en-têtes
            if ($line[0] == $nomConfig) {
                $cartesConfig = $line[2];
                $nbConfig = $line[1];
            }
        }
        fclose($handle);
    }

    $cartes = explode('|', $cartesConfig);

    // Vérifier si la valeur commence par "0x" pour déterminer si elle est en hexadécimal
    if (substr($codeEG, 0, 2) === "0x") {
        // Supprimer le préfixe "0x"
        $hexadecimal = substr($codeEG, 2);

        // Remplir les zéros à gauche pour obtenir une longueur de 4 caractères
        $hexadecimal = str_pad($hexadecimal, 4, '0', STR_PAD_LEFT);
    } else {
        // Convertir en entier si la valeur est une chaîne de caractères numérique
        $codeEG = is_numeric($codeEG) ? intval($codeEG) : $codeEG;

        // Convertir en hexadécimal avec un format fixe sur 16 bits (4 caractères)
        $hexadecimal = sprintf("%04X", $codeEG);
    }

    // Ajouter le préfixe "0x"
    $hexadecimal = "0x" . $hexadecimal;

    // Vérifier si les valeurs existent déjà dans le fichier
    if (valuesExist($hexadecimal, $nom, $filePath)) {
        unset($_SESSION['csvFileName']);
        unset($_SESSION['csvFilePath']);
        header('Location: Pages/pageAjoutCSV.php?erreurValeursExistent');
        exit();
    }

    if (!fwrite($file, $hexadecimal . ';' . $nom . "\n")) {
        unset($_SESSION['csvFileName']);
        unset($_SESSION['csvFilePath']);
        header('Location: Pages/pageAjoutCSV.php?erreurValeursAjout');
        exit();
    } else {
        fclose($file);

        if (($handle = fopen($csvFilePath, 'w')) !== FALSE) {
            $entete = ["Carte", "Vannes/Etat", "Valeur", "Timer dependance", "Dependance vannes"];
            fputcsv($handle, $entete, ';');

            $ligne1 = ["OFFSET", "EG", "#", "#", "#"];
            fputcsv($handle, $ligne1, ';');

            for ($i = 0; $i < $nbConfig; $i++) {
                $filePath = getCSVValves($cartes[$i], getBoard($nomConfig), $nomConfig);
                if (($handleValves = fopen($filePath, "r")) !== FALSE) {
                    // Initialiser un tableau pour stocker les données
                    $dataValves = [];

                    // Lire toutes les lignes du fichier et stocker les données dans le tableau
                    while (($rowS = fgetcsv($handleValves, 1000, ";")) !== FALSE) {
                        if ($rowS[1] !== 'Vannes') {
                            $dataValves[] = $rowS;
                        }
                    }

                    // Fermer le fichier
                    fclose($handleValves);
                }
                for ($j = 0; $j < 12; $j++) {
                    $ligne = [$cartes[$i], $dataValves[$j][1], "#", "#", "#"];
                    fputcsv($handle, $ligne, ';');
                }
                fputcsv($handle, $ligne1, ';');
            }
            // Fermeture du fichier
            fclose($handle);
            unset($_SESSION['csvData']);
        } else {
            header('Location: Pages/pageModifCSV.php');
            exit();
        }

        session_write_close();
        header('Location: Pages/pageAjoutCSV.php?reussite');
        exit();
    }
} else {
    unset($_SESSION['csvFileName']);
    unset($_SESSION['csvFilePath']);
    header('Location: Pages/pageAjoutCSV.php?erreurChampsVide');
    exit();
}

// Fonction pour vérifier si les valeurs existent déjà dans le fichier
function valuesExist($codeEG, $nom, $filePath) {
    // Ouvrir le fichier en mode lecture
    $file = fopen($filePath, "r");

    if (!$file) {
        return false; // Retourner false si le fichier n'a pas pu être ouvert
    }

    // Parcourir chaque ligne du fichier
    while (($line = fgets($file)) !== false) {
        // Séparer la ligne en deux parties : code et nom
        $values = explode(';', $line);

        // S'assurer que la ligne contient au moins deux parties (code et nom)
        if (count($values) >= 2) {
            // Récupérer le code et le nom
            $existingCode = trim($values[0]);
            $existingName = trim($values[1]);

            // Comparer le code et le nom avec les valeurs fournies
            if ($existingCode === $codeEG || $existingName === $nom) {
                fclose($file);
                return true; // Retourner true si les valeurs existent déjà
            }
        }
    }

    fclose($file);
    return false; // Retourner false si les valeurs n'ont pas été trouvées
}

unset($_SESSION['csvData']);
session_write_close();
?>
