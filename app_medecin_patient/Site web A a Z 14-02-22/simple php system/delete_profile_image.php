
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Document</title>
</head>
<body>
    
</body>
</html>
<?php
session_start();

include("php/config.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit(); // Arrêter l'exécution du script après la redirection
}

// Récupérer l'ID de l'utilisateur connecté
$id = $_SESSION['id'];

// Sélectionner le chemin de l'image de profil de l'utilisateur
$query = mysqli_query($con, "SELECT ProfileImage FROM users WHERE Id = $id");
if ($result = mysqli_fetch_assoc($query)) {
    $profile_image_path = $result['ProfileImage'];

    // Supprimer l'image de profil du serveur
    if (unlink($profile_image_path)) {
        // Mettre à jour la base de données pour supprimer le chemin de l'image
        $update_query = mysqli_query($con, "UPDATE users SET ProfileImage = NULL WHERE Id = $id");
        if ($update_query) {
           
            $_SESSION['alert_message'] = 'L\'image de profil a été supprimée avec succès.';
        } else {
            $_SESSION['alert_message'] = 'Erreur lors de la mise à jour de la base de données.';
        }
    } else {
        $_SESSION['alert_message'] = 'Erreur lors de la suppression de l\'image de profil.';
    }
} else {
    $_SESSION['alert_message'] = 'Aucune image de profil trouvée.';
}

header("location: profil.php");
?>
