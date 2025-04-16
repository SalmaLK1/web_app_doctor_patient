<?php
session_start();

include("php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

$patient_id = $_SESSION['id'];

// Récupérer les ordonnances du patient
$query_ord = mysqli_query($con, "SELECT * FROM ordonnance WHERE patient_id = $patient_id");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des ordonnances</title>
    <style>
        /* Ajoutez vos styles CSS ici */

        /* Style simple pour la liste des ordonnances */
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .medecin-info {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .date-ordonnance {
            color: #666;
        }
    </style>
</head>

<body>
    <h1>Liste des ordonnances</h1>
    <ul>
        <?php
        // Parcourir les ordonnances du patient et afficher les informations
        while ($row_ord = mysqli_fetch_assoc($query_ord)) {
            // Récupérer les informations du médecin associé à l'ordonnance
            $medecin_id = $row_ord['medecin_id'];
            $query_medecin = mysqli_query($con, "SELECT * FROM users WHERE Id = $medecin_id AND Role = 'medecin'");
            if ($row_medecin = mysqli_fetch_assoc($query_medecin)) {
                $medecin_username = $row_medecin['Username'];
                $medecin_speciality = $row_medecin['Speciality'];
            } else {
                $medecin_username = "Médecin introuvable";
                $medecin_speciality = "Spécialité inconnue";
            }

            echo '<li>';
            echo '<div class="medecin-info">Médecin: ' . $medecin_username . ' (' . $medecin_speciality . ')</div>';
            echo '<div class="date-ordonnance">Date de l\'ordonnance: ' . $row_ord['date_created'] . '</div>';
            echo '<div class="medication">Médication: ' . $row_ord['medication'] . '</div>';
            echo '<div class="dosage">Dosage: ' . $row_ord['dosage'] . '</div>';
            echo '<div class="frequency">Fréquence: ' . $row_ord['frequency'] . '</div>';
            echo '<div class="instructions">Instructions: ' . $row_ord['instructions'] . '</div>';
            echo '</li>';
        }
        ?>
    </ul>
</body>

</html>
