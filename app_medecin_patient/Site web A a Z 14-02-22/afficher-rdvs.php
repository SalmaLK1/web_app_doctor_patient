<?php
// Code pour se connecter à la base de données
include("simple php system/php/config.php");

// Vérifier si la requête a été effectuée via la méthode POST et si la date est définie
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selectedDate'])) {
    // Récupérer la date depuis la requête POST
    $date = $_POST['selectedDate'];

    // Préparer la requête SQL pour récupérer les rendez-vous pour la date donnée
    $sql = "SELECT Nom, Prenom, HeureRendezVous FROM rendez_vous WHERE DateRendezVous = ?";
    
    
    // Préparer la déclaration SQL
    if ($stmt = $con->prepare($sql)) {
        // Liaison des paramètres
        $stmt->bind_param("s", $date);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Créer un tableau pour stocker les rendez-vous par tranche horaire
        $rendezvousArray = array_fill(0, 15, array());

        // Parcourir les résultats et ajouter les rendez-vous au tableau
        while ($row = $result->fetch_assoc()) {
            $heure = intval(substr($row['HeureRendezVous'], 0, 2));
            $minute = intval(substr($row['HeureRendezVous'], 3, 2));
            $index = ($heure - 9) * 2 + ($minute >= 30 ? 1 : 0);
            $rendezvousArray[$index][] = $row['Nom'] . " " . $row['Prenom'];
        }

        // Fermer la déclaration
        $stmt->close();
    } else {
        echo "<p>Erreur lors de la préparation de la requête SQL.</p>";
    }

    // Envoyer les données au format JSON
    header('Content-Type: application/json');
    echo json_encode($rendezvousArray);
    exit();
    
}

?>
<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='utf-8' />
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <style>
        .schedule-item {
    margin-bottom: 10px;
    font-size: 16px; /* Taille de la police */
    color: #333; /* Couleur du texte */
}

.schedule-item::before {
    content: '\2022'; /* Utilisation d'un point pour marquer chaque rendez-vous */
    color: #FF5733; /* Couleur du point */
    margin-right: 5px;
}

.schedule-item span {
    font-weight: bold; /* Rendre le nom du patient en gras */
    margin-right: 10px;
}

.schedule-item.consultation::before {
    color: #007bff; /* Couleur du point pour les consultations */
}

.schedule-item.controle::before {
    color: #28a745; /* Couleur du point pour les contrôles */
}

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 80%;
            max-width: 1200px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        #calendar {
            flex: 1;
            margin-right: 20px;
        }
        .schedule {
            flex: 1;
            background-color: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .schedule-item {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <header>
        <a href="/simple php system/home.php"> <button class="btn"><i class="fas fa-home home-icon"></i>accueil</button> </a>
    </header>
    <div class="container">
        <div id='calendar'></div>
        <div class="schedule" id="schedule"></div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                selectable: true,
                select: function(info) {
                    var date = info.startStr;
                    loadSchedule(date);
                }
            });
            calendar.render();
        });

        function loadSchedule(date) {
            var schedule = document.getElementById("schedule");
            schedule.innerHTML = "";

            // Envoyer une requête POST au serveur pour récupérer les rendez-vous
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var rendezvousArray = JSON.parse(xhr.responseText);
                    displaySchedule(rendezvousArray);
                }
            };
            xhr.send("selectedDate=" + date);
        }

        function displaySchedule(rendezvousArray) {
    var schedule = document.getElementById("schedule");
    schedule.innerHTML = "";

    for (var i = 0; i < rendezvousArray.length; i++) {
        var hour = "9:00";
        if (i % 2 === 1) {
            hour = "9:30";
        }
        if (i > 1) {
            hour = (Math.floor(i / 2) + 9) + ":" + (i % 2 === 0 ? "00" : "30");
        }

        for (var j = 0; j < rendezvousArray[i].length; j++) {
            var patientName = rendezvousArray[i][j];
            var scheduleItem = document.createElement("div");
            scheduleItem.classList.add("schedule-item");
            
            // Ajouter une classe en fonction du type de rendez-vous
            var indication = rendezvousArray[i].length > 1 ? "Consultation" : "Contrôle";
            scheduleItem.classList.add(indication.toLowerCase());
            
            // Afficher le nom du patient et le type de rendez-vous
            scheduleItem.innerHTML = '<span>' + patientName + '</span>' + hour;
            schedule.appendChild(scheduleItem);
        }
    }
}

    </script>

    <?php
    if (isset($rendezvousArray)) {
        echo "<script>displaySchedule(" . json_encode($rendezvousArray) . ");</script>";
    }
    ?>
</body>
</html>