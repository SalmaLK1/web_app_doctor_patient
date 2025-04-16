<?php
session_start();
include("php/config.php");

// Vérifier si la requête est une requête POST et si les données nécessaires sont présentes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'], $_POST['message'], $_POST['patient_id'])) {
    // Récupérer les données du formulaire
    $message = $_POST['message'];
    $patient_id = $_POST['patient_id'];
    
    // Récupérer l'ID de l'utilisateur envoyant le message à partir de la session
    $sender_id = $_SESSION['id']; // Assurez-vous que $_SESSION['id'] contient l'ID de l'utilisateur connecté
    $sender_name = $_SESSION['Username']; // Assurez-vous que $_SESSION['Username'] contient le nom de l'utilisateur connecté

    // Vérifier si l'identifiant du patient est un entier valide
    if (!empty($patient_id) && is_numeric($patient_id)) {
        // Préparer la requête d'insertion
        $insert_query = "INSERT INTO messages (sender_id, receiver_id, message, sender_name, timestamp) 
                        VALUES ('$sender_id', '$patient_id', '$message', '$sender_name', NOW())";

        // Exécuter la requête d'insertion
        $insert_result = mysqli_query($con, $insert_query);

        if ($insert_result) {
            // Le message a été inséré avec succès
            echo "Message envoyé avec succès.";
        } else {
            // Une erreur s'est produite lors de l'insertion du message
            echo "Erreur lors de l'envoi du message : " . mysqli_error($con);
        }
    } else {
        // L'identifiant du patient est invalide
        echo "L'identifiant du patient est invalide.";
    }
} else {
    // Les données nécessaires ne sont pas présentes dans la requête
    echo "Veuillez remplir tous les champs du formulaire.";
}
?>
