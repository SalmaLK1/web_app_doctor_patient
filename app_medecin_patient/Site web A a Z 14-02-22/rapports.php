<?php
session_start();

include("simple php system/php/config.php");
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
    $res_speciality = $result['Speciality'];
    $res_id = $result['Id'];
    $profile_image = $result['ProfileImage'];
    // Afficher les informations du médecin
} else {
    // Gérer le cas où aucun utilisateur n'est trouvé avec cet ID
    // Par exemple, rediriger l'utilisateur vers une page d'erreur ou afficher un message d'erreur
}

// ...

// Récupérer les patients associés à ce médecin
$patients = [];
$sql_patients = "SELECT Id,Nom ,Prenom
                FROM rendez_vous 
                
                WHERE rendez_vous.MedecinId = $id";

$result_patients = $con->query($sql_patients);

// Vérifier s'il y a des résultats
if ($result_patients->num_rows > 0) {
    // Parcourir les résultats et les stocker dans un tableau
    while($row = $result_patients->fetch_assoc()) {
        $patients[] = $row;
    }
} else {
    // Aucun patient trouvé
}

// Traitement du formulaire de nouveau rapport
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification des données envoyées
    if (isset($_POST['rendezVousId']) && isset($_POST['rapport'])) {
        // Récupérer les données du formulaire
        $rendezVousId = $_POST['rendezVousId'];
        $rapport = $_POST['rapport'];

        // Traitement du rapport (enregistrement dans la base de données, etc.)
        // Ici, vous pouvez ajouter le code pour enregistrer le rapport dans la base de données
        // par exemple : INSERT INTO rapport (RendezVousId, RapportText) VALUES ('$rendezVousId', '$rapport')
        
        // Après le traitement, vous pouvez rediriger l'utilisateur vers une autre page si nécessaire
        // header("Location: autre_page.php");
        // exit; // Assurez-vous de terminer le script après la redirection
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Votre style CSS ici */
        /* Votre CSS pour la page "rapport.php" */

     /* Votre CSS pour la page "rapport.php" */

body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f5f5f5;
}

.header {
    background-color: #007bff;
    color: white;
    padding: 20px;
    text-align: center;
}

.main {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: center;
    margin-bottom: 20px;
    color: #007bff;
}

h2 {
    margin-bottom: 15px;
    color: #007bff;
}

section {
    margin-bottom: 30px;
}

label {
    display: block;
    margin-bottom: 5px;
}

select,
textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: none;
}

button {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

ul {
    list-style-type: none;
    padding: 0;
}

ul li {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

ul li a {
    margin-right: 10px;
    text-decoration: none;
    color: #007bff;
    transition: color 0.3s;
}

ul li a:hover {
    color: #0056b3;
}

        /* Optionnel : Ajoutez du style supplémentaire selon vos préférences */

    </style>
</head>
<body>
<header class="header">
    <a href="/simple php system/home.php"><button class="btn"><i class="fas fa-home home-icon"></i>Accueil</button></a>
    <h1>Rapport</h1>
</header>
<main class="main">
    <section>
        <h2>Nouveau Rapport</h2>
        <!-- Formulaire pour écrire un nouveau rapport -->
        <form action="save_report.php" method="post">
            <label for="rendezVousId">Patient :</label>
            <select id="rendezVousId" name="rendezVousId" required>
                <?php foreach ($patients as $patient) : ?>
                    <option value="<?php echo $patient["Id"]; ?>"><?php echo $patient["Nom"] . ' ' . $patient["Prenom"]; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="rapport">Rapport Médical / Ordonnance :</label>
            <textarea id="rapport" name="rapport" rows="10" required></textarea>
            <button type="submit">Enregistrer</button>
        </form>
    </section>
    <section>
    <h2>Rapports existants</h2>
    <!-- Affichage des rapports existants -->
    <?php 
    // Requête SQL pour récupérer les rapports existants pour ce médecin
    $sql_rapports = "SELECT RapportText FROM rapport WHERE RendezVousId IN (SELECT Id FROM rendez_vous WHERE MedecinId = $id)";
    $result_rapports = $con->query($sql_rapports);

    if ($result_rapports->num_rows > 0) : ?>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($result_rapports)) : ?>
                <li><?php echo $row["RapportText"]; ?></li>
            <?php endwhile; ?>
        </ul>
    <?php else : ?>
        <p>Aucun rapport trouvé.</p>
    <?php endif; ?>
    <!-- Ajoutez ceci à l'intérieur de la boucle while qui affiche les rapports existants -->
<li>

    <!-- Bouton Modifier -->
    <a href="edit_report.php?report_id=<?php echo $row['Id']; ?>">Modifier</a>
    <!-- Bouton Supprimer -->
    <a href="delete_report.php?report_id=<?php echo $row['Id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?')">Supprimer</a>
</li>

</section>

</main>
</body>
</html>
