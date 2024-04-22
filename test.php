<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Checkbox Values</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .selectBox {
            position: relative;
        }
        .selectBox select {
            width: 100%;
            font-weight: bold;
        }
        .overSelect {
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
        }
        #checkboxes {
            display: none;
            border: 1px solid #ddd;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <table class="table">
            <thead>
                <tr>
                    <th>Colonne 1</th>
                    <th>Colonne 2</th>
                    <th>Colonne 3</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= 5; $i++): ?>
                <tr onclick="addCheckbox(this)">
                    <td><?= $i ?></td>
                    <td>Colonne 2 - Ligne <?= $i ?></td>
                    <td>Colonne 3 - Ligne <?= $i ?></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <div id="checkboxes">
            <!-- Les cases à cocher seront ajoutées ici -->
        </div>
        <input type="text" id="selectedValues" class="form-control mt-3" disabled placeholder="Selected values appear here...">
    </div>

    <script>
        function addCheckbox(row) {
            var cellValue = row.cells[0].innerText; // Prendre la valeur de la première colonne
            var checkboxes = document.getElementById("checkboxes");
            var checkboxExists = document.getElementById("checkbox_" + cellValue);
            if (!checkboxExists) {
                var checkboxWrapper = document.createElement("div");
                checkboxWrapper.className = "form-check";
                
                var checkbox = document.createElement("input");
                checkbox.type = "checkbox";
                checkbox.className = "form-check-input";
                checkbox.id = "checkbox_" + cellValue;
                checkbox.name = "checkboxes[]";
                checkbox.value = cellValue;
                checkbox.onclick = updateSelectedValues; // Écouteur d'événements pour mettre à jour les valeurs sélectionnées

                var label = document.createElement("label");
                label.className = "form-check-label";
                label.htmlFor = "checkbox_" + cellValue;
                label.textContent = "Selected Value: " + cellValue;

                checkboxWrapper.appendChild(checkbox);
                checkboxWrapper.appendChild(label);
                checkboxes.appendChild(checkboxWrapper);
                checkboxes.style.display = "block";
            }
        }

        function updateSelectedValues() {
            var selectedCheckboxes = document.querySelectorAll('input[type="checkbox"]:checked');
            var selectedValues = Array.from(selectedCheckboxes).map(cb => cb.value).join(', ');
            document.getElementById('selectedValues').value = selectedValues;
        }
    </script>
</body>
</html>
