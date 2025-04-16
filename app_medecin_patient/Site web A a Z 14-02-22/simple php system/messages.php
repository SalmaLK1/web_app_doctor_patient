<?php 


include("../simple php system/php/config.php");

// Requête pour récupérer les messages, triés par ordre décroissant
$query = mysqli_query($con, "SELECT * FROM messages ORDER BY message_id DESC");

// Vérifier s'il y a des messages
if(mysqli_num_rows($query) > 0) {
    // Afficher chaque message sous forme d'HTML
    while($row = mysqli_fetch_assoc($query)) {
        // Vous pouvez personnaliser la mise en forme selon vos besoins
        echo '<div class="message">';
        echo '<span>' . $row['sender_name'] . '</span>';
        echo '<p>' . $row['message'] . '</p>';
        echo '<p class="date">' . $row['timestamp'] . '</p>';
        echo '</div>';
    }
} else {
    // Si aucun message n'est trouvé
    echo "Aucun message trouvé.";
}
?>
