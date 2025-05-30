<?php
session_start();

// Vérification de la connexion et des droits
if (!isset($_SESSION['id_user'])) {
    header("Location: votrecompte.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

$id = $_SESSION['id_user'];
$result = $mysqli->query("SELECT role FROM users WHERE id_user = $id");
$user = $result->fetch_assoc();

if (!$user || $user['role'] !== 'admin') {
    die("Accès réservé à l'administrateur.");
}

// Vérifie que l’ID du bien est bien transmis
if (isset($_GET['id'])) {
    $id_bien = (int) $_GET['id'];

    // Supprimer le bien
    $stmt = $mysqli->prepare("DELETE FROM biens WHERE id_bien = ?");
    $stmt->bind_param("i", $id_bien);
    $stmt->execute();
    $stmt->close();
}

$mysqli->close();

// Redirection
header("Location: gestion_biens.php");
exit();
?>
