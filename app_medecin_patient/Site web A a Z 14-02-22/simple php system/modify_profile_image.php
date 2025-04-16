<?php
session_start();

include("php/config.php");
if(!isset($_SESSION['valid'])){
    header("Location: index.php");
    exit();
}

if(isset($_POST['submit'])){
    $id = $_SESSION['id'];

    // Traitement de la nouvelle image de profil
    $file = $_FILES['new_profile_image'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    // Vérifier si l'image a été téléchargée sans erreur
    if($file_error === 0){
        // Déplacer l'image téléchargée vers un répertoire sur le serveur
        $file_destination = 'uploads/' . $file_name;

        if(move_uploaded_file($file_tmp, $file_destination)){
            // Mettre à jour le chemin de la nouvelle image de profil dans la base de données
            $update_query = mysqli_query($con, "UPDATE users SET ProfileImage='$file_destination' WHERE Id=$id");

            if($update_query){
                header("Location: profil.php");
                exit();
            } else {
                echo "Une erreur s'est produite lors de la mise à jour de l'image de profil.";
            }
        } else {
            echo "Une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    } else {
        echo "Erreur lors du téléchargement de l'image.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style1.css">
    <title>Modifier la Photo de Profil</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php"> Revenir à Votre espace</a></p>
        </div>

        <div class="right-links">
            <a href="#">Change Profile</a>
            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>
        </div>
    </div>
    <div class="container">
        <div class="box form-box">
            <header>Modifier la Photo de Profil</header>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="field input">
                    <label for="new_profile_image">Nouvelle Photo de Profil</label>
                    <input type="file" name="new_profile_image" id="new_profile_image" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Modifier">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
