<?php
session_start();

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
    header("Location: votrecompte.php");
    exit();
}

// Connexion à la BDD
$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Vérification que l'utilisateur est admin
$id_user = $_SESSION['id_user'];
$res = $mysqli->query("SELECT role FROM users WHERE id_user = $id_user");
$user = $res->fetch_assoc();

if (!$user || $user['role'] !== 'admin') {
    die("Accès refusé. Réservé à l'administrateur.");
}

// Récupération de l'ID de l'agent à supprimer
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_agent = intval($_GET['id']);

    // Suppression
    $stmt = $mysqli->prepare("DELETE FROM agents WHERE id_agent = ?");
    $stmt->bind_param("i", $id_agent);
    if ($stmt->execute()) {
        header("Location: gestion_agents.php?success=1");
    } else {
        echo "Erreur lors de la suppression.";
    }

    $stmt->close();
} else {
    echo "ID agent invalide.";
}

$mysqli->close();
?>
