<?php
// Inclure le fichier de configuration de la base de données
include('config.php');

// Vérifier si l'ID de l'utilisateur à supprimer est présent dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Préparer et exécuter la requête de suppression
    $sql = "DELETE FROM Users WHERE Id = ?";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Vérifier si la suppression s'est bien déroulée
        if ($stmt->affected_rows > 0) {
            // Redirection vers la page de liste des utilisateurs
            header("Location: liste_utilisateurs.php");
            exit();
        } else {
            echo "Erreur : Utilisateur non trouvé ou impossible de le supprimer.";
        }

        // Fermer la déclaration préparée
        $stmt->close();
    } else {
        echo "Erreur de préparation de la requête.";
    }

    // Fermer la connexion à la base de données
    $con->close();
} else {
    echo "ID d'utilisateur non spécifié.";
}
?>
