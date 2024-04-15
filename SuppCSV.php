<?php

if($searchedFile){
    $searchedFile = $_POST[''];

    if (unlink($searchedFile)){
        print("Fichier {$seachedFile} supprimé");

        header('Location: Pages/pageAjoutCSV.php');
        exit();
    }
    else{
        print("Suppression du fichier a échoué");
    }
}
else{
    print("Aucun fichier n'a été sélectionné !")
}
?>