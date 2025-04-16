<?php
session_start();

include("simple php system/php/config.php");

// Vérifier si un utilisateur est connecté
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID du rapport à modifier est passé en paramètre
if (!isset($_GET['report_id'])) {
    header("Location: rapport.php");
    exit();
}

// Récupérer l'ID du rapport à modifier
$report_id = $_GET['report_id'];

// Récupérer le rapport à modifier depuis la base de données
$sql_report = "SELECT * FROM rapport WHERE Id = $report_id";
$result_report = $con->query($sql_report);

// Vérifier si le rapport existe
if ($result_report->num_rows == 0) {
    header("Location: rapport.php");
    exit();
}

// Récupérer les détails du rapport
$row_report = $result_report->fetch_assoc();
$rapport = $row_report['RapportText'];

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification des données envoyées
    if (isset($_POST['rapport'])) {
        // Récupérer les données du formulaire
        $nouveau_rapport = $_POST['rapport'];

        // Mettre à jour le rapport dans la base de données
        $sql_update = "UPDATE rapport SET RapportText = '$nouveau_rapport' WHERE Id = $report_id";
        if ($con->query($sql_update) === TRUE) {
            header("Location: rapport.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour du rapport : " . $con->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Rapport Médical</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <h1>Modifier Rapport Médical</h1>
    </header>
    <main class="main">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?report_id=' . $report_id; ?>" method="post">
            <label for="rapport">Nouveau Rapport :</label>
            <textarea id="rapport" name="rapport" rows="10" required><?php echo $rapport; ?></textarea>
            <button type="submit">Enregistrer</button>
        </form>
    </main>
</body>
</html>
