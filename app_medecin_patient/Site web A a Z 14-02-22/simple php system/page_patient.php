

<?php
session_start();

include("php/config.php");

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
    <title>Espace Patient</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Ajouter ces styles à la fin de votre fichier CSS existant */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #DCDCDC; /* Couleur de fond de la page */
        }

        .container {
            display: flex;
            min-height: 100vh;
            
        }

        .sidebar {
            background-color:#2986cc; /* Couleur de fond du sidebar */
            color: #333;
            padding: 20px;
            width: 250px;
            
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Ombre légère */
        }

        .sidebar .title {
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar .title h2 {
            color: #007bff; /* Couleur bleue pour "sa7ti" */
            font-size: 24px;
            margin-bottom: 5px;
        }

        .sidebar .title span {
            color: #0056b3; /* Couleur bleue moins forte pour "ra7ti" */
            font-size: 18px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: white;
            color: #2986cc;
        }

        .content {
            flex: 1;
            padding: 20px;
            position: relative;
            padding-right: 350px;
        }

        /* Autres styles pour le contenu */
        .centered-card {
            margin: 0 auto;
            max-width: 600px;
        }

        .upcoming-appointment {
            background-color: #fff;
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

        .blue-card {
            background-color: #2986cc; /* Fond bleu */
            color: #fff; /* Texte blanc */
            padding: 20px; /* Espacement intérieur */
            border-radius: 10px; /* Coins arrondis */
            max-width: 600px; /* Largeur maximale de la carte */
            margin: 0 auto; /* Centrer horizontalement */
            margin-left: 20px;
        }

        .blue-card h1 {
            font-size: 24px; /* Taille du titre */
            margin-bottom: 10px; /* Espacement sous le titre */
        }

        .blue-card p {
            font-size: 16px; /* Taille du paragraphe */
            margin-bottom: 0; /* Supprimer l'espacement sous le paragraphe */
        }

        /* Styles pour le tableau des rendez-vous */
        .appointment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .appointment-table th {
            padding: 10px;
            text-align: left;
        }
        .appointment-table td {
            padding: 10px;
            text-align: left;
            background-color: #DCDCDC; /* Fond blanc pour les cellules du tableau */
        }

       
       

        .appointment-table th {
            color: #333; /* Texte noir */
            border-bottom: 1px solid #ccc; /* Bordure basse pour l'en-tête */
        }

        .appointment-table td img {
            width: 50px;
            height: 50px;
            border-radius: 10%;
            margin-right: 10px;
            float: left;
        }

        /* Styles pour le statut */
        .status-confirm {
            background-color: #a5d6a7; /* Vert pour statut confirmé */
            color: green;
        }

        .status-rejected {
            background-color: #ef9a9a; /* Rouge pour statut refusé */
            color: red;
        }
        .status-pending {
            background-color: #007bff;
        }
        /* Ajoutez ces styles à votre fichier CSS existant */
        .nav-icons {
        background-color: #2986cc;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-left: 19%;
    /* Ajustez la marge selon vos besoins */
}

.nav-icons > div {
    margin-left: 10px;
    cursor: pointer;
}

.nav-icons .profile-icon img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
}

.nav-icons > div i {
    font-size: 24px;
}
.doctor-image {
    position: absolute;
    /* Ajustez la valeur selon votre besoin pour faire dépasser l'image */
    top: 85px; 
    max-width: 150px; 
    left: 500px; 
}
.appointment-table-small {
    max-width: 10%; 
    margin: 0 auto; 
}
.upcoming-appointment-left {
    float: left; /* Alignement à gauche */
    clear: both; /* Assurez-vous qu'aucun élément ne flotte à côté */
     /* Largeur de chaque carte (50% de la largeur de l'écran moins les marges) */
    margin-right: 20px; /* Marge à droite pour espacer les cartes */
    margin-bottom: 20px; /* Marge en bas pour espacer les cartes */
    background-color: #DCDCDC;
}
.side-card {
    background-color: #2986cc;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    margin-top: 20px;
    position: absolute;
    top: 100px;
    right: 20px;
    width: 320px;
}

.side-card h3 {
    margin-top: 0;
    margin-bottom: 10px;
    font-size: 18px;
    color: #333;
}

.side-card p {
    margin: 0;
    margin-bottom: 10px;
    font-size: 16px;
    color: #666;
}

.calendar {
    margin-top: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    padding: 20px;
    flex: 0 0 300px;
    position: absolute;
    bottom: 20px;
    right: 100px;
}

.calendar-header {
    text-align: center;
    margin-bottom: 10px;
    font-size: 18px;
}

.weekdays {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.weekdays span {
    width: calc(100% / 7);
    text-align: center;
}

.days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
}

.days span {
    display: block;
    text-align: center;
    padding: 5px;
    margin-bottom: 5px;
    border-radius: 5px;
}

.days span.has-appointment {
    background-color: #2986cc; /* Arrière-plan bleu pour les jours avec rendez-vous */
    color: #fff;
}


.container {
    display: flex;
    min-height: 100vh; /* Ajustez la hauteur minimale à 100vh pour couvrir toute la page */
}

.sidebar {
    background-color: #2986cc;
    color: #333;
    padding: 20px;
    width: 250px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    height: 100vh; /* Ajustez la hauteur du sidebar pour couvrir toute la hauteur de la page */
}

.content {
    flex: 1;
    padding: 20px;
    position: relative;
    padding-right: 350px;
}


    </style>
</head>

<body>
<div class="nav-icons">
    <div class="profile-icon">
        <?php
        // Récupérer le chemin de la photo de profil de l'utilisateur depuis la base de données
        $query_profile_image = mysqli_query($con, "SELECT ProfileImage FROM users WHERE Id = $patient_id");
        if ($row_profile_image = mysqli_fetch_assoc($query_profile_image)) {
            $profile_image_path = $row_profile_image['ProfileImage'];
            echo '<img src="' . $profile_image_path . '" alt="Profile Image">';
        } else {
            // Afficher une icône par défaut si aucune photo de profil n'est disponible
            echo '<i class="fa fa-user-circle-o"></i>';
        }
        ?>
    </div>
    <div class="search-icon">
        <i class="fa fa-search"></i>
    </div>
    <div class="notification-icon">
        <i class="fa fa-bell"></i>
    </div>

        </div>
<div class="container">
        <div class="sidebar">
           
            <ul>
                <li><a href="annuler_rendezvous.php"><i class="fa fa-calendar-times-o fa-icon"></i>Annuler un rendez-vous</a></li>
                <li><a href="cherchercher_ordonnance.php"><i class="fa fa-file-medical fa-icon"></i>Chercher une ordonnance</a></li>
                <li><a href="chatPatient.php"fa fa-comments fa-icon"></i>Communiquer avec le médecin</a></li>
                <li><a href="newrdv.php"><i class="fa fa-calendar-plus-o fa-icon"></i>Prendre un rendez-vous</a></li>
                <li><a href="#voir-historique-medecin"><i class="fa fa-history fa-icon"></i>Voir l'historique du médecin</a></li>
                <li><a href="#voir-historique"><i class="fa fa-history fa-icon"></i>Voir votre historique</a></li>
            </ul>
       
</div>

        
        <div class="content">
        <h2>Bienvenue, <?php echo $patient_username; ?>!</h2>
        <p>how are you today</p>
       
        <div class="blue-card">
            <h1>find the best doctors with <br> health care</h1>
            <p>appoints the doctors and get the finest medical services</p>
            <img src="docteur-pfe.png" alt="Doctor Image" class="doctor-image">
        
        <div class="side-card">
    <?php
    // Récupérer les informations sur l'ordonnance la plus récente pour ce patient
    $query_ordonnance = mysqli_query($con, "SELECT * FROM prescriptions WHERE patient_id = $patient_id ORDER BY date_created DESC LIMIT 1");
    if ($row_ordonnance = mysqli_fetch_assoc($query_ordonnance)) {
        $medecin_id = $row_ordonnance['medecin_id'];
        $query_medecin = mysqli_query($con, "SELECT * FROM users WHERE Id = $medecin_id AND Role = 'medecin'");
        if ($row_medecin = mysqli_fetch_assoc($query_medecin)) {
            $medecin_nom = $row_medecin['Username'];
        } else {
            $medecin_nom = "Médecin introuvable";
        }

        echo '<h3>Dernière ordonnance:</h3>';
        echo '<p>Médecin: Dr. ' . $medecin_nom . '</p>';
        echo '<p>Date de création: ' . $row_ordonnance['date_created'] . '</p>';
    } else {
        echo '<h3>Pas d\'ordonnance disponible</h3>';
    }
    ?>
</div>
        </div>
        
                
        <p>vos rendez vous a venir</p>
        <div class="upcoming-appointment upcoming-appointment-left">
        <div class="upcoming-appointment">
        <table class="appointment-table appointment-table-small">
            <table class="appointment-table">
                <thead>
                    <tr>
                        <th>Médecin</th>
                        <th>Spécialité</th>
                        <th>Date</th>
                        <th>Horaire</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                                $query_rdv = mysqli_query($con, "SELECT * FROM rendez_vous WHERE PatientId = $patient_id ORDER BY DateRendezVous ASC");
                                while ($row_rdv = mysqli_fetch_assoc($query_rdv)) {
                                    $medecin_id = $row_rdv['MedecinId'];
                                    $query_medecin_rdv = mysqli_query($con, "SELECT * FROM users WHERE Id = $medecin_id AND Role = 'medecin'");
                                    if ($row_medecin_rdv = mysqli_fetch_assoc($query_medecin_rdv)) {
                                        $medecin_username_rdv = $row_medecin_rdv['Username'];
                                        $medecin_speciality_rdv = $row_medecin_rdv['Speciality'];
                                        $medecin_profile_image = $row_medecin_rdv['ProfileImage'];
                                    } else {
                                        $medecin_username_rdv = "Médecin introuvable";
                                        $medecin_speciality_rdv = "Spécialité inconnue";
                                        $medecin_profile_image = ""; // Par défaut, pas d'image
                                    }
        
                                    // Déterminer la classe de style en fonction du statut
                                    $status_class = "";
                                    if ($row_rdv['Status'] == "Confirmé") {
                                        $status_class = "status-confirm";
                                    } elseif ($row_rdv['Status'] == "Refusé") {
                                        $status_class = "status-rejected";
                                    } else {
                                        $status_class = "status-pending";
                                    } 
        
                                    echo '<tr>';
                                    echo '<td><img src="' . $medecin_profile_image . '" alt="Profile Image">' . $medecin_username_rdv . '</td>';
                                    echo '<td>' . $medecin_speciality_rdv . '</td>';
                                    echo '<td>' . $row_rdv['DateRendezVous'] . '</td>';
                                    echo '<td>' . $row_rdv['HeureRendezVous'] . '</td>';
                                    echo '<td class="' . $status_class . '">' . $row_rdv['Status'] . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                        
                </tbody>
            </table>
        </table>
       
    </div>
</div>
        </div>
        <div class="calendar">
                        <div class="calendar-header">
                            <span class="arrow" id="prev-week">&#8249;</span>
                            <span id="month-year"></span>
                            <span class="arrow" id="next-week">&#8250;</span>
                        </div>
                        <div class="calendar-days">
                            <div class="weekdays">
                                <span>Dim</span>
                                <span>Lun</span>
                                <span>Mar</span>
                                <span>Mer</span>
                                <span>Jeu</span>
                                <span>Ven</span>
                                <span>Sam</span>
                            </div>
                            <div class="days" id="calendar-days"></div>
                        </div>
                    </div>
        </div>
        
    </div>  
    
        

</body>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarDays = document.getElementById('calendar-days');
    const monthYear = document.getElementById('month-year');
    const prevWeek = document.getElementById('prev-week');
    const nextWeek = document.getElementById('next-week');
    const weekdays = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];
    let currentDate = new Date();

    function fillCalendar() {
        const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
        const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

        monthYear.textContent = currentDate.toLocaleString('fr-FR', { month: 'long' }) + ' ' + currentDate.getFullYear();

        let daysHTML = '';

        for (let i = 0; i < 7; i++) {
            if (i === firstDayOfMonth) break; // Stop après la première ligne
            const day = i + 1;
            const dayClass = checkAppointment(day) ? 'has-appointment' : '';
            daysHTML += `<span class="${dayClass}">${day}</span>`;
        }

        calendarDays.innerHTML = daysHTML;
    }

    function checkAppointment(day) {
        // Ajoutez ici la logique pour vérifier si le patient a un rendez-vous ce jour du mois
        // Par exemple, vérifiez votre base de données ou utilisez des données simulées
        const appointments = [5, 10, 15]; // Jours avec rendez-vous simulés
        return appointments.includes(day);
    }

    prevWeek.addEventListener('click', function () {
        currentDate.setDate(currentDate.getDate() - 7);
        fillCalendar();
    });

    nextWeek.addEventListener('click', function () {
        currentDate.setDate(currentDate.getDate() + 7);
        fillCalendar();
    });

    fillCalendar();
});

</script>
</html>

