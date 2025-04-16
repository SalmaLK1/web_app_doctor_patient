<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style1.css">
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Inscription</title>
    <style>
        .back-link {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #0056b3;
        }

        .home-icon {
            margin-right: 5px;
        }
        .logo1{
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="logo1">
    <a href="index.php" class="back-link"><i class="fas fa-home home-icon"></i> Revenir à l'accueil</a>
</div>
<div class="container">
    <div class="box form-box">
        
    <?php
include("php/config.php");
if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $birthdate = $_POST['birthdate'];
    $role = $_POST['role'];
    $speciality = ($_POST['role'] === "medecin") ? $_POST['speciality'] : "";
    $password = $_POST['password'];
    $conf_password = $_POST['conf_password'];

    // Vérification de l'email unique
    $verify_query = mysqli_query($con,"SELECT Email FROM users WHERE Email='$email'");
    if(mysqli_num_rows($verify_query) != 0){
        echo "<div class='message'>
                  <p>Cet e-mail est déjà utilisé, veuillez en choisir un autre!</p>
              </div> <br>";
        echo "<a href='javascript:self.history.back()'><button class='btn'>Retour</button>";
    }
    else{
        // Traitement de l'image
        $file = $_FILES['profile_image'];
        $file_name = $file['name'];
        $file_tmp = $file['tmp_name'];
        $file_size = $file['size'];
        $file_error = $file['error'];

        // Vérifier si l'image a été téléchargée sans erreur
        if($file_error === 0){
            // Déplacer l'image téléchargée vers un répertoire sur le serveur
            $file_destination = '../uploads/' . $file_name;

            if(move_uploaded_file($file_tmp, $file_destination)){
                // Enregistrer le chemin de l'image dans la base de données
                if ($role === "medecin") {
                    mysqli_query($con,"INSERT INTO users(Username,Email,Birthdate,Password,conf_password,ProfileImage,Speciality,Role) VALUES('$username','$email','$birthdate','$password','$conf_password','$file_destination', '$speciality', '$role')") or die("Erreur survenue");
                } else {
                    mysqli_query($con,"INSERT INTO users(Username,Email,Birthdate,Password,conf_password,ProfileImage,Role) VALUES('$username','$email','$birthdate','$password','$conf_password','$file_destination', '$role')") or die("Erreur survenue");
                }

                echo "<div class='message'>
                          <p>Inscription réussie!</p>
                      </div> <br>";
                echo "<a href='index.php'><button class='btn'>Se Connecter Maintenant</button>";
            } else {
                echo "Une erreur s'est produite lors du téléchargement de votre fichier.";
            }
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    }}
       
        else{
        ?>
        <header>Inscrivez-Vous</header>
        <form action="" method="post" onsubmit="return checkPassword()" enctype="multipart/form-data">
            <div class="field input">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username" autocomplete="off" required>
            </div>
            <div class="field input">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" autocomplete="off" required>
            </div>
            <div class="field input">
                <label for="birthdate">Date de Naissance</label>
                <input type="date" name="birthdate" id="birthdate" autocomplete="off" required>
            </div>
            <div class="field input">
            
            <label for="role">Rôle :</label>
            <select name="role" id="role" onchange="showSpecialityField()" required>
                <option value="patient">Patient</option>
                <option value="medecin">Médecin</option>
                <option value="admin">Administrateur</option>
            </select>
        
            </div>
            <div class="field input" id="speciality_field" style="display: none;">
                <label for="speciality">Spécialité</label>
                <select name="speciality" id="speciality">
                    <option value="">Choisir une spécialité</option>
                    <option value="Cardiologue">Cardiologue</option>
                    <option value="Dermatologue">Dermatologue</option>
                    <option value="Gynécologue">Gynécologue</option>
                    <option value="Pédiatre">Pédiatre</option>
                    <option value="Orthopédiste">Orthopédiste</option>
                    <option value="Psychiatre">Psychiatre</option>
                    <option value="Radiologue">Radiologue</option>
                    <option value="Ophtalmologiste">Ophtalmologiste</option>
                    <option value="Chirurgien général">Chirurgien général</option>
                    <option value="Neurologue">Neurologue</option>
                    <option value="Oto-rhino-laryngologiste">Oto-rhino-laryngologiste (ORL)</option>
                    <option value="Urologue">Urologue</option>
                    <option value="Oncologue">Oncologue</option>
                    <option value="Endocrinologue">Endocrinologue</option>
                    <option value="Gastro-entérologue">Gastro-entérologue</option>
                </select>
            </div>
            <div class="field input">
                <label for="password">Mot De Passe</label>
                <input type="password" name="password" id="password" autocomplete="off" required>
            </div>
            <div class="field input">
                <label for="conf_password">Confirmation Mot De Passe</label>
                <input type="password" name="conf_password" id="conf_password" autocomplete="off" required>
            </div>
            <div class="field input">
                <label for="profile_image">Image de Profil</label>
                <input type="file" name="profile_image" id="profile_image" required>
            </div>
            
            
        <div class="alert alert-danger" role="alert" id="passwordMismatchAlert" style="display: none;">
                Les mots de passe ne correspondent pas.
            </div>
            <div class="field">
                <input type="submit" class="btn" name="submit" value="S'inscrire">
            </div>
            <div class="links">
                Etes-Vous un membre? <a href="index.php">Se Connecter</a>
            </div>
        </form>
        <?php } ?>
    </div>
</div>
<script>
    window.onload = function() {
        showSpecialityField();
    }
    function checkPassword() {
        let password = document.getElementById("password").value;
        let conf_password = document.getElementById("conf_password").value;
        let passwordMismatchAlert = document.getElementById("passwordMismatchAlert");
        
        if(password.length === 0){
            alert("Le mot de passe ne peut pas être vide.");
            return false;
        }
        
        if(password !== conf_password){
            passwordMismatchAlert.style.display = "block";
            return false;
        }
        
        passwordMismatchAlert.style.display = "none";
        return true;
    }
    function showSpecialityField() {
        var userType = document.getElementById("role").value;
        var specialityField = document.getElementById("speciality_field");

        if (userType === "medecin") {
            specialityField.style.display = "block";
        } else {
            specialityField.style.display = "none";
        }
    }
</script>
</body>
</html>
