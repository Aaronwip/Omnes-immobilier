<?php
session_start();

// Vérification de session
if (!isset($_SESSION['id_user'])) {
    header("Location: votrecompte.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Vérification du rôle
$id = $_SESSION['id_user'];
$result = $mysqli->query("SELECT role FROM users WHERE id_user = $id");
$user = $result->fetch_assoc();
if (!$user || $user['role'] !== 'admin') {
    die("Accès réservé à l'administrateur.");
}

// Récupération des utilisateurs de rôle "client"
$clients = $mysqli->query("SELECT * FROM users WHERE role = 'client'");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des clients</title>
    <link rel="stylesheet" href="style-header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #eee;
        }

        .wrapper {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            min-height: 100vh;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .btn-ajouter {
            display: block;
            width: fit-content;
            background-color: #0B3D91;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            margin-left: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
        }

        .btn-supprimer {
            padding: 6px 10px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 4px;
            color: white;
            text-decoration: none;
            background-color: #B30000;
        }

        #footer {
            padding: 20px;
            font-size: 14px;
            background-color: #f2f2f2;
            text-align: left;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="wrapper">
    <h1>Gestion des clients</h1>

    <!-- Lien optionnel si tu veux pouvoir ajouter un client manuellement -->
    <!-- <a href="ajouter_client.php" class="btn-ajouter">Ajouter un client</a> -->

    <table>
        <tr>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Adresse mail</th>
            <th>Action</th>
        </tr>

        <?php while ($client = $clients->fetch_assoc()): ?>
            <tr>
                <td><?= $client['id_user'] ?></td>
                <td><?= htmlspecialchars($client['prenom']) ?></td>
                <td><?= htmlspecialchars($client['nom']) ?></td>
                <td><?= htmlspecialchars($client['email']) ?></td>
                <td>
                    <a class="btn-supprimer" href="supprimer_client.php?id=<?= $client['id_user'] ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<div id="footer">
    Footer copyright 2025 xxx<br>
    +33 6 01 02 03 04<br>
    agent@omnesimmobilier.com
</div>
</body>
</html>
