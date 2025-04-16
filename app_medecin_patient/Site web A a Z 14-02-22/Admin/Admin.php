<?php

include('config.php');
$sql = "SELECT COUNT(*) AS total_patients FROM Users WHERE Role = 'patient'";
$result = $con->query($sql);

$total_patients = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_patients = $row['total_patients'];
}
$sql = "SELECT COUNT(*) AS total_medecins FROM Users WHERE Role = 'medecin'";
$result = $con->query($sql);

$total_medecins = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_medecins = $row['total_medecins'];
}
$sql = "SELECT COUNT(*) AS total_admin FROM Users WHERE Role = 'admin'";
$result = $con->query($sql);

$total_admin = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_admin = $row['total_admin'];
}

// Fermeture de la connexion
$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Espace admin</title>
  <style>
    /* Reset des styles par défaut */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
}

header {
  background-color: #333;
  color: #fff;
  padding: 20px;
  text-align: center;
}

nav ul {
  list-style: none;
}

nav ul li {
  display: inline;
  margin-right: 20px;
}

nav ul li a {
  color: #fff;
  text-decoration: none;
}

.container {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  padding: 20px;
}

.section {
  background-color: #fff;
  border-radius: 5px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin: 20px;
  width: 250px;
  text-align: center;
}

.count {
  font-size: 36px;
  font-weight: bold;
  color: #333;
}

.title {
  font-size: 24px;
  margin-top: 10px;
}

.description {
  color: #666;
  margin-top: 10px;
}

.btn {
  background-color: #333;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  margin-top: 20px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.btn:hover {
  background-color: #555;
}

  </style>
 </head>
<body>
  <header>
    <h1>Espace admin</h1>
    <nav>
      <ul>
        <li><a href="#">Admin</a></li>
        <li><a href="patientadmin.php">Patients</a></li>
        <li><a href="doctoradmin.php">Professionnels de la santé</a></li>
        <li><a href="#">Rendez-vous</a></li>
        <li><a href="#">Statistiques</a></li>
      </ul>
    </nav>
  </header>
  <main>
    <div class="container">
      <section class="section">
        <div class="count"><?php echo $total_admin; ?></div>
        <div class="title">Admin</div>
        <div class="description">Gérer les comptes administrateurs</div>
        <button class="btn" onclick="window.location.href='rechercheadmin.php'">Accéder</button>
      </section>

      <section class="section">
        <div class="count"><?php echo $total_patients; ?></div>
        <div class="title">Patients</div>
        <div class="description">Gérer les comptes des patients</div>
        <button class="btn" onclick="window.location.href='patientadmin.php'">Accéder</button>
      </section>

      </div>
  </main>
</body>
</html>
