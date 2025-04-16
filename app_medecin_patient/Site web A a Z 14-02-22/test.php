<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme de gestion de rendez-vous médicaux</title>
     
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #007bff;
        }

        .row {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
            gap: 20px;
        }

        .feature {
            flex: 0 0 calc(33.333% - 10px);
            text-align: center;
            cursor: pointer;
            transition: transform 0.3s ease;
            border: 2px solid #007bff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .feature:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            font-size: 40px;
            margin-bottom: 10px;
            color: #007bff;
        }

        .feature-text {
            font-size: 18px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 999;
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

    </style>
    <style>
       
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
       
   </style>
</head>
<body>
<div class="logo">

<a href="/index.php" class="back-link"><i class="fas fa-home home-icon"></i>Revenir à l'accueil</a>
        </div>
    <div class="container">
        <h1>Bienvenue sur notre plateforme de gestion de rendez-vous médicaux</h1>
        
        <div class="row">
            <div class="feature" onclick="openModal()">
                <div class="feature-icon"><img src="https://emojicdn.elk.sh/🕑" alt="Icone Horloge"></div>
                <div class="feature-text">
                    <h3>Annuler un rendez-vous</h3>
                    <p>Besoin d'annuler un rendez-vous prévu ? Pas de soucis ! Vous pouvez le faire en quelques clics sans tracas.</p>
                </div>  
            </div>
            
            <div class="feature" onclick="openModal()">
                <div class="feature-icon"><img src="https://emojicdn.elk.sh/🔍" alt="Icone Loupe"></div>
                <div class="feature-text">
                    <h3>Chercher une ordonnance</h3>
                    <p>Vous avez perdu votre ordonnance ? Pas de panique ! Vous pouvez la rechercher et la récupérer facilement ici.</p>
                </div>
            </div>

            <div class="feature" onclick="openModal()">
                <div class="feature-icon"><img src="https://emojicdn.elk.sh/📞" alt="Icone Téléphone"></div>
                <div class="feature-text">
                    <h3>Communiquer avec le médecin</h3>
                    <p>Besoin de contacter votre médecin pour une question urgente ? Vous pouvez le faire directement depuis notre plateforme.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="feature" onclick="openModal()">
                <div class="feature-icon"><img src="https://emojicdn.elk.sh/📅" alt="Icone Calendrier"></div>
                <div class="feature-text">
                    <h3>Prendre un rendez-vous</h3>
                    <p>Prévoyez votre prochain rendez-vous médical à l'avance ! Notre système de réservation en ligne vous rend la tâche facile.</p>
                </div>
            </div>

            <div class="feature" onclick="openModal()">
                <div class="feature-icon"><img src="https://emojicdn.elk.sh/📜" alt="Icone Parchemin"></div>
                <div class="feature-text">
                    <h3>Voir l'historique du médecin</h3>
                    <p>Consultez l'historique des consultations de votre médecin pour connaître ses disponibilités et son expertise.</p>
                </div>
            </div>

            <div class="feature" onclick="openModal()">
                <div class="feature-icon"><img src="https://emojicdn.elk.sh/📖" alt="Icone Livre"></div>
                <div class="feature-text">
                    <h3>Voir votre historique</h3>
                    <p>Consultez votre historique de rendez-vous médicaux pour suivre votre parcours de santé et vos progrès.</p>
                </div>
            </div>
        </div>
    </div>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Vous devez vous connecter pour profiter de nos services.</p>
            <button class="btn" onclick="redirectToLogin()">Connexion</button>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function redirectToLogin() {
            window.location.href = 'simple php system/index.php';
        }
    </script>
</body>
</html>
