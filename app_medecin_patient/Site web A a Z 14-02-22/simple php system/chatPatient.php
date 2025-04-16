<?php
session_start();
include("php/config.php");

// Vérifier si l'utilisateur est connecté en tant que patient
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit(); // Arrêter l'exécution du script
}

// Récupérer l'ID du patient depuis la session
$user_id = $_SESSION['id'];
$user_username = isset($_SESSION['Username']) ? $_SESSION['Username'] : '';

// Récupérer les médecins avec lesquels le patient a un rendez-vous ou a essayé de prendre un rendez-vous
$doctors_query = mysqli_query($con, "SELECT users.Id AS doctor_id, users.Username AS doctor_username 
                                     FROM users 
                                     INNER JOIN rendez_vous ON users.Id = rendez_vous.MedecinId 
                                     WHERE rendez_vous.PatientId = '$user_id'");

// Initialiser un tableau pour stocker les noms des médecins
$doctors_names = array();

// Si la requête s'est bien exécutée et qu'il y a des médecins
if ($doctors_query && mysqli_num_rows($doctors_query) > 0) {
    // Parcourir les résultats de la requête
    while ($doctor_row = mysqli_fetch_assoc($doctors_query)) {
        // Ajouter le nom du médecin au tableau avec son ID comme clé
        $doctors_names[$doctor_row['doctor_id']] = $doctor_row['doctor_username'];
    }
}

// Traiter l'envoi d'un nouveau message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];
    $selected_doctor_id = isset($_POST['selected_doctor']) ? $_POST['selected_doctor'] : '';

    // Récupérer le nom de l'utilisateur connecté depuis la session (patient)
    $sender_name = isset($_SESSION['Username']) ? $_SESSION['Username'] : '';

    // Vérifier si le message n'est pas vide et si le médecin est sélectionné
    if (!empty($message) && !empty($selected_doctor_id)) {
        // Insérer le nouveau message dans la base de données avec les champs nécessaires
        $insert_query = "INSERT INTO messages (sender_id, receiver_id, message, timestamp, sender_name) 
                         VALUES ('$user_id', '$selected_doctor_id', '$message', NOW(), '$sender_name')";
        $insert_result = mysqli_query($con, $insert_query);
        if (!$insert_result) {
            echo "Erreur lors de l'insertion du message : " . mysqli_error($con);
        } else {
            // Redirection vers la même page pour éviter la duplication des messages
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        echo "Veuillez saisir un message et sélectionner un médecin.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            width: 80%;
            max-width: 1200px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            padding: 20px;
            background-color: #007bff;
            color: #fff;
            overflow-y: auto;
        }

        .sidebar h2 {
            margin-bottom: 20px;
        }

        .doctors-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .doctors-list-item {
            margin-bottom: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            padding: 10px;
            border-radius: 5px;
        }

        .doctors-list-item:hover {
            background-color: #0056b3;
        }

        .chat-area {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #fff;
        }

        .messages_box {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            max-height: 300px;
            overflow-y: auto;
        }

        .message {
            margin-bottom: 5px;
            padding: 5px;
            border-radius: 5px;
        }

        .message b {
            font-weight: bold;
        }

        #messageInput {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 10px;
            width: calc(100% - 22px);
        }

        #submitBtn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            align-self: flex-end;
        }

        .title {
            color: #007bff;
            margin-bottom: 10px;
        }

        .sent_message {
            background-color: #007bff;
            color: #fff;
            border: 1px solid #4CAF50;
        }

        .received_message {
            background-color: #e0e0e0;
            color: #333;
            border: 1px solid #87CEEB;
        }

        header {
            text-align: center;
            color: transparent;
        }

        h1 {
            font-size: 36px;
            animation: fadeInAndColor 3s forwards;
        }

        @keyframes fadeInAndColor {
            0% {
                color: transparent;
            }

            100% {
                color: #007bff;
            }
        }
    </style>
</head>
<body>
<header><h1 id="headerText">Commencez la discussion avec votre médecin !</h1></header>
<div class="container">
<div class="sidebar" id="sidebar">
        <h2>Liste des Médecins</h2>
        <ul class="doctors-list">
            <?php foreach ($doctors_names as $doctor_id => $doctor_username): ?>
                <li class="doctors-list-item" onclick="openChat(<?= $doctor_id ?>)"><?= $doctor_username ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="chat-area" id="chatArea" style="display: none;">
        <!-- Informations de l'utilisateur et bouton de déconnexion -->
        <div class="button-email">
            <span><?= $user_username ?></span>
            <a href="/simple php system/php/logout.php" class="Deconnexion_btn">Déconnexion</a>
        </div>
        <!-- Boîte des médecins -->
        <div class="doctors_box">
            <h1 class="title">Messages</h1>
            <form id="messageForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <select id="selectedDoctor" name="selected_doctor">
                    <option value="">Sélectionnez un médecin</option>
                    <?php foreach ($doctors_names as $doctor_id => $doctor_username): ?>
                        <option value="<?= $doctor_id ?>"><?= $doctor_username ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="doctors_messages">
                    <?php foreach ($doctors_names as $doctor_id => $doctor_username): ?>
                        <div class="messages_box" id="messagesBox<?= $doctor_id ?>" style="display: none;">
                            <?php
                            // Récupérer les messages pour ce médecin uniquement
                            $doctor_messages_query = mysqli_query($con, "SELECT messages.*, users.Username AS sender_name 
                                                              FROM messages 
                                                              LEFT JOIN users ON messages.sender_id = users.Id 
                                                              WHERE messages.receiver_id = '$doctor_id'
                                                              ORDER BY messages.timestamp DESC");
                            // Afficher les messages pour ce médecin
                            if (mysqli_num_rows($doctor_messages_query) > 0) {
                                while ($message_row = mysqli_fetch_assoc($doctor_messages_query)) {
                                    $message_class = ($message_row['sender_id'] == $user_id) ? 'sent_message' : 'received_message';
                                    echo "<div class='message $message_class'>
                                <b>{$message_row['sender_name']} :</b> {$message_row['message']}
                              </div>";
                                }
                            } else {
                                echo "Aucun message trouvé.";
                            }
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="text" id="messageInput" name="message" placeholder="Votre message" style="display: none;">
                <input type="submit" id="submitBtn" value="Envoyer" style="display: none;">
            </form>
        </div>
    </div>

</div>
<script>
    function openChat(doctorId) {
        const chatArea = document.getElementById('chatArea');
        const selectedDoctor = document.getElementById('selectedDoctor');
        const messageInput = document.getElementById('messageInput');
        const submitBtn = document.getElementById('submitBtn');

        selectedDoctor.value = doctorId;
        const selectedBox = document.getElementById('messagesBox' + doctorId);
        const messagesBoxes = document.querySelectorAll('.messages_box');
        messagesBoxes.forEach(function(box) {
            box.style.display = 'none';
        });
        selectedBox.style.display = 'block';
        messageInput.style.display = 'block';
        submitBtn.style.display = 'block';

        chatArea.style.display = 'block';
    }

    // Rediriger l'utilisateur vers la même page après l'envoi d'un message
    function redirectAfterMessage() {
        setTimeout(function() {
            window.location.href = window.location.href; // Recharger la même page
        }, 1000); // Délai en millisecondes avant la redirection (1000 ms = 1 seconde)
    }
</script>
</body>
</html>
