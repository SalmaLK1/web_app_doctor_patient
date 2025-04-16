<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Patient</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
           .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    h1, h2 {
        text-align: center;
        font-weight: 700;
        color: #007bff;
    }

    form {
        text-align: center;
        margin-bottom: 30px;
    }
    body{
        margin:100px auto;
    }

    input[type="text"] {
        padding: 12px;
        width: 60%;
        border-radius: 25px;
        border: 1px solid #ccc;
        margin-right: 10px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    input[type="text"]:focus {
        outline: none;
        border-color: #007bff;
    }

    button{
        padding: 12px 24px;
        border: none;
        border-radius: 25px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    pre {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
        overflow-x: auto;
        font-family: 'Roboto Mono', monospace;
        font-size: 14px;
    }

    p {
        text-align: center;
        font-style: italic;
        color: #666;
    }

    .edit-link {
        display: block;
        text-align: center;
        color: #007bff;
        text-decoration: none;
        margin-top: 10px;
        transition: color 0.3s ease;
    }

    .edit-link:hover {
        color: #0056b3;
    }

    #editFormContainer {
        margin-top: 20px;
    }

    #editFormContainer form {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    #editFormContainer textarea {
        width: 100%;
        height: 200px;
        font-family: 'Roboto Mono', monospace;
        font-size: 14px;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        resize: vertical;
    }

    #editFormContainer button {
        display: block;
        margin: 10px auto 0;
        padding: 10px 20px;
        border: none;
        border-radius: 25px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    #editFormContainer button:hover {
        background-color: #0056b3;
    }
</style>
    
</head>
<body>
<header>
    <a href="rapports.php"> <button class="btn"><i class="fas fa-home home-icon"></i>accueil</button> </a>
    <h1>Recherche de Patient</h1>
    </header>
    <div class="container">
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <input type="text" id="patient_name" name="patient_name" placeholder="Nom du patient">
            <button type="submit">Rechercher</button>
        </form>

        <?php
        if (isset($_GET['patient_name'])) {
            $patient_name = $_GET['patient_name'];
            $filename = "reports/" . $patient_name . ".txt";

            if (file_exists($filename)) {
                $report_content = file_get_contents($filename);
                ?>
                <h2>Rapport de <?php echo $patient_name; ?></h2>
                <pre id="reportContent"><?php echo htmlspecialchars($report_content); ?></pre>
                <a href="#" class="edit-link" data-patient-name="<?php echo $patient_name; ?>">Modifier</a>
                <div id="editFormContainer" style="display: none;"></div>
                <button id="printButton">Imprimer</button>

                <?php
            } else {
                echo "<p>Aucun rapport trouvé pour le patient $patient_name.</p>";
            }
        }
        ?>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var editFormContainer = document.getElementById("editFormContainer");

            var editLinks = document.querySelectorAll(".edit-link");
            editLinks.forEach(function(link) {
                link.addEventListener("click", function(event) {
                    event.preventDefault();

                    var patientName = this.getAttribute("data-patient-name");
                    var reportContent = document.getElementById("reportContent").textContent;

                    editFormContainer.innerHTML = '';
                    editFormContainer.style.display = 'block';
                    displayEditForm(patientName, reportContent);
                });
            });
        });

        function displayEditForm(patientName, reportContent) {
            var form = document.createElement("form");
            form.addEventListener("submit", function(event) {
                event.preventDefault(); // Empêcher la soumission par défaut du formulaire
                var updatedReport = this.elements["report"].value;
                // Mettre à jour le contenu du rapport sans recharger la page
                document.getElementById("reportContent").textContent = updatedReport;
                // Vous pouvez ajouter ici la logique pour enregistrer le rapport côté serveur
                // Par exemple, en utilisant AJAX pour envoyer les données au serveur
                // et mettre à jour le fichier correspondant.
                editFormContainer.style.display = 'none'; // Cacher le formulaire d'édition
            });

            var patientNameInput = document.createElement("input");
            patientNameInput.setAttribute("type", "hidden");
            patientNameInput.setAttribute("name", "patient_name");
            patientNameInput.setAttribute("value", patientName);
            form.appendChild(patientNameInput);

            var reportTextarea = document.createElement("textarea");
            reportTextarea.setAttribute("name", "report");
            reportTextarea.setAttribute("rows", "10");
            reportTextarea.textContent = reportContent;
            form.appendChild(reportTextarea);

            var submitButton = document.createElement("button");
            submitButton.setAttribute("type", "submit");
            submitButton.textContent = "Enregistrer";
            form.appendChild(submitButton);

            var cancelButton = document.createElement("button");
            cancelButton.setAttribute("type", "button");
            cancelButton.textContent = "Annuler";
            cancelButton.addEventListener("click", function() {
                editFormContainer.style.display = 'none'; // Cacher le formulaire d'édition
            });
            form.appendChild(cancelButton);

            editFormContainer.appendChild(form);
        }
        document.addEventListener("DOMContentLoaded", function() {
            // Votre code JavaScript existant

            // Ajout de l'événement click pour le bouton d'impression
            var printButton = document.getElementById("printButton");
            if (printButton) {
                printButton.addEventListener("click", function() {
                    printReportContent();
                });
            }
        });
        function printReportContent() {
            // Sélectionner le contenu du rapport à imprimer
            var reportContent = document.getElementById("reportContent").innerHTML;

            // Créer une fenêtre d'impression
            var printWindow = window.open('', '_blank');

            // Écrire le contenu du rapport dans la fenêtre d'impression
            printWindow.document.write('<html><head><title>Rapport Médical</title></head><body>');
            printWindow.document.write(reportContent);
            printWindow.document.write('</body></html>');

            // Imprimer la fenêtre
            printWindow.print();
            printWindow.close();
        }
    </script>
</body>
</html>
