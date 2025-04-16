<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Rapport Patient</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Votre style CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 100px auto;
            padding: 0;
            background-color: #f5f5f5; /* Couleur de fond légère */
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .main {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff; /* Fond blanc pour le contenu principal */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Légère ombre */
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold; /* Texte en gras pour les étiquettes */
        }

        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none; /* Désactiver la redimension du textarea */
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

        .btn {
            margin-right: 50px;
        }
    </style>
</head>
<body>

    <header class="header">
        <a href="rapports.php"> <button class="btn"><i class="fas fa-home home-icon"></i>accueil</button> </a>
        <h1>Nouveau Rapport Patient</h1>
    </header>
    <main class="main">
        <!-- Formulaire pour ajouter un nouveau rapport -->
        <form action="save_report.php" method="post">
            <label for="patient_name">Nom du patient :</label>
            <input type="text" id="patient_name" name="patient_name" required>
            <label for="report">Rapport médical / Ordonnance :</label>
            <textarea id="report" name="report" rows="10" required></textarea>
            <button type="submit">Enregistrer</button>
        </form>
    </main>
</body>
</html>
