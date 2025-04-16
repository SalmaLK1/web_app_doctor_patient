<?php
include("php/config.php");
session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    $result = mysqli_query($con, "SELECT * FROM users WHERE Email='$email' AND Password='$password' ") or die("Select Error");
    $row = mysqli_fetch_assoc($result);

    if (is_array($row) && !empty($row)) {
        $_SESSION['valid'] = $row['Email'];
        $_SESSION['username'] = $row['Username'];
        $_SESSION['age'] = $row['Age'];
        $_SESSION['id'] = $row['Id'];

        // Vérifiez si l'utilisateur est un patient ou un médecin
        $role = strtolower($row['Role']); // Convertir en minuscules pour assurer l'insensibilité à la casse
        if ($role === 'patient') {
            header("Location: page_patient.php");
            exit(); 
        } elseif ($role === 'medecin') {
            header("Location: home.php");
            exit(); 
        }elseif ($role === 'admin') {
            header("Location: ../Admin/Admin.php");
            exit();
         }
         else {
            echo "<div class='message'>
                    <p>Invalid Role</p>
                  </div> <br>";
        }
    } else {
        echo "<div class='message'>
              <p>Wrong Username or Password</p>
               </div> <br>";
        echo "<a href='index.php'><button class='btn'>Go Back</button>";
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
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Login</title>
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
        .logo{
            margin-top: 20px;
            margin-left: 20px;
        }
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
    <a href="/index.php" class="back-link"><i class="fas fa-home home-icon"></i> Revenir à l'accueil</a>
</div>

<div class="container login-box">
    <div class="box form-box">
          <header>Se Connecter</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Mot De Passe</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>
                

                <div class="field">
                    
                    <input style="margin-top: 1rem;" type="submit" class="btn btn-primary" name="submit" value="Login" required>
                </div>
                <div class="links">
                    Si Vous n'avez Pas De Compte? <a href="register.php">Inscrivez-Vous</a>
                </div>
            </form>
        </div>
      </div>
</body>
</html>
