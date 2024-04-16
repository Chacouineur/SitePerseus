<?php
session_start();
$csvFileName = $_SESSION['csvFileName'];
$csvData = $_SESSION['csvData'];

// Initialiser un tableau pour stocker toutes les données du fichier CSV

if (!empty($_POST['carte']) && !empty($csvFileName) && !empty($_POST['btnValue'])) {
    $carte = $_POST['carte'];
    $vannesEtat = $_POST['vannesEtat'];
    $valeur = $_POST['valeur'];
    $timeDep = $_POST['timeDep'];
    $depVannes = isset($_POST['depVannes']) ? $_POST['depVannes'] : "#";
    $btnValue = $_POST['btnValue']; 
    $numLigne = $_POST['ligneIndex'];
    
    if($vannesEtat==null){
        $vannesEtat="#";
    }if($valeur==null){
        $valeur="#";
    }if($timeDep==null){
        $timeDep="#";
    }if($depVannes==null){
        $depVannes="#";
    }
    
    // Ouvrir le fichier CSV en mode écriture
    $monFichier = fopen($csvFileName, "r+");  // 'r+' permet la lecture et l'écriture
    if (!$monFichier) {
        die("Impossible d'ouvrir le fichier");
    }

    switch($btnValue){
        case "ajout":
            // Compter le nombre de lignes pour la carte spécifiée
            $nombreDeLignesParCarte = 0;
            while (($data = fgetcsv($monFichier, 1000, ";")) !== FALSE) {
                if ($data[0] === $carte) {
                    $nombreDeLignesParCarte++;
                }
            }

            // Ajouter une nouvelle ligne si le nombre de lignes est inférieur à 12
            if ($nombreDeLignesParCarte < 12) {
                
                fseek($monFichier, 0, SEEK_END); // Aller à la fin du fichier pour l'écriture

                $ligne = [$carte, $vannesEtat, $valeur, $timeDep, $depVannes];
                fputcsv($monFichier, $ligne, ';');
            } else {
                echo "La limite de 12 lignes pour la carte $carte a été atteinte.";
            }

            fclose($monFichier);
            break;
        case "ajoutMax":
            // Compter le nombre de lignes pour la carte spécifiée
            $nombreDeLignesParCarte = 0;
            while (($data = fgetcsv($monFichier, 1000, ";")) !== FALSE) {
                if ($data[0] === $carte) {
                    $nombreDeLignesParCarte++;
                }
            }

            // Ajouter une nouvelle ligne si le nombre de lignes est inférieur à 12
            if ($nombreDeLignesParCarte < 12) {
                
                fseek($monFichier, 0, SEEK_END); // Aller à la fin du fichier pour l'écriture
                $ligne = [$carte, $vannesEtat, $valeur, $timeDep, $depVannes];
                $ligneVide = [$carte,"#","#","#","#"];
                $offset = ["OFFSET","GE","#","#","#"];
                fputcsv($monFichier, $ligne, ';');
                for($nombreDeLignesParCarte+1;$nombreDeLignesParCarte+1<12;$nombreDeLignesParCarte++){
                    fputcsv($monFichier, $ligneVide, ';');
                }
                fputcsv($monFichier, $offset, ';');
                
            } else {
                echo "La limite de 12 lignes pour la carte $carte a été atteinte.";
            }

            fclose($monFichier);
            break;
        case "modif":
            if ($numLigne) {
                $ligne = [$carte, $vannesEtat, $valeur, $timeDep, $depVannes];
                $csvData[$numLigne] = $ligne;
                $csvData[0] = ["Carte", "Vannes/Etat", "Valeur", "Timer dependance", "Dependance vannes"];
            
                $monFichier = fopen($csvFileName, "w");
            
                if (!$monFichier) {
                    die("Impossible d'ouvrir le fichier $csvFileName pour écriture.");
                }
            
                foreach ($csvData as $ligne) {
                    if (fputcsv($monFichier, $ligne, ';') === false) {
                        die("Erreur lors de l'écriture dans le fichier $csvFileName.");
                    }
                }
            
                fclose($monFichier);
            }
            break;
            
        case "suppr":
            if($numLigne){
                // Supprimer la ligne correspondant à $numLigne du tableau $csvData
                unset($csvData[$numLigne]);

                // Ouvrir le fichier CSV en mode écriture
                $monFichier = fopen($csvFileName, "w");

                if (!$monFichier){
                    print("Impossible d'ouvrir le fichier");
                    exit;
                }
                else{
                    // Parcourir les lignes restantes du tableau csvData et les écrire dans le fichier CSV
                    foreach ($csvData as $ligne) {
                        // Écrire chaque élément de la ligne dans le fichier CSV
                        fwrite($monFichier, implode(';', $ligne) . "\n");
                    }
                }

                fclose($monFichier);
            }
            
            break;
        default:
            echo "Erreur ! Aucun bouton n'a été appuyé.";
            break;
    }
    

    // Rediriger vers la page AjoutCSV.php
    header('Location: Pages/pageAjoutCSV.php');
    exit();
} else {
    echo "Certains champs sont vides. Veuillez remplir tous les champs.";
}
?>
