<?php
session_start();

include("simple php system/php/config.php");

// Vérifier si un utilisateur est connecté
if (!isset($_SESSION['valid'])) {
    header("Location: index.php");
    exit();
}

// Vérifier si l'ID du rapport à supprimer est passé en paramètre
if (!isset($_GET['report_id'])) {
    header("Location: rapport.php");
    exit();
}

// Récupérer l'ID du rapport à supprimer
$report_id = $_GET['report_id'];

// Supprimer le rapport de la base de données
$sql_delete = "DELETE FROM rapport WHERE Id = $report_id";
if ($con->query($sql_delete) === TRUE) {
    header("Location: rapport.php");
    exit();
} else {
    echo "Erreur lors de la suppression du rapport : " . $con->error;
}
?>
