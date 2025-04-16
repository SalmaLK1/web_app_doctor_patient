<?php
// Inclure le fichier de configuration de la base de données
include('simple php system/php/config.php');

// Requête SQL pour sélectionner les médecins
$query = "SELECT Username FROM users WHERE Role = 'medecin'";

// Exécuter la requête SQL
$result = mysqli_query($con, $query);

// Vérifier s'il y a des résultats
if (mysqli_num_rows($result) > 0) {
    // Construire les options de menu déroulant avec les médecins trouvés
    $options = "<option value='' disabled selected>Choisissez un médecin</option>";
    while ($row = mysqli_fetch_assoc($result)) {
        $options .= "<option value='" . $row['Username'] . "'>" . $row['Username'] . "</option>";
    }
} else {
    // Aucun médecin trouvé
    $options = "<option value='' disabled>Aucun médecin disponible</option>";
}

// Fermer la connexion à la base de données
mysqli_close($con);

// Renvoyer les options de menu déroulant
echo $options;
?>
