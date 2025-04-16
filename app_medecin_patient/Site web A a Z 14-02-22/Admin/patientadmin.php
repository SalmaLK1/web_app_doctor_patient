<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs patients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        form {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="submit"], select {
            padding: 8px;
            margin-right: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        td {
            background-color: #fff;
        }
        .btn {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Liste des utilisateurs patients</h1>
    <form action="" method="GET">
        <input type="text" name="search" placeholder="Rechercher par nom">
        
        <button type="submit" class="btn">Rechercher</button>
    </form>
    
    <div class="btn-ajouter">
        <a href="ajouter_utilisateur.php" class="btn">Ajouter un utilisateur</a>
    </div>
    <table>
        <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th></th>
            <th>action</th>
            
        </tr>
        <?php
        include('config.php');

        if ($con) {
            $sql = "SELECT * FROM Users WHERE Role = 'patient'";

            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $_GET['search'];
                $sql .= " AND Username LIKE '%$search%'";
            }

            if (isset($_GET['ville']) && !empty($_GET['ville'])) {
                $ville = $_GET['ville'];
                $sql .= " AND Ville = '$ville'";
            }

            $result = $con->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>".$row['Id']."</td>";
                    echo "<td>".$row['Username']."</td>";
                    echo "<td>".$row['Email']."</td>";
                    echo "<td>";
                    echo "<a href='edit.php?id=".$row['Id']."' class='btn'>detail</a>";
                    echo "<a href='#' class='btn' data-id='".$row['Id']."' onclick='supprimerUtilisateur(".$row['Id'].")'>Supprimer</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Aucun utilisateur trouvé</td></tr>";
            }
            $con->close();
        } else {
            echo "<tr><td colspan='5'>Erreur de connexion à la base de données</td></tr>";
        }
        ?>
    </table>
    <script>
        function supprimerUtilisateur(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
                // Envoyer une requête AJAX pour supprimer l'utilisateur
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        // Mettre à jour la liste affichée sur la page en supprimant la ligne correspondante
                        var row = document.getElementById("row_" + id);
                        if (row) {
                            row.parentNode.removeChild(row);
                        }
                    }
                };
                xhr.open("GET", "supprimer_utilisateur.php?id=" + id, true);
                xhr.send();
            }
        }
    </script>
</body>
</html>
