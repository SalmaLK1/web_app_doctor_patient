<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        font-weight: 700;
        color: #007bff;
        margin-bottom: 30px;
    }

    .prescription-container {
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .prescription-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .prescription-header h2 {
        font-weight: 600;
        color: #007bff;
        margin: 0;
    }

    .prescription-header button {
        padding: 8px 16px;
        border: none;
        border-radius: 25px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    .prescription-header button:hover {
        background-color: #0056b3;
    }

    .prescription-details {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-gap: 20px;
    }

    .prescription-details label {
        font-weight: 600;
        color: #007bff;
    }

    .prescription-details input,
    .prescription-details textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-family: 'Montserrat', sans-serif;
        font-size: 14px;
    }

    .prescription-details textarea {
        resize: vertical;
    }

    #add-prescription-btn {
        display: block;
        margin: 20px auto 0;
        padding: 12px 24px;
        border: none;
        border-radius: 25px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    #add-prescription-btn:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
<div class="container"> <h1>Ordonnance Médicale</h1>
    <div class="prescription-container">
        <div class="prescription-header">
            <h2>Prescription 1</h2>
            <button class="delete-prescription-btn">Supprimer</button>
        </div>
        <div class="prescription-details">
            <div>
                <label for="medication-1">Médicament :</label>
                <input type="text" id="medication-1" name="medication" value="Paracétamol">
            </div>
            <div>
                <label for="dosage-1">Dosage :</label>
                <input type="text" id="dosage-1" name="dosage" value="500 mg">
            </div>
            <div>
                <label for="frequency-1">Fréquence :</label>
                <input type="text" id="frequency-1" name="frequency" value="3 fois par jour">
            </div>
            <div>
                <label for="instructions-1">Instructions :</label>
                <textarea id="instructions-1" name="instructions">Prendre avec un repas.</textarea>
            </div>
        </div>
    </div>

    <button id="add-prescription-btn">Ajouter une nouvelle prescription</button>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var prescriptionCounter = 1;
        var addPrescriptionBtn = document.getElementById("add-prescription-btn");

        addPrescriptionBtn.addEventListener("click", function() {
            prescriptionCounter++;
            var newPrescriptionContainer = createPrescriptionContainer(prescriptionCounter);
            document.querySelector(".container").appendChild(newPrescriptionContainer);

            var deleteBtns = document.querySelectorAll(".delete-prescription-btn");
            deleteBtns.forEach(function(btn) {
                btn.addEventListener("click", function() {
                    this.parentElement.parentElement.remove();
                });
            });
        });

        function createPrescriptionContainer(prescriptionNumber) {
            var prescriptionContainer = document.createElement("div");
            prescriptionContainer.classList.add("prescription-container");

            var prescriptionHeader = document.createElement("div");
            prescriptionHeader.classList.add("prescription-header");

            var prescriptionTitle = document.createElement("h2");
            prescriptionTitle.textContent = "Prescription " + prescriptionNumber;

            var deleteBtn = document.createElement("button");
            deleteBtn.classList.add("delete-prescription-btn");
            deleteBtn.textContent = "Supprimer";

            prescriptionHeader.appendChild(prescriptionTitle);
            prescriptionHeader.appendChild(deleteBtn);

            var prescriptionDetails = document.createElement("div");
            prescriptionDetails.classList.add("prescription-details");

            var medicationInput = createInputField("Médicament :", "medication-" + prescriptionNumber, "medication");
            var dosageInput = createInputField("Dosage :", "dosage-" + prescriptionNumber, "dosage");
            var frequencyInput = createInputField("Fréquence :", "frequency-" + prescriptionNumber, "frequency");
            var instructionsInput = createTextareaField("Instructions :", "instructions-" + prescriptionNumber, "instructions");

            prescriptionDetails.appendChild(medicationInput);
            prescriptionDetails.appendChild(dosageInput);
            prescriptionDetails.appendChild(frequencyInput);
            prescriptionDetails.appendChild(instructionsInput);

            prescriptionContainer.appendChild(prescriptionHeader);
            prescriptionContainer.appendChild(prescriptionDetails);

            return prescriptionContainer;
        }

        function createInputField(label, id, name) {
            var inputContainer = document.createElement("div");
            var labelElement = document.createElement("label");
            labelElement.textContent = label;
            labelElement.setAttribute("for", id);

            var inputElement = document.createElement("input");
            inputElement.setAttribute("type", "text");
            inputElement.setAttribute("id", id);
            inputElement.setAttribute("name", name);

            inputContainer.appendChild(labelElement);
            inputContainer.appendChild(inputElement);
            return inputContainer;
        }

        function createTextareaField(label, id, name) {
            var textareaContainer = document.createElement("div");
            var labelElement = document.createElement("label");
            labelElement.textContent = label;
            labelElement.setAttribute("for", id);

            var textareaElement = document.createElement("textarea");
            textareaElement.setAttribute("id", id);
            textareaElement.setAttribute("name", name);
            textareaElement.setAttribute("rows", "3");

            textareaContainer.appendChild(labelElement);
            textareaContainer.appendChild(textareaElement);
            return textareaContainer;
        }
    });
</script>
</body>
</html>