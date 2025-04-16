<?php
session_start();

include("php/config.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit(); // Arrêter le script pour éviter toute exécution supplémentaire
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer l'historique saisi par l'utilisateur depuis le formulaire
    $historique = $_POST['historique'];

    // Nettoyer et échapper les données pour éviter les injections SQL
    $historique = mysqli_real_escape_string($con, $historique);
    $id = $_SESSION['id'];

    // Préparer la requête SQL d'insertion
    $sql = "UPDATE users SET Historique = '$historique' WHERE Id = $id";

    // Exécuter la requête et vérifier si elle a réussi
    if (mysqli_query($con, $sql)) {
        // Rediriger vers la page doctors.php après avoir enregistré l'historique
        header("Location: doctors.php");
        exit(); // Arrêter le script pour éviter toute exécution supplémentaire après la redirection
    } else {
        echo "Erreur lors de l'enregistrement de l'historique: " . mysqli_error($con);
    }
} else {
    // Rediriger si le formulaire n'a pas été soumis
    header("Location: index.php");
    exit(); // Arrêter le script pour éviter toute exécution supplémentaire après la redirection
}

// Fermer la connexion à la base de données
mysqli_close($con);
?>
