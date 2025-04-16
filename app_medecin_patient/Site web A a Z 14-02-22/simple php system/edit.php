<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style1.css">
    <title>Modifier Votre Profil</title>
</head>
<body>
    <?php 
    session_start();
    include("php/config.php");

    // Vérifier si l'utilisateur est connecté
    if(!isset($_SESSION['valid'])){
        header("Location: index.php");
        exit(); // Arrêter l'exécution du script après la redirection
    }

    // Récupérer les informations de l'utilisateur
    $id = $_SESSION['id'];
    $query = mysqli_query($con, "SELECT * FROM users WHERE Id = $id");
    if($result = mysqli_fetch_assoc($query)){
        $res_Uname = $result['Username'];
        $res_Email = $result['Email'];
        $res_Birthdate = $result['Birthdate'];
        $res_Role = $result['Role']; // Ajout de la récupération du rôle de l'utilisateur
        $res_Speciality = $result['Speciality'];
        $profile_image = $result['ProfileImage'];
    }
    ?>

    <header>
        <div class="nav">
            <div class="logo">
                <img src="favicon.png.webp" alt="logo">
                <p>A healthy outside starts from the inside </p>
            </div>
            <div class="right-links">
                <a href="php/logout.php"> <button class="btn">Déconnexion</button> </a>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <div class="box form-box">
                <header>Modifier Votre Profil</header>
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
                        <input type="date" name="birthdate" id="birthdate" value="<?php echo $res_Birthdate; ?>" autocomplete="off" required>
                    </div>

                    <div class="field input">
                        <label for="role">Rôle</label>
                        <select name="role" id="role" onchange="showSpecialityField()" required>
                            <option value="patient" <?php echo $res_Role === "patient" ? "selected" : ""; ?>>Patient</option>
                            <option value="medecin" <?php echo $res_Role === "medecin" ? "selected" : ""; ?>>Médecin</option>
                            <option value="admin" <?php echo $res_Role === "admin" ? "selected" : ""; ?>>Administrateur</option>
                        </select>
                    </div>

                    <div class="field input" id="speciality_field" <?php echo $res_Role === "medecin" ? "style='display:block;'" : "style='display:none;'"; ?>>
                        <label for="speciality">Spécialité</label>
                        <input type="text" name="speciality" id="speciality" value="<?php echo $res_Speciality; ?>" autocomplete="off">
                    </div>
                
                    <div class="field1">
                        <input type="submit" class="btn" name="submit" value="Modifier" required>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Fonction pour afficher ou masquer le champ de spécialité en fonction du rôle sélectionné
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
