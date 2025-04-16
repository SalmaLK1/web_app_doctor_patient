<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Le serveur prend en charge les requêtes POST.";
} else {
    echo "Le serveur ne prend pas en charge les requêtes POST.";
}
?>
