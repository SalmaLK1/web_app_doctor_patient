<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon fav-icon" href="favicon.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Docteurs</title>
    <link rel="stylesheet" href="../style.css">
    <!-- Inclure Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
   body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f5f5;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            margin: 20px auto;
        }

        .section-title {
            color: #007bff;
            font-size: 2.5em;
            margin-bottom: 40px;
            text-align: center;
        }

        .search-box {
            margin-top: 20px;
            text-align: center;
           
        }

        .search-box input[type="text"] {
            padding: 10px;
            border-radius: 5px;
             border: 1px solid #cccccc;
            
            width: 300px;
        }

        .search-box button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .search-box button:hover {
            background-color: #0056b3;
        }

        .doctors-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .doctor-card {
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            text-align: center;
            width: calc(33.33% - 20px); /* Pour afficher 3 cartes par rangée */
            margin-right: 20px;
        }

        .profile-picture {
            border-radius: 50%;
            width: 150px;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .doctor-name {
            font-size: 1.5em;
            margin-bottom: 5px;
        }

        .doctor-email {
            color: #666666;
            margin-bottom: 10px;
        }

        .historique-btn {
            background-color: #ffc107;
            color: #ffffff;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .historique-btn:hover {
            background-color: #ff9800;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #ffffff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .close {
            position: absolute;
            top: 5px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
            color: #666666;
        }

        .close:hover {
            color: #333333;
        }
        .fa-user-circle{
            font-size: 150px;
        }
        .btn{
    height: 35px;
    background: #0065e1;
    border: 0;
    border-radius: 5px;
    color: #fff;
    font-size: 15px;
    cursor: pointer;
    transition: all .3s;
    margin-top: 10px;
    padding: 0px 10px;
}
.btn:hover{
    opacity: 0.82;
}
    </style>
</head>
<body>
    <div class="right-links">
    <a href="home.php"> <button class="btn"><i class="fas fa-home home-icon"></i>Revenir à l'Accueil</button> </a>
    </div>
 
<div class="container">
    <h1 class="section-title">Nos Docteurs</h1>
    <div class="search-box">
        <form action="" method="GET">
            <input type="text" name="search" placeholder="Rechercher un docteur par nom ou spécialité" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <div>
        <br>
    </div>
    <div class="doctors-grid">
        <?php
        // Inclure le fichier de configuration de la base de données
        include('php/config.php');

        // Initialiser la variable de recherche
        $search_query = isset($_GET['search']) ? $_GET['search'] : '';

        // Construire la requête SQL de base
        $query = "SELECT * FROM users where Role = 'medecin'";

        // Ajouter une condition WHERE si le champ de recherche est rempli
        if (!empty($search_query)) {
            $query .= " WHERE";
            $query .= " Username LIKE '%$search_query%'";
            $query .= " OR Speciality LIKE '%$search_query%'";
        }

        // Exécuter la requête SQL
        $result = mysqli_query($con, $query);

        // Vérifier s'il y a des résultats
        if(mysqli_num_rows($result) > 0) {
            // Afficher les informations des docteurs
            while($row = mysqli_fetch_assoc($result)) {
                echo '<div class="doctor-card">';
                echo '<div class="card-content">';
                
                // Afficher l'image du docteur si le chemin de l'image est disponible
                if(!empty($row['ProfileImage'])) {
                    echo '<img src="' . $row['ProfileImage'] . '" alt="Photo de profil du Dr. ' . $row['Username'] . '" class="profile-picture">';
                } else {
                    // Afficher l'icône par défaut si l'image n'est pas disponible
                    echo '<i class="fa fa-user-circle fa-5x"></i>';
                }
                
                echo '<h2 class="doctor-name">Dr. ' . $row['Username'] . '</h2>';
                echo '<p class="doctor-email">' . $row['Email'] . '</p>';
                echo '<p class="doctor-email">' . $row['Speciality'] . '</p>';
                echo '<a href="#" class="historique-btn" data-historique="' . $row['Historique'] . '">informations du Docteur</a>';
                echo '</div>'; // Fermeture de la div "card-content"
                echo '</div>'; // Fermeture de la div "doctor-card"
            }
        } else {
            echo '<p>Aucun docteur trouvé.</p>';
        }

        // Fermer la connexion à la base de données
        mysqli_close($con);
        ?>
    </div> <!-- Fermeture de la div "doctors-grid" -->
</div> <!-- Fermeture de la div "container" -->

<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>informations du Docteur</h2>
        <p id="historique_docteur"></p>
    </div>
    </div>

<script>
    // Récupérer tous les liens "Historique du Docteur"
    var links = document.querySelectorAll('.historique-btn');

    // Pour chaque lien, ajouter un gestionnaire d'événement au clic
    links.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault(); // Empêcher le comportement par défaut du lien

            // Récupérer l'historique du docteur à partir de l'attribut data-historique
            var historique = this.getAttribute('data-historique');

            // Afficher la fenêtre modale avec l'historique correspondant
            document.getElementById('historique_docteur').innerText = historique;
            document.getElementById('modal').style.display = 'block';
        });
    });

    // Gérer la fermeture de la fenêtre modale lorsque l'utilisateur clique sur le bouton de fermeture
    document.querySelector('.close').addEventListener('click', function() {
        document.getElementById('modal').style.display = 'none';
    });
</script>

</body>
</html>
