<?php
include("simple php system/php/config.php");

// Vérification des paramètres passés via GET
if (isset($_GET['rendezVousId']) && isset($_GET['action'])) {
    $rendezVousId = $_GET['rendezVousId'];
    $action = $_GET['action'];

    // Échapper les valeurs pour éviter les injections SQL
    $rendezVousId = mysqli_real_escape_string($con, $rendezVousId);
    $action = mysqli_real_escape_string($con, $action);

    // Mettre à jour le statut dans la base de données
    $sql = "UPDATE rendez_vous SET Status = '$action' WHERE Id = $rendezVousId";

    if ($con->query($sql) === TRUE) {
        // Réussite de la mise à jour du statut
        echo "Statut mis à jour avec succès.";
    } else {
        // Erreur lors de la mise à jour du statut
        echo "Erreur lors de la mise à jour du statut : " . $con->error;
    }
} else {
    // Paramètres manquants
    echo "Paramètres manquants pour la mise à jour du statut.";
}

// Fermeture de la connexion à la base de données
$con->close();
?>
