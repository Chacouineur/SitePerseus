<?php
    $fichier = "../liaisonEGEtat.csv";
    $monFichier = fopen($fichier, "r");

    if (!($monFichier)){
        print("Impossible d'ouvrir le fichier");
        exit;
    }
    else{
        fgetcsv($monFichier, null, ";");
        while( $ligne = fgetcsv($monFichier,null,";")){
            $nbElements = count($ligne); // Nombre d'éléments dans la ligne
            for($i = 0; $i < $nbElements; $i++){
                print($ligne[$i] . " ");
            }
            print("<br>");
        }
    }

    fclose($monFichier);
    ?>