<?php
$titre = "Erreur";
$page = "";
require 'header.inc.php';
$nom = "";
session_start(); // Démarrer la session
if (!empty($_POST['code']) && !empty($_POST['nom'])) {
    $codeEG = $_POST['code'];
    $nom = $_POST['nom'];

    $filePath = "liaisonEGEtat.csv";

    // Ouvrir le fichier en mode "ajout"
    $file = fopen($filePath, "a");

    // Vérifier si l'extension ".csv" est déjà présente dans $nom
    if (!preg_match('/\.csv$/', $nom)) {
        // Si l'extension n'est pas présente, ajoutez-la
        $csvFileName = $nom . '.csv';
    } else {
        // Si l'extension est déjà présente, utilisez simplement $nom
        $csvFileName = $nom;
    }

    // Stocker la valeur de csvFileName dans une variable de session
    $_SESSION['csvFileName'] = $csvFileName;
    session_write_close();
    if (!$file) {
        print("Erreur ! Impossible d ouvrir le fichier.");
        ?>
        <html><a class="btn btn-primary" href="AjoutCSV.php" role="button">Retour</a>
        </html><?php
        exit();
    }

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
    if (valuesExist($hexadecimal, $csvFileName, $filePath)) {
        print("Erreur ! Les valeurs existent deja dans le fichier.");
        ?>
        <html><a class="btn btn-primary" href="AjoutCSV.php" role="button">Retour</a>
        </html><?php
        exit();
    }

    if (!fwrite($file, $hexadecimal . ';' . $csvFileName . "\n")) {
        print("Erreur ! La valeur n a pas ete ajoutee.");
        ?>
        <html><a class="btn btn-primary" href="AjoutCSV.php" role="button">Retour</a>
        </html><?php
        exit();
    } else {
        fclose($file);

        // Tente d'ouvrir le fichier CSV en mode écriture, crée le fichier s'il n'existe pas
        if (($handle = fopen($csvFileName, 'w')) !== FALSE) {
            
            // Définition de l'entête du fichier CSV
            $entete = ["Carte", "Vannes/Etat", "Valeur", "Timer dependance", "Dependance vannes"];
            
            // Écriture de l'entête dans le fichier CSV
            fputcsv($handle, $entete, ';'); 
            
            // Fermeture du fichier
            fclose($handle);
            header('Location: Pages/pageAjoutCSV.php');
            exit();
        } else {
            echo "Erreur lors de la creation du fichier.";
            ?>
            <html><a class="btn btn-primary" href="AjoutCSV.php" role="button">Retour</a>
            </html><?php
        }
    }
} else {
    print("Erreur ! Les champs code et nom doivent etre remplis.");
    ?>
    <html><a class="btn btn-primary" href="AjoutCSV.php" role="button">Retour</a>
    </html><?php
}

// Fonction pour vérifier si les valeurs existent déjà dans le fichier
function valuesExist($codeEG, $csvFileName, $filePath) {
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
            if ($existingCode === $codeEG || $existingName === $csvFileName) {
                fclose($file);
                return true; // Retourner true si les valeurs existent déjà
            }
        }
    }

    fclose($file);
    return false; // Retourner false si les valeurs n'ont pas été trouvées
}
?>