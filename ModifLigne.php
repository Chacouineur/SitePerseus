<?php
session_start();
$csvFileName = $_SESSION['csvFileName'];
$csvData = $_SESSION['csvData'];

// Initialiser un tableau pour stocker toutes les données du fichier CSV

if (!empty($csvFileName) && !empty($_POST['btnValue'])) {
    $carte = $_POST['carte'];
    $vannesEtat = $_POST['vannesEtat'];
    $valeur = $_POST['valeur'];
    $timeDep = $_POST['timeDep'];
    $depVannes = isset($_POST['selectValue']) ? $_POST['selectValue'] : "#";
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
        case "ajoutMax":
            // Compter le nombre de lignes pour la carte spécifiée
            $nombreDeLignesParCarte = 0;
            while (($data = fgetcsv($monFichier, 1000, ";")) !== FALSE) {
                if ($data[0] === $carte) {
                    $nombreDeLignesParCarte++;
                }
            }

            // Ajouter une nouvelle ligne si le nombre de lignes est inférieur à 12
            if ($nombreDeLignesParCarte < 12 && $carte != 'OFFSET') {
                
                fseek($monFichier, 0, SEEK_END); // Aller à la fin du fichier pour l'écriture
                $ligne = [$carte, $vannesEtat, $valeur, $timeDep, $depVannes];
                $ligneVide = [$carte,"#","#","#","#"];
                $offset = ["OFFSET","EG","#","#","#"];
                fputcsv($monFichier, $ligne, ';');
                for($nombreDeLignesParCarte+1;$nombreDeLignesParCarte+1<12;$nombreDeLignesParCarte++){
                    fputcsv($monFichier, $ligneVide, ';');
                }
                fputcsv($monFichier, $offset, ';');
                
            }else {
                echo "La limite de 12 lignes pour la carte $carte a été atteinte et/ou vous avez tente dajouter une carte OFFSET";
            }

            fclose($monFichier);
            break;

        case "modif":
            if ($numLigne && ($numLigne - 1) % 13 == 0) {
                // Vérifier si le nom de la carte de la ligne précédente est "OFFSET"
                if ($csvData[$numLigne - 1][0] === "OFFSET") {
                    // Utiliser le nom de la carte de la ligne suivante
                    $carte = $csvData[$numLigne + 1][0];
                }
                // Ne modifie pas la ligne
            } else {
                // Ignorer la modification du champ "Carte"
                $ligne = [$csvData[$numLigne][0], $vannesEtat, $valeur, $timeDep, $depVannes];
                $csvData[$numLigne] = $ligne;
                $csvData[0] = ["Carte", "Vannes/Etat", "Valeur", "Timer dependance", "Dependance vannes"];
                $csvData[1] = ["OFFSET", "EG", "#", "#", "#"];
            
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
            if($carte !== 'OFFSET'){
                $csvDataSansCarte = [];
                $csvDataSansCarte[0] = ["Carte", "Vannes/Etat", "Valeur", "Timer dependance", "Dependance vannes"];
                $csvDataSansCarte[1] = ["OFFSET", "EG", "#", "#", "#"];
                for($i=2;$i < count($csvData);$i++){
                    
                    if($csvData[$i][0]!== $carte && !($csvData[$i-1][0]===$carte && $csvData[$i][0]==='OFFSET')){
                        $csvDataSansCarte[$i]=$csvData[$i];
                    }
                }
    
                // Ouvrir le fichier CSV en mode écriture
                $monFichier = fopen($csvFileName, "w");
    
                if (!$monFichier) {
                    print("Impossible d'ouvrir le fichier");
                    exit;
                } else {
                    // Parcourir les lignes restantes du tableau csvData et les écrire dans le fichier CSV
                    foreach ($csvDataSansCarte as $ligne) {
                        // Écrire chaque élément de la ligne dans le fichier CSV
                        fputcsv($monFichier, $ligne, ';');
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
