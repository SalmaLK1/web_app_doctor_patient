<?php
session_start();

include("php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

$patient_id = $_SESSION['id'];

// Vérifier si un rendez-vous a été supprimé
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = mysqli_real_escape_string($con, $_POST['delete_id']);
    $delete_query = "DELETE FROM rendez_vous WHERE Id='$delete_id' AND PatientId='$patient_id'";
    if (mysqli_query($con, $delete_query)) {
        header("Location: liste_rendezvous.php");
        exit();
    } else {
        echo "Erreur lors de la suppression du rendez-vous : " . mysqli_error($con);
    }
}

$query = mysqli_query($con, "SELECT rv.Id, rv.Nom, rv.Prenom, rv.DateRendezVous, rv.HeureRendezVous, u.Username AS MedecinNom FROM rendez_vous rv JOIN users u ON rv.MedecinId = u.Id WHERE rv.PatientId = $patient_id");
$rendezvous = [];
while ($row = mysqli_fetch_assoc($query)) {
    $rendezvous[] = $row;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des rendez-vous</title>
    <!-- Vos styles CSS ici -->
</head>
<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    h1 {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: left;
    }

    th {
        background-color: #007bff;
        color: #fff;
        font-weight: normal;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    button {
        background-color: #dc3545;
        color: #fff;
        border: none;
        padding: 5px 10px;
        border-radius: 3px;
        cursor: pointer;
    }

    button:hover {
        background-color: #c82333;
    }

    .no-rendezvous {
        color: #777;
        font-style: italic;
    }
</style>

<body>
    <div class="container">
        <h1>Liste des rendez-vous</h1>
        <?php if (!empty($rendezvous)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Nom et Prénom</th>
                        <th>Date du rendez-vous</th>
                        <th>Médecin</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rendezvous as $rdv) : ?>
                        <tr>
                            <td><?php echo $rdv['Nom'] . ' ' . $rdv['Prenom']; ?></td>
                            <td><?php echo $rdv['DateRendezVous'] . ' ' . $rdv['HeureRendezVous']; ?></td>
                            <td><?php echo $rdv['MedecinNom']; ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="delete_id" value="<?php echo $rdv['Id']; ?>">
                                    <button type="submit">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p class="no-rendezvous">Aucun rendez-vous trouvé.</p>
        <?php endif; ?>
    </div>
</body>

</html>
