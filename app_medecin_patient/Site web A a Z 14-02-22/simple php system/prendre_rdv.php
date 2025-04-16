<?php
include("php/config.php");
session_start();

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $dateRendezVous = $_POST['date'];
    $heureRendezVous = $_POST['heure'];
    $medecinId = $_POST['medecin'];

    // Récupérer l'ID du patient depuis la session
    $patientId = $_SESSION['id'];

    // Préparer la requête d'insertion
    $sql = "INSERT INTO rendez_vous (Nom, Prenom, DateRendezVous, HeureRendezVous, MedecinId, Status, PatientId) VALUES (?, ?, ?, ?, ?, 'En attente', ?)";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        // Liaison des paramètres
        $stmt->bind_param("ssssii", $nom, $prenom, $dateRendezVous, $heureRendezVous, $medecinId, $patientId);

        // Exécution de la requête
        if ($stmt->execute()) {
            echo "Le rendez-vous a été enregistré avec succès.";
        } else {
            echo "Erreur lors de l'insertion du rendez-vous : " . $stmt->error;
        }
    } else {
        echo "Erreur de préparation de la requête : " . $con->error;
    }

    // Fermer la connexion à la base de données
    $stmt->close();
} else {
    echo "Formulaire non soumis.";
}

// Vérifier si l'ID du médecin est passé en tant que paramètre
if (isset($_GET['medecin_id'])) {
    $medecin_id = $_GET['medecin_id'];

    // Requête pour obtenir les informations du médecin
    $query_medecin = mysqli_query($con, "SELECT Username, Speciality FROM users WHERE Id = $medecin_id");
    if ($query_medecin) {
        if ($result_medecin = mysqli_fetch_assoc($query_medecin)) {
            $medecin_username = $result_medecin['Username'];
            $medecin_speciality = $result_medecin['Speciality'];
        } else {
            // Si le médecin n'est pas trouvé, définissez des valeurs par défaut ou gérez l'erreur autrement
            $medecin_username = 'Médecin non trouvé';
            $medecin_speciality = 'Spécialité inconnue';
        }
    } else {
        // Gérer l'erreur de requête SQL
        $medecin_username = 'Erreur de requête';
        $medecin_speciality = 'Erreur de requête';
    }
} else {
    // Si l'ID du médecin n'est pas passé, afficher un message d'erreur
    echo "ID du médecin non spécifié.";
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de rendez-vous</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        header h1 {
            color: #0065e1;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #0065e1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #004c8b;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            font-weight: bold;
        }

        .search-box {
            display: flex;
        }

        .search-box input[type="text"] {
            flex: 1;
            margin-right: 10px;
        }

        .search-box button[type="submit"] {
            padding: 10px 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>Prise de rendez-vous avec Dr. <?php echo  $medecin_username; ?></h1>
            <p><?php echo $medecin_speciality; ?></p>
        </header>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

            <div class="input-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
            </div>
            <div class="input-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
            </div>
            <div class="input-group">
                <label for="date">Date :</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="input-group">
                <label for="heure">Heure :</label>
                <input type="time" id="heure" name="heure" required>
            </div>
            <input type="hidden" name="medecin" value="<?php echo $medecin_id; ?>">
            <button type="submit">Prendre rendez-vous</button>
        </form>
    </div>
</body>

</html>
