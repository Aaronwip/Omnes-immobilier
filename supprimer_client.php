<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: votrecompte.php");
    exit();
}

// Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Vérifier que l'utilisateur est bien un administrateur
$id_admin = $_SESSION['id_user'];
$verif = $mysqli->query("SELECT role FROM users WHERE id_user = $id_admin");
$admin = $verif->fetch_assoc();

if (!$admin || $admin['role'] !== 'admin') {
    die("Accès non autorisé.");
}

// Vérifie que l'ID est présent
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID invalide.");
}

$id_client = intval($_GET['id']);

// Vérifie que le client existe et est bien un client
$res = $mysqli->query("SELECT * FROM users WHERE id_user = $id_client AND role = 'client'");
if ($res->num_rows !== 1) {
    die("Client introuvable ou invalide.");
}

// Suppression
$mysqli->query("DELETE FROM users WHERE id_user = $id_client");

// Redirection vers la page de gestion
header("Location: gestion_clients.php");
exit();
?>
