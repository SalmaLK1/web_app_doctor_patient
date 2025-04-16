<?php
session_start();
include('php/config.php');
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

$patient_id = $_SESSION['id'];

$query = mysqli_query($con, "SELECT * FROM users WHERE Id = $patient_id");
if ($result = mysqli_fetch_assoc($query)) {
    $patient_username = $result['Username'];
    $profile_image = $result['ProfileImage'];
} else {
}

// Récupérer la liste des spécialités disponibles
$query_specialities = mysqli_query($con, "SELECT DISTINCT Speciality FROM users WHERE Role='medecin'");
$specialities = [];
while ($row_speciality = mysqli_fetch_assoc($query_specialities)) {
    $specialities[] = $row_speciality['Speciality'];
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Ajouter ces styles à la fin de votre fichier CSS existant */

        .content {
            flex: 1;
            padding: 20px;
            position: relative;
        }

        .notification {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .notification a {
            color: #333;
        }

        .search-box {
            margin-bottom: 20px;
        }

        .search-box form {
            display: flex;
            align-items: center;
        }

        .search-box input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            outline: none;
        }

        .search-box button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            outline: none;
        }

        .search-box button:hover {
            background-color: #0056b3;
        }

        .search-box i {
            margin-right: 5px;
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .doctor-card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-content {
            padding: 20px;
            text-align: center;
        }

        .profile-picture {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .doctor-name {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .doctor-email {
            margin: 0;
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .historique-btn {
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .historique-btn:hover {
            background-color: #0056b3;
        }

        .rdv-btn {
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-right: 10px;
        }

        .rdv-btn:hover {
            background-color: #218838;
        }

        body {
           display: flex;
           justify-content: center;
           align-items: flex-start;
           min-height: 100vh;
           margin: 0;
           padding: 0;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            padding: 20px;
            box-sizing: border-box;
        }
        centered-search{
            max-width: 600px;
            width: 100%;


        }

        .sidebar {
            background-color: #333;
            color: #fff;
            padding: 20px;
            width: 250px;
        }

        .content {
            flex: 1;
            padding: 20px;
        }


        .profile-section {
            margin-bottom: 30px;
            text-align: center;
        }

        .profile-section img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .profile-section h2 {
            margin: 0;
            font-size: 18px;
        }


        .functionality {
            margin-bottom: 30px;
        }

        .functionality h2 {
            margin-bottom: 10px;
            font-size: 16px;
            color: #333;
        }

        .functionality p {
            margin-bottom: 20px;
            color: #666;
        }

        .fa-icon {
            margin-right: 10px;
        }


        ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        ul li {
            margin-bottom: 10px;
        }

        ul li a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        ul li a:hover {
            background-color: #555;
        }

        .content {
            flex: 1;
            padding: 20px;
            position: relative;
        }


        .notification {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .notification a {
            color: #333;
        }


        .search-bar {
            margin-bottom: 20px;
        }

        .search-bar form {
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px 0 0 5px;
            outline: none;
        }

        .search-bar button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
            outline: none;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }


        .search-bar i {
            margin-right: 5px;
        }
        .upcoming-appointment {
    background-color: #f8f9fa;
    border-radius: 5px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.upcoming-appointment h2 {
    margin-top: 0;
    margin-bottom: 20px;
    font-size: 20px;
    color: #333;
}

.upcoming-appointment p {
    margin: 0;
    margin-bottom: 10px;
    font-size: 16px;
    color: #666;
}

.upcoming-appointment p:last-child {
    margin-bottom: 0;
}

.upcoming-appointment .no-appointment {
    font-style: italic;
    color: #999;
}

.upcoming-appointment .appointment-details {
    border-top: 1px solid #ccc;
    padding-top: 10px;
    margin-top: 10px;
}
.search-box{
    margin-bottom: 20px;
    width: 100%;
    max-width: 600px;
}
.doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .doctor-card {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-content {
            padding: 20px;
            text-align: center;
        }

        .profile-picture {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .doctor-name {
            margin: 0;
            font-size: 18px;
            color: #333;
            font-weight: bold;
        }

        .doctor-email {
            margin: 0;
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .speciality {
            margin: 0;
            font-size: 14px;
            color: #555;
            margin-bottom: 15px;
        }

        .info-btn {
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            margin-right: 5px;
        }

        .info-btn:hover {
            background-color: #0056b3;
        }

        .appointment-btn {
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .appointment-btn:hover {
            background-color: #218838;
        }

    </style>
</head>
<body>
<div  class="container">
    <div class="centered-search">
<div class="search-box">
                <form action="" method="GET">
                    <input type="text" name="search" placeholder="Rechercher un docteur par nom ou spécialité" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                    <button type="submit"><i class="fa fa-search"></i></button>
                    <select name="speciality">
                        <option value="">Toutes les spécialités</option>
                        <?php foreach ($specialities as $speciality) : ?>
                            <option value="<?php echo $speciality; ?>"><?php echo $speciality; ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
        
</div>

   <div class="doctors-grid">
                <?php
                // Construction de la requête SQL de base
                $query = "SELECT * FROM users WHERE Role='medecin'";

                // Ajouter une condition WHERE pour la spécialité si elle est sélectionnée dans le formulaire
                if (!empty($_GET['speciality'])) {
                    $speciality_filter = mysqli_real_escape_string($con, $_GET['speciality']);
                    $query .= " AND Speciality='$speciality_filter'";
                }

                // Exécuter la requête SQL modifiée
                $result = mysqli_query($con, $query);

                // Vérifier s'il y a des résultats
                if (mysqli_num_rows($result) > 0) {
                    // Afficher les informations des docteurs
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="doctor-card">';
                        echo '<div class="card-content">';

                        // Afficher l'image, le nom, l'email, etc., du docteur
                        if (!empty($row['ProfileImage'])) {
                            echo '<img src="' . $row['ProfileImage'] . '" alt="Photo de profil du Dr. ' . $row['Username'] . '" class="profile-picture">';
                        } else {
                            echo '<i class="fa fa-user-circle fa-5x"></i>';
                        }

                        echo '<h2 class="doctor-name">Dr. ' . $row['Username'] . '</h2>';
                        echo '<p class="doctor-email">' . $row['Email'] . '</p>';
                        echo '<p class="doctor-email">' . $row['Speciality'] . '</p>';
                        echo '<a href="#" class="historique-btn" data-historique="' . $row['Historique'] . '">informations du Docteur</a>';
                        echo '<a href="prendre_rdv.php?medecin_id=' . $row['Id'] . '" class="rdv-btn">Prendre un rendez-vous</a>';
                        echo '</div>'; // Fermeture de la div "card-content"
                        echo '</div>'; // Fermeture de la div "doctor-card"
                    }
                } else {
                    echo '<p>Aucun docteur trouvé.</p>';
                }

                // Fermer la connexion à la base de données
               
                ?>
            </div>
    
</body>
</html>