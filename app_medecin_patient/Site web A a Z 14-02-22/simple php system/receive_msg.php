<?php
session_start();
include("php/config.php");

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    $query = "SELECT * FROM messages WHERE (sender_id = '{$_SESSION['id']}' AND receiver_id = '$patient_id') OR (sender_id = '$patient_id' AND receiver_id = '{$_SESSION['id']}') ORDER BY timestamp ASC";

    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $sender_name = $row['sender_name'];
        $message = $row['message'];
        echo "<div><strong>{$sender_name} :</strong> {$message}</div>";
    }
}
?>
