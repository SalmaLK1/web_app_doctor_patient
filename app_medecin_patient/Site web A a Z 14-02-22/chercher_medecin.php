<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Recherche de Médecins</title>
    <style>
        /* Ajouter vos styles personnalisés ici */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        header {
            text-align: center;
            margin-bottom: 20px;
        }

        .search-form {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-input {
            padding: 10px;
            width: 70%;
            border-radius: 20px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }

        .search-button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .search-button:hover {
            background-color: #0056b3;
        }

        .doctor-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .doctor-item {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .doctor-details {
            flex-grow: 1;
            margin-right: 10px;
        }

        .doctor-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .doctor-speciality {
            color: #666;
        }

        .message-icon {
            color: #007bff;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .message-icon:hover {
            color: #0056b3;
        }
   
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Recherche de Médecins</h1>
        <form action="" method="post">
            <input type="text" name="search_query" placeholder="Rechercher un médecin par nom ou spécialité">
            <input type="submit" value="Rechercher">
        </form>
    </header>
    <ul class="doctor-list">
        <?php
        // Connexion à la base de données
      include('simple php system/php/config.php');
        // Traitement de la recherche
        if (isset($_POST['search_query'])) {
            $search_query = $_POST['search_query'];

            // Requête SQL pour rechercher les médecins par nom ou spécialité dans la table users
            $query = "SELECT * FROM users WHERE Username LIKE '%$search_query%' or Speciality LIKE '%$search_query%' AND Role = 'medecin'";

            $result = mysqli_query($con, $query);

            // Affichage des résultats
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<li class='doctor-item'>";
                echo "<span class='doctor-name'>" . $row['Username'] . "</span>";
                echo "<span class='doctor-speciality'>" . $row['Speciality'] . "</span>";
                echo "<i class='fas fa-envelope message-icon' onclick='sendMessage(\"" . $row['Username'] . "\")'></i>";
                echo "</li>";
            }

            // Libérer le résultat de la mémoire
            mysqli_free_result($result);
        }

        // Fermer la connexion à la base de données
        mysqli_close($con);
        ?>
    </ul>
</div>

<script>
    function sendMessage(doctorName) {
        // Ici, vous pouvez implémenter la logique pour ouvrir une boîte de dialogue de messagerie
        // avec le médecin sélectionné ou rediriger vers une page de messagerie dédiée.
        alert("Envoyer un message au Dr. " + doctorName);
    }
</script>
</body>
</html>
