<?php
session_start();
include('simple php system/php/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];   
    $prenom = $_POST['prenom'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $medecinId = $_POST['medecin'];

    // Récupérer l'ID du patient depuis la session
    $patientId = $_SESSION['id'];

    // Connexion à la base de données
    include("php/config.php");

    // Préparer la requête d'insertion
    $sql = "INSERT INTO rendez_vous (Nom, Prenom, DateRendezVous, HeureRendezVous, MedecinId, Status, PatientId) VALUES (?, ?, ?, ?, ?, 'En attente', ?)";
    $stmt = $con->prepare($sql);

    if ($stmt) {
        // Liaison des paramètres
        $stmt->bind_param("ssssii", $nom, $prenom, $date, $heure, $medecinId, $patientId);

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
    $con->close();
} else {
    echo "Formulaire non soumis.";
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de rendez-vous</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            <h1>Bienvenue sur notre service de prise de rendez-vous en ligne !</h1>
            <p>Planifiez votre prochain rendez-vous avec facilité.</p>
        </header>
        <form action="select_rdvs.php" method="POST">
            <div class="personal-info">
                <h2>Informations personnelles</h2>
                <div class="input-group">
                    <label for="nom"><i class="fas fa-user"></i> Nom :</label>
                    <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
                </div>
                <div class="input-group">
                    <label for="prenom"><i class="fas fa-user"></i> Prénom :</label>
                    <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required>
                </div>
            </div>
            <div class="appointment-details">
                <h2>Détails du rendez-vous</h2>
                <div class="input-group">
                    <label for="date"><i class="far fa-calendar-alt"></i> Date :</label>
                    <input type="date" id="date" name="date" required>
                </div>
                <div class="input-group">
                    <label for="heure"><i class="far fa-clock"></i> Heure :</label>
                    <input type="time" id="heure" name="heure" required>
                </div>
                <div class="input-group search-doctor">
                    <label for="medecin"><i class="fas fa-user-md"></i> Sélectionnez votre médecin :</label>
                    <select id="medecin" name="medecin" required>
                        <!-- Récupérez et affichez les médecins ayant le rôle de médecin -->
                        <?php
                        // Connexion à la base de données
                        include("simple php system/php/config.php");
                        // Requête pour récupérer les médecins
                        $sql = "SELECT Id, Username FROM users WHERE Role = 'medecin'";
                        $result = $con->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['Id'] . "'>" . $row['Username'] . "</option>";
                            }
                        } else {
                            echo "<option value=''>Aucun médecin trouvé</option>";
                        }

                        $con->close();
                        ?>
                    </select>
                </div>
            </div>
            <div class="button-group">
                <button type="submit"><i class="fas fa-calendar-plus"></i> Confirmer le rendez-vous</button>
            </div>
        </form>
    </div>
</body>
</html>
