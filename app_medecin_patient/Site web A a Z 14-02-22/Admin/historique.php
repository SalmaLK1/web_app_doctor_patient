<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique de l'utilisateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #f9f9f9;
            padding: 10px;
            margin-bottom: 5px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <h1>Historique de l'utilisateur</h1>
    <ul>
        <?php
        // Vérification si l'ID de l'utilisateur est passé en paramètre
        if (isset($_GET['id'])) {
            $user_id = $_GET['id'];
           $hostname = 'localhost';
           $port = 3306;
           $database = 'pfe';
           $username = 'root';
           $password = 'azerty';
           
          
           $con = new mysqli($hostname, $username, $password, $database, $port);
           if ($con->connect_error) {
               die("Erreur de connexion : " . $con->connect_error);
           }          
           $sql = "SELECT Historique FROM Users WHERE Id = $patient_id AND Role = 'patient'";
           $result = $con->query($sql);
       
           if ($result->num_rows > 0) {
               $row = $result->fetch_assoc();
               $historique = $row["Historique"];
               echo "<h1>Historique du patient</h1>";
               echo "<p>$historique</p>";
           } else {
               echo "<p>Aucun historique trouvé pour ce patient.</p>";
           }
           $con->close();
       } else {
           echo "<p>Aucun ID de patient spécifié.</p>";
       }
       ?>