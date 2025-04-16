
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des rendez-vous</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 100px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #0065e1;
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #0065e1;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }

        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
        }
        .refuse-btn{
            background-color: red;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 5px;
        }
        .accept-btn {
            background-color: green;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 12px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 5px;
        }
        .refuse-btn:hover{
            background-color: red;
        }
        .accept-btn:hover
         {
            background-color: green;
        }

        p {
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
    <a href="/simple php system/home.php"> <button class="btn"><i class="fas fa-home home-icon"></i>accueil</button> </a>
    </header>
    <div class="container">
        <h1>Gestion des rendez-vous</h1>
        <!-- La liste des rendez-vous est affichée ici -->
        <?php
        // Inclusion du fichier de configuration de la base de données
        include("simple php system/php/config.php");

        // Vérification de l'ID du médecin
$medecin_id = isset($_GET['medecin_id']) ? $_GET['medecin_id'] : null;
if($medecin_id === null) {
    // Redirection si l'ID du médecin n'est pas spécifié
    header("Location: ../index.php");
    exit(); // Arrêt de l'exécution du script après la redirection
}

// Récupération des rendez-vous pour le médecin spécifié
$sql = "SELECT * FROM rendez_vous WHERE MedecinId = $medecin_id";
$result = $con->query($sql);

// Vérification si des rendez-vous ont été trouvés
if ($result) {
    // Affichage des rendez-vous sous forme de tableau
    echo "<h2>Liste des rendez-vous pour le médecin</h2>";
    echo "<table>";
    echo "<thead><tr><th>Nom</th><th>Prénom</th><th>Date de rendez-vous</th><th>Heure de rendez-vous</th><th>Statut</th><th>Actions</th></tr></thead>";
    echo "<tbody>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["Nom"] . "</td>";
        echo "<td>" . $row["Prenom"] . "</td>";
        echo "<td>" . $row["DateRendezVous"] . "</td>";
        echo "<td>" . $row["HeureRendezVous"] . "</td>";
        echo "<td id='statut-" . $row["Id"] . "'>" . $row["Status"] . "</td>";
        echo "<td class='action-buttons'>";
        echo "<button class='accept-btn' onclick='updateStatut(" . $row["Id"] . ", \"Accepter\")'>Accepter</button>";
        echo "<button class='refuse-btn' onclick='updateStatut(" . $row["Id"] . ", \"Refuser\")'>Refuser</button>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>Aucun rendez-vous trouvé pour ce médecin.</p>";
}
$con->close();
?>

        <script>
            function updateStatut(rendezVousId, action) {
                // Envoyer une requête AJAX pour mettre à jour le statut
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Mettre à jour le statut dans le tableau
                        document.getElementById("statut-" + rendezVousId).innerHTML = action;
                    }
                };
                xhttp.open("GET", "update_statut.php?rendezVousId=" + rendezVousId + "&action=" + action, true);
                xhttp.send();
            }
        </script>
    </div>
</body>
</html>
