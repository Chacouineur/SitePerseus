<!DOCTYPE html>
<html lang="fr">
<!-- <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select avec Checkbox Bootstrap</title> -->
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <!-- Bootstrap Multiselect CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.16/css/bootstrap-multiselect.min.css">
    <style>
        .input-group-prepend:hover .dropdown-menu {
            display: block;
        }
    </style>
</head>
<body>

 <div class="input-group">
    <div class="input-group-prepend">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Vannes/Etat
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item">
                <input type="checkbox" id="option1" name="option1" value="1">
                <label for="option1">Option 1</label>
                
            </a>
            <a class="dropdown-item">
                <input type="checkbox" id="option2" name="option2" value="2">
                <label for="option2">Option 2</label>
            </a>
            <a class="dropdown-item">
                <input type="checkbox" id="option3" name="option3" value="3">
                <label for="option3">Option 3</label>
            </a>
            <a class="dropdown-item">
                <input type="checkbox" id="option4" name="option4" value="4">
                <label for="option4">Option 4</label>
            </a>
            <a class="dropdown-item">
                <input type="checkbox" id="option5" name="option5" value="5">
                <label for="option5">Option 5</label>
            </a>
        </div>
    </div>
    <input type="text" class="form-control" id="selectedLabels" placeholder="Texte des labels sélectionnés" aria-label="Texte des labels sélectionnés" aria-describedby="btnGroupAddon">
    <input type="text" class="form-control" id="selectedValues" placeholder="Valeurs sélectionnées" aria-label="Valeurs sélectionnées" aria-describedby="btnGroupAddon">
</div>

<script>
    // Récupérer l'élément input et les cases à cocher du dropdown
    var selectedLabelsInput = document.getElementById('selectedLabels');
    var selectedValuesInput = document.getElementById('selectedValues');
    var checkboxes = document.querySelectorAll('.dropdown-menu input[type="checkbox"]');

    // Écouter les changements sur les cases à cocher
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            updateSelectedValues();
        });
    });

    // Fonction pour mettre à jour les valeurs sélectionnées dans l'élément input
    function updateSelectedValues() {
        var selectedLabels = [];
        var selectedValues = [];
        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                selectedLabels.push(checkbox.nextElementSibling.textContent.trim());
                selectedValues.push(checkbox.value);
            }
        });
        selectedLabelsInput.value = selectedLabels.join(' | ');
        selectedValuesInput.value = selectedValues.join(' | ');
    }
</script>

</body>  -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Change Color on Click</title>
<style>
    /* Style pour la couleur de fond initiale des lignes */
    .selected {
        background-color: yellow;
    }
</style>
</head>
<body>

<table id="myTable">
    <thead>
        <tr>
            <th>Header 1</th>
            <th>Header 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
        </tr>
        <tr>
            <td>Row 2 Data 1</td>
            <td>Row 2 Data 2</td>
        </tr>
    </tbody>
</table>

<script>
    // Sélectionner toutes les lignes de la table
    const rows = document.querySelectorAll("#myTable tbody tr");

    // Ajouter un gestionnaire d'événement de clic à chaque ligne
    rows.forEach(row => {
        row.addEventListener("click", () => {
            // Supprimer la classe 'selected' de toutes les lignes
            rows.forEach(r => r.classList.remove("selected"));
            // Ajouter la classe 'selected' à la ligne cliquée
            row.classList.add("selected");
        });
    });
</script>

</body>
</html>