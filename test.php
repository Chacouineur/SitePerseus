<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre Titre</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Ajoutez ici vos styles CSS */
        /* Vous pouvez personnaliser davantage les couleurs et les styles si nécessaire */
        .table-active {
            background-color: #6edff6; /* Couleur de fond pour la ligne sélectionnée */
        }
    </style>
</head>
<body>
    <table class="table table-hover" id="myTable">
        <thead>
            <tr>
                <th scope="col">Carte</th>
                <th scope="col">Vannes/Etat</th>
                <th scope="col">Valeur</th>
                <th scope="col">Timer dépendance</th>
                <th scope="col">Dépendance vannes</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Générez des valeurs aléatoires pour chaque cellule
            $row_count = 10; // Nombre de lignes
            $col_count = 5; // Nombre de colonnes

            // Boucle à travers les lignes
            for($i = 0; $i < $row_count; $i++) {
                echo "<tr onclick=\"selectRow(this)\">";
                // Boucle à travers les colonnes
                for($j = 0; $j < $col_count; $j++) {
                    // Générez une valeur aléatoire
                    $random_value = rand(0, 100); // Chiffre aléatoire entre 0 et 100
                    echo "<td>$random_value</td>";
                }
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        // Fonction pour sélectionner une ligne
        function selectRow(row) {
            // Réinitialiser la couleur de toutes les lignes
            var rows = document.querySelectorAll("#myTable tbody tr");
            rows.forEach(row => {
                row.classList.remove("table-active"); // Supprimer la classe "table-active" de toutes les lignes
            });

            // Appliquer la classe "table-active" à la ligne sélectionnée
            row.classList.add("table-active");
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
