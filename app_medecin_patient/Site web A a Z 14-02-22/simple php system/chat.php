<?php
session_start();
include("php/config.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit(); // Arrêter l'exécution du script
}

// Récupérer les informations de l'utilisateur à partir de la session
$user_id = $_SESSION['id'];
$user_username = isset($_SESSION['Username']) ? $_SESSION['Username'] : '';
$user_role = isset($_SESSION['Role']) ? $_SESSION['Role'] : '';
$selected_patient_id = isset($_POST['selected_patient']) ? $_POST['selected_patient'] : '';

// Traiter l'envoi d'un nouveau message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
    $message = $_POST['message'];
    $selected_patient_id = isset($_POST['selected_patient']) ? $_POST['selected_patient'] : '';

    // Récupérer le nom de l'utilisateur connecté depuis la session
    $sender_name = isset($_SESSION['Username']) ? $_SESSION['Username'] : '';

    // Vérifier si le message n'est pas vide et si le patient est sélectionné
    if (!empty($message) && !empty($selected_patient_id)) {
        // Insérer le nouveau message dans la base de données avec le patient sélectionné comme récepteur
        $insert_query = "INSERT INTO messages (sender_name, sender_id, receiver_id, message, timestamp) VALUES ('$sender_name', '$user_id', '$selected_patient_id', '$message', NOW())";
        $insert_result = mysqli_query($con, $insert_query);
        if (!$insert_result) {
            echo "Erreur lors de l'insertion du message : " . mysqli_error($con);
        } else {
            // Redirection vers la même page pour éviter la duplication des messages
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        echo "Veuillez saisir un message et sélectionner un patient.";
    }
}

// Récupérer la liste des noms des patients avec lesquels le médecin a un rendez-vous depuis la base de données
$patients_query = mysqli_query($con, "SELECT rendez_vous.Id AS rendez_vous_id, Nom, Prenom 
                                      FROM rendez_vous 
                                      INNER JOIN users ON rendez_vous.medecinId = users.Id 
                                      WHERE rendez_vous.medecinId = '$user_id'");

// Initialiser un tableau pour stocker les noms des patients
$patients_names = array();

// Si la requête s'est bien exécutée et qu'il y a des patients
if ($patients_query && mysqli_num_rows($patients_query) > 0) {
    // Parcourir les résultats de la requête
    while ($patient_row = mysqli_fetch_assoc($patients_query)) {
        // Construire le nom complet du patient
        $patient_name = $patient_row['Nom'] . ' ' . $patient_row['Prenom'];
        // Ajouter le nom du patient au tableau avec son ID comme clé
        $patients_names[$patient_row['rendez_vous_id']] = $patient_name;
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
    <link rel="stylesheet" href="style.css">
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

.chat-area {
    flex: 1;
    padding: 20px;
    overflow-y: auto; 
    background-color: #fff; 
}

.sidebar h2 {
    margin-bottom: 20px;
}

.patients-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.patients-list-item {
    margin-bottom: 10px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    padding: 10px;
    border-radius: 5px;
}

.patients-list-item:hover {
    background-color: #0056b3; 
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

.subtitle {
    color: #333;
    margin-bottom: 20px;
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
<header><h1 id="headerText">Commencez la discussion avec vos patients !</h1></header>
<div class="container">
    
<div class="sidebar" id="sidebar">
        <h2>Liste des Patients</h2>
        <ul class="patients-list">
            <?php foreach ($patients_names as $patient_id => $patient_name): ?>
                <li class="patients-list-item" onclick="openChat(<?= $patient_id ?>)"><?= $patient_name ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
   
    <div class="chat-area" id="chatArea" style="display: none;">
        <!-- Informations de l'utilisateur et bouton de déconnexion -->
        <div class="button-email">
            <span><?= $user_username ?></span>
            <a href="/simple php system/php/logout.php" class="Deconnexion_btn">Déconnexion</a>
        </div>
        <!-- Boîte des patients -->
        <div class="patients_box">
            <h1 class="title">Messages</h1>
            <form id="messageForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <select id="selectedPatient" name="selected_patient">
                    <option value="">Sélectionnez un patient</option>
                    <?php foreach ($patients_names as $patient_id => $patient_name): ?>
                        <option value="<?= $patient_id ?>"><?= $patient_name ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="patients_messages">
                    <?php foreach ($patients_names as $patient_id => $patient_name): ?>
                        <div class="messages_box" id="messagesBox<?= $patient_id ?>" style="display: none;">
                            <?php
                            // Récupérer les messages pour ce patient uniquement
                            $patient_messages_query = mysqli_query($con, "SELECT messages.*, users.Username AS sender_name 
                                                              FROM messages 
                                                              LEFT JOIN users ON messages.sender_id = users.Id 
                                                              WHERE messages.receiver_id = '$patient_id'
                                                              ORDER BY messages.timestamp DESC");
                            // Afficher les messages pour ce patient
                            if (mysqli_num_rows($patient_messages_query) > 0) {
                                while ($message_row = mysqli_fetch_assoc($patient_messages_query)) {
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
    function openChat(patientId) {
        const chatArea = document.getElementById('chatArea');
        const selectedPatient = document.getElementById('selectedPatient');
        const messageInput = document.getElementById('messageInput');
        const submitBtn = document.getElementById('submitBtn');

        selectedPatient.value = patientId;
        const selectedBox = document.getElementById('messagesBox' + patientId);
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
    document.addEventListener('DOMContentLoaded', function() {
    const headerText = document.getElementById('headerText');
    const text = headerText.innerText; // Récupérer le texte

    let index = 0;
    setInterval(function() {
        // Ajouter une lettre à chaque intervalle
        headerText.textContent = text.slice(0, index);
        index++;
        // Arrêter l'animation lorsque tout le texte est affiché
        if (index > text.length) {
            clearInterval();
        }
    }, 100); // Intervalle de temps entre chaque ajout de lettre (en millisecondes)
});

</script>
</body>
</html>
