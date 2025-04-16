<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue des Rapports Médicaux</title>
    <!-- Lien vers votre fichier CSS -->
</head>
<body>
    <header class="header">
        <h1>Vue des Rapports Médicaux</h1>
    </header>
    <main class="main">
        <div class="report-list">
            <?php
            // Vérifier si un nom de patient a été envoyé via GET
            if (isset($_GET['patient_name'])) {
                // Récupérer le nom du patient recherché
                $patient_name = $_GET['patient_name'];

                // Charger le rapport correspondant s'il existe
                $filename = "reports/" . $patient_name . ".txt";
                if (file_exists($filename)) {
                    // Afficher le rapport
                    echo "<h2>Rapport pour le patient $patient_name :</h2>";
                    echo "<pre>" . htmlspecialchars(file_get_contents($filename)) . "</pre>";
                } else {
                    // Si le rapport n'existe pas, afficher un message d'erreur
                    echo "<p>Aucun rapport trouvé pour le patient $patient_name.</p>";
                }
            } else {
                // Si aucun nom de patient n'a été spécifié, afficher un message d'instruction
                echo "<p>Veuillez spécifier le nom du patient pour afficher son rapport.</p>";
            }
            ?>
        </div>
        <a href="index.php" class="btn">Retour à la page principale</a>
    </main>
</body>
</html>
