<?php 
 
 // Fermer la connexion PDO

// Paramètres de connexion
$hostname = 'localhost';
$port = 3306;
$database = 'pfe';
$username = 'root';
$password = 'salma2002';

// Connexion à la base de données avec mysqli
$con = new mysqli($hostname, $username, $password, $database, $port);

// Vérifier les erreurs de connexion
if ($con->connect_error) {
    die("Erreur de connexion : " . $con->connect_error);
}

// Vérifiez si la connexion est établie avec succès

?>