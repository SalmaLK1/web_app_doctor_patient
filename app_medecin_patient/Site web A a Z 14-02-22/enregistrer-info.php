<?php
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $date = $_POST['date'];
    $heure = $_POST['heure'];
    $medecin_id = $_POST['medecin'];

    // Enregistre les données dans la base de données
    // Connexion à la base de données
    $servername = 'localhost';
    $username = 'root';
    $password = 'azerty';
    $dbname = 'pfe';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    // Requête d'insertion des données du rendez-vous
    $sql = "INSERT INTO rendez_vous (Nom, Prenom, DateRendezVous, HeureRendezVous, MedecinId) VALUES ('$nom', '$prenom', '$date', '$heure', '$medecin_id')";

    if ($conn->query($sql) === TRUE) {
        // Redirige vers la page de gestion des rendez-vous
        header("Location: afficher-rdvs.php");
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
