<?php 
session_start();

include("php/config.php");
if(!isset($_SESSION['valid'])){
    header("Location: index.php");
}

// Vérifier le rôle de l'utilisateur

    // Si l'utilisateur est un médecin, afficher les informations spécifiques aux médecins
    $id = $_SESSION['id'];
    // Récupérer le chemin de l'image de profil et d'autres informations du médecin à partir de la base de données
    $query = mysqli_query($con, "SELECT * FROM users WHERE Id = $id");
    if($result = mysqli_fetch_assoc($query)){
        $res_Uname = $result['Username'];
        $res_Email = $result['Email'];
        $res_Age = $result['Birthdate'];
        $res_speciality=$result['Speciality'];
        $res_id = $result['Id'];
        $profile_image = $result['ProfileImage'];
        // Afficher les informations du médecin
    } else {
        // Gérer le cas où aucun utilisateur n'est trouvé avec cet ID
        // Par exemple, rediriger l'utilisateur vers une page d'erreur ou afficher un message d'erreur
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="/style.css">
    <style>
          canvas {
            max-width: 300px; /* Limiter la largeur du graphique */
            max-height: 300px; /* Limiter la hauteur du graphique */
            margin: 0 auto; /* Centrer le graphique horizontalement */
            display: block; /* Afficher le graphique en tant que bloc */
        }
        .container{
            width: 40%;
            margin: 100px auto;
            margin-left: 20%;
            display: flex;
            justify-content:center;
            align-items: center;
        }
        
    </style>
</head>
<body>
<h1>Bienvenue, <?php echo $res_Uname ;?> dans votre Espace!</h1>
<?php
// Récupérer les données depuis la base de données
include("php/config.php");

$sql = "SELECT MONTH(DateRendezVous) AS Month, 
               COUNT(*) AS TotalPatients, 
               SUM(CASE WHEN Status = 'accepter' THEN 1 ELSE 0 END) AS TotalRdvConfirmes
        FROM rendez_vous 
        WHERE MedecinId = $id 
        GROUP BY MONTH(DateRendezVous)";
$result = $con->query($sql);

// Initialiser des tableaux pour stocker les données formatées
$months = [];
$totalPatients = [];
$totalRdvConfirmes = [];

// Parcourir les résultats et stocker les données dans les tableaux
while ($row = $result->fetch_assoc()) {
    $months[] = ucfirst(strftime('%B', mktime(0, 0, 0, $row['Month'], 1)));
    $totalPatients[] = $row['TotalPatients'];
    $totalRdvConfirmes[] = $row['TotalRdvConfirmes'];
}


?>
<div class="container">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa fa-users"></i> Nombre de patients</h5>
                    <?php if (!empty($totalPatients)) : ?>
                        <p class="card-text"><?php echo $totalPatients[0]; ?></p>
                    <?php else : ?>
                        <p class="card-text">Aucun patient trouvé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fa fa-calendar"></i> Nombre de rendez-vous confirmés</h5>
                    <?php if (!empty($totalRdvConfirmes)) : ?>
                        <p class="card-text"><?php echo $totalRdvConfirmes[0]; ?></p>
                    <?php else : ?>
                        <p class="card-text">Aucun rendez-vous confirmé.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="nav">

    </div>
   
    <div class="sidebar">
     
        <div class="profile">
            <div class="profile-image">
                
                <?php if (!empty($profile_image)) : ?>
                    <img src="<?php echo $profile_image; ?>" alt="Image de profil" class="profile-image">
                <?php else : ?>
                    <i class="fa fa-user-circle fa-lg"></i> 
                <?php endif; ?>
            </div>
        
        </div>
        <nav class="menu">
            <ul>
                
              <li><a href="listepatient.php?medecin_id=<?php echo $res_id; ?>">
        <i class="fa fa-users"></i>
        <span>PATIENTS</span>
    </a></li>
    <li><a href="afficher-rdvs.php?medecin_id=<?php echo $res_id; ?>">
        <i class="fa fa-calendar"></i>
        <span>AGENDA</span>
    </a></li>
    <li><a href="/rapports.php?medecin_id=<?php echo $res_id; ?>">
        <i class="fa fa-file-text-o"></i>
        <span>RAPPORTS</span>
    </a></li>
    <li><a href="#">
        <i class="fa fa-bar-chart"></i>
        <span>STATISTIQUES</span>
    </a></li>
    <li><a href="profil.php" class="profil">
        <?php if (!empty($profile_image)) : ?>
            <img src="<?php echo $profile_image; ?>" alt="Image de profil" class="profile-icon">
        <?php else : ?>
            <i class="fa fa-user-circle fa-lg"></i>
        <?php endif; ?>
        Profil
    </a></li>
    <li><a href="php/logout.php">
        <i class="fa fa-sign-out"></i>
        <span>Déconnexion</span>
    </a></li>
            </ul>
        </nav>
    </div>
    </div>
    <div class="main-content">
        <!-- Ajout de la cloche de notification -->
        
<a href="#" class="notification-bell">
    <i class="fa fa-bell"></i>
    <span class="notification-badge">3</span> <!-- Nombre de notifications -->
</a>

<!-- Ajout d'un lien vers le chat avec une icône -->
<a href="chat.php?id=<?php echo $id; ?>" class="chat-link">
    <i class="fa fa-comments chat-icon"></i> Accéder au chat
</a>


       
        
   
    
        <!-- Statistiques -->
        <div class="container">
            <h1>Statistiques</h1>
            <canvas id="myChart"></canvas>
        </div>
    
    
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 

<script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'polarArea',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Nombre de patients',
                data: <?php echo json_encode($totalPatients); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            },
            {
                label: 'Nombre de rendez-vous confirmés',
                data: <?php echo json_encode($totalRdvConfirmes); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                // Vous n'avez pas besoin d'ajuster l'échelle Y pour un graphique en forme de secteur
                // Supprimez donc la configuration de l'échelle Y
            }
        }
    });
</script>

    </div>   
</body>
</html>
