<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table HTML en JavaScript</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Inclure les scripts JavaScript de Bootstrap (JQuery requis) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <!-- Bootstrap CSS -->
    
    <!-- Bootstrap Multiselect CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.16/css/bootstrap-multiselect.min.css">
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/font-awesome.min.css">
</head>
<body>
    <div class="container">
        <div class="mb-3" id="nb">
            <label for="nom" class="form-label">Nombre de cartes :</label>
            <div class="input-group mb-3">
                <input type="number" min="1" max="239" class="form-control" name="nbCartes" id="nbCartes" placeholder="min: 1 | max: 239" required>
                <button class="btn btn-primary" type="button" id="ajoutNom" onclick="creerTableau()">Créer Tableau</button>
            </div>
        </div>

        <div class="mb-3">
            <label for="nomCarte" class="form-label">Nom des cartes :</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="nom*" aria-label="nomCarte" name="nomCarte" id="nomCarte" >
                <button class="btn btn-primary" type="button" id="ajoutNom">Modifier</button>
            </div>
            <input type="hidden" class="form-control" placeholder="Noms" name="noms" id="noms" required>
            <input type="hidden" class="form-control" placeholder="ligneIndex" name="ligneIndex" id="ligneIndex">
        </div>

        <div id="tableContainer"></div>
    </div>
    <script>
        function creerTableau() {
            const nombreLignes = document.getElementById('nbCartes').value;
            const data = [];
            for (let i = 1; i <= nombreLignes; i++) {
                data.push([`CN${i}`, `Nom${i}`]); // Modifier pour inclure des noms de cartes fictifs
            }

            const table = document.createElement('table');
            table.setAttribute('id', 'myTable');
            table.classList.add('tableau', 'table', 'table-hover');

            const thead = document.createElement('thead');
            const headerRow = document.createElement('tr');
            const headers = ["Num Carte", "NomCarte"];
            headers.forEach(headerText => {
                const th = document.createElement('th');
                th.setAttribute('scope', 'col');
                th.textContent = headerText;
                headerRow.appendChild(th);
            });
            thead.appendChild(headerRow);
            table.appendChild(thead);

            const tbody = document.createElement('tbody');
            data.forEach(rowData => {
                const row = document.createElement('tr');
                rowData.forEach(cellData => {
                    const cell = document.createElement('td');
                    cell.textContent = cellData;
                    row.appendChild(cell);
                });
                tbody.appendChild(row);
            });
            table.appendChild(tbody);

            const tableContainer = document.getElementById('tableContainer');
            tableContainer.innerHTML = ''; 
            tableContainer.appendChild(table);

            
        }
        var ligneIndex = document.getElementById('ligneIndex');
        var nomsInput = document.getElementById('noms'); 
        // Attacher l'événement click pour sélectionner une ligne du tableau
        document.addEventListener('click', function(event) {
            const target = event.target;
            if (target.nodeName === 'TD') {
                const selectedRow = target.parentNode;
                const rowIndex = Array.from(selectedRow.parentNode.children).indexOf(selectedRow);

                const nomCarte = selectedRow.children[1].textContent;
                document.getElementById('nomCarte').value = nomCarte;

                // Vérifier si la ligne est déjà active
                const isActive = selectedRow.classList.contains("table-active");

                // Déselectionner toutes les autres lignes et surligner la ligne cliquée
                const rows = document.querySelectorAll("#tableContainer #myTable tbody tr");
                rows.forEach(row => {
                    row.classList.remove("table-active");
                });

                // Si la ligne n'est pas déjà active, l'activer, sinon la désactiver
                if (!isActive) {
                    selectedRow.classList.add("table-active"); // Ajouter la classe "table-active" à la ligne sélectionnée
                }

                // Mettre à jour l'index de ligne seulement si la ligne est active
                ligneIndex.value = isActive ? '' : rowIndex + 1;
                console.log(ligneIndex.value);

                // Obtenez les valeurs de la deuxième colonne pour toutes les lignes
                const values = [];
                rows.forEach(row => {
                    const cellValue = row.children[1].textContent;
                    values.push(cellValue);
                });
                const concatenatedValues = values.join('|'); // Concaténer les valeurs avec '|'

                // Affecter les valeurs concaténées à l'élément <input> avec l'ID "noms"
                nomsInput.value = concatenatedValues;
                console.log(nomsInput.value);
            }
        });
    </script>
</body>
</html>
