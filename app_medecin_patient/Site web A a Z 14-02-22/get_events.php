<?php
// Code pour se connecter à la base de données
include("simple php system/php/config.php");

// Vérifier si la requête a été effectuée via la méthode POST et si la date est définie
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedDate'])) {
    // Récupérer la date depuis la requête POST
    $date = $_POST['selectedDate'];

    // Préparer la requête SQL pour récupérer les rendez-vous pour la date donnée
    $sql = "SELECT * FROM rendez_vous WHERE DateRendezVous = ?";
    
    // Préparer la déclaration SQL
    if ($stmt = $con->prepare($sql)) {
        // Liaison des paramètres
        $stmt->bind_param("s", $date);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Stocker les rendez-vous dans un tableau
        $rendezvous = [];
        while ($row = $result->fetch_assoc()) {
            $rendezvous[] = $row['Nom'] . " " . $row['Prenom'];
        }

        // Fermer la déclaration
        $stmt->close();

        // Afficher les rendez-vous dans la boîte modale
        echo "<script>displayModal(" . json_encode($rendezvous) . ");</script>";
    } else {
        // Erreur lors de la préparation de la requête SQL
        echo "<p>Erreur lors de la préparation de la requête SQL.</p>";
    }
} else {
    // Requête invalide ou date non spécifiée
    echo "<p>Requête invalide ou date non spécifiée.</p>";
}

// Fermer la connexion à la base de données
$con->close();
?>
