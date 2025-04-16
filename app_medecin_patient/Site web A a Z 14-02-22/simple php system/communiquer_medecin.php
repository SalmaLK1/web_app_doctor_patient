<?php
include("php/config.php");

// Récupérer l'ID du patient et du médecin à partir de la session ou de la requête, selon votre configuration
$patient_id = 1; // Exemple d'ID de patient à remplacer par la méthode appropriée
$medecin_id = 2; // Exemple d'ID de médecin à remplacer par la méthode appropriée

// Traiter l'envoi d'un nouveau message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];

    // Insérer le nouveau message dans la table messages
    $insert_query = "INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES ('$patient_id', '$medecin_id', '$message', NOW())";
    $insert_result = mysqli_query($con, $insert_query);
    if ($insert_result) {
        echo "Message envoyé avec succès.";
    } else {
        echo "Erreur lors de l'envoi du message : " . mysqli_error($con);
    }
}

// Récupérer les messages échangés avec le médecin
$messages_query = mysqli_query($con, "SELECT * FROM messages WHERE (sender_id = $patient_id AND receiver_id = $medecin_id) OR (sender_id = $medecin_id AND receiver_id = $patient_id)");

if($messages_query) {
    // Afficher les messages
    while($message_row = mysqli_fetch_assoc($messages_query)) {
        $sender_id = $message_row['sender_id'];
        $message = $message_row['message'];
        echo "Expéditeur: $sender_id - Message: $message <br>";
    }
} else {
    echo "Erreur lors de la récupération des messages : " . mysqli_error($con);
}
?>
