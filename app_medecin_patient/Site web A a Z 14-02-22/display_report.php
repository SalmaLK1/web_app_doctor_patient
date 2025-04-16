<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue des Rapports Médicaux</title>
    <!-- Lien vers votre fichier CSS -->
    <style>
        /* Styles généraux */
body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}

.header {
    background-color: #007bff;
    color: white;
    padding: 20px;
    text-align: center;
}

.main {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.report-list {
    margin-bottom: 20px;
}

.report-container {
    background-color: #f9f9f9;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    margin-bottom: 10px;
}

.btn {
    display: inline-block;
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    text-decoration: none;
    border-radius: 5px;
}

.btn:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <header class="header">
        <h1>Vue des Rapports Médicaux</h1>
    </header>
    <main class="main">
        <div class="report-list">
        <?php
// Assurez-vous que le nom du patient est passé en paramètre GET
if(isset($_GET['search_patient_name'])) {
    $search_patient_name = $_GET['search_patient_name'];

    // Supposons que les rapports sont stockés dans des fichiers texte avec le nom du patient comme nom de fichier
    $filename = "reports/" . $search_patient_name . ".txt";

    // Vérifiez si le fichier existe avant de l'ouvrir
    if(file_exists($filename)) {
        // Lire le contenu du fichier et l'afficher comme rapport
        $report_content = file_get_contents($filename);

        // Afficher le rapport dans la section appropriée de la page HTML
        echo "<div class='report-container'>";
        echo "<h2>Rapport pour le patient $search_patient_name :</h2>";
        echo "<p>$report_content</p>";
        echo "</div>";
    } else {
        echo "<p>Aucun rapport trouvé pour le patient $search_patient_name.</p>";
    }
} else {
    echo "<p>Aucun nom de patient spécifié.</p>";
}
?>

        </div>
        <a href="/simple php system/home.php" class="btn">Retour à la page principale</a>
    </main>
</body>
</html>
