<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de configuration pour établir la connexion à la base de données
    include("simple php system/php/config.php");

    // Vérifier si la connexion à la base de données est établie avec succès
    if ($con) {
        // Vérifier si les données du formulaire existent
        if (isset($_POST['rendezVousId']) && isset($_POST['rapport'])) {
            // Récupérer les données du formulaire
            $rendezVousId = $_POST['rendezVousId'];
            $rapport = $_POST['rapport'];

            // Échapper les caractères spéciaux pour éviter les injections SQL
            $rendezVousId = mysqli_real_escape_string($con, $rendezVousId);
            $rapport = mysqli_real_escape_string($con, $rapport);

            // Insérer le rapport dans la base de données
            $sql_insert = "INSERT INTO rapport (RendezVousId, RapportText) VALUES ('$rendezVousId', '$rapport')";
            if (mysqli_query($con, $sql_insert)) {
                // Rediriger l'utilisateur vers la page de confirmation avec un message de succès
                header("Location: save_raport.php");
                exit();
            } else {
                // En cas d'échec de l'insertion, afficher un message d'erreur
                echo "Erreur lors de l'enregistrement du rapport : " . mysqli_error($con);
            }
        } else {
            // Afficher un message d'erreur si les données du formulaire sont manquantes
            echo "Données du formulaire manquantes.";
        }
    } else {
        // Afficher un message d'erreur si la connexion à la base de données a échoué
        echo "Erreur de connexion à la base de données.";
    }
} else {
    // Rediriger l'utilisateur vers la page principale si le formulaire n'a pas été soumis
    header("Location: index.php");
    exit();
}
?>
