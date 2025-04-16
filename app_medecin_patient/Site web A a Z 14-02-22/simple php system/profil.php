<?php 
session_start();

include("php/config.php");

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['valid']) && $_SESSION['Role']==='medecin'){
    header("Location: index.php");
    exit(); // Arrêter l'exécution du script après la redirection
}

$id = $_SESSION['id'];
// Récupérer le chemin de l'image de profil
$query = mysqli_query($con, "SELECT * FROM users WHERE Id = $id");
if($result = mysqli_fetch_assoc($query)){
    $res_Uname = $result['Username'];
    $res_Email = $result['Email'];
    $Birthdate = $result['Birthdate'];
    $Speciality=$result['Speciality'];
    $res_id = $result['Id'];
    $profile_image = $result['ProfileImage']; // Ajout de la récupération du chemin de l'image de profil
} else {
    // Gérer le cas où aucun utilisateur n'est trouvé avec cet ID
    // Par exemple, rediriger l'utilisateur vers une page d'erreur ou afficher un message d'erreur
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/style1.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Style pour l'image de profil */
        .profile-image {
            border-radius: 50%; /* Pour rendre l'image arrondie */
            width: 150px; 
            height: 150px; /* Ajustez la hauteur selon vos besoins */
            object-fit: cover; /* Pour s'assurer que l'image est complètement couverte dans le conteneur */
        }

        .img {
            display: flex;
            justify-content: center;
        }

        .infos {
            font-size: 20px;
            text-align: center;
            margin-top: 20px;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            margin-left: 20%;
            margin-right: 20%;
            margin-top: 20px;
        }

        .btn1 {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
        }

        main {
            display: flex;
            justify-content: center;
            margin: 0;
        }

        .container {
            margin-top: 0;
        }

        .box.form-box {
            width: 80%;
            height: 600px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .doctor-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
   

    <main>
        <div class="container">
            <div class="box form-box">
                <p>Bonjour <span><?php echo strtoupper($res_Uname) ." !"?> </span></p>
                <!-- Afficher l'image de profil -->
                <div class="img">
                    <?php if (!empty($profile_image)) : ?>
                        <img src="<?php echo $profile_image; ?>" alt="Image de profil" class="profile-image">
                    <?php else : ?>
                        <i class="fa fa-user-circle fa-lg"></i> <!-- Icône par défaut -->
                    <?php endif; ?>
                </div>
                <div class="btn-container">
                    <a href="delete_profile_image.php" class="btn1" id="supprimer">Supprimer</a>
                    <a href="modify_profile_image.php" class="btn1" id="modifier">Modifier</a>
                </div>
                <div class="infos">  
                    <p>  Nom d'utilisateur :  <span class="span"><?php echo $res_Uname; ?></span><br>
                        Email :  <span class="span"> <?php echo $res_Email; ?></span> <br>
                        Date de Naissance : <span class="span"><?php echo $Birthdate ; ?></span> <br>
                        Spécialité : <span class="span"><?php echo $Speciality; ?></span>
                    </p>
                </div>
            </div>
            <div class="box form-box">
                <header>Changer Votre Profil</header>
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="field input">
                        <label for="username">Nom d'utilisateur</label>
                        <input type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="email">Email</label>
                        <input type="text" name="email" id="email" value="<?php echo $res_Email; ?>" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="birthdate">Date de Naissance</label>
                        <input type="date" name="birthdate" id="birthdate" value="<?php echo $Birthdate; ?>" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="speciality">Spécialité</label>
                        <input type="text" name="speciality" id="speciality" value="<?php echo $Speciality; ?>" autocomplete="off" required>
                    </div>
                
                    <div class="field1">
                        <input type="submit" class="btn" id="btn2" name="submit" value="Modifier" required>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
</html>
