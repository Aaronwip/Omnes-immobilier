<?php
session_start();

// Redirection si non connecté ou non admin
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

// Récupération des agents avec spécialité
$agents = $mysqli->query("
    SELECT a.*, s.nom AS specialite_nom
    FROM agents a
    LEFT JOIN specialites s ON a.specialite_id = s.id_specialite
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des agents</title>
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

        img.photo {
            height: 60px;
            border-radius: 4px;
        }

        .btn-cv, .btn-supprimer {
            padding: 6px 10px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 4px;
            color: white;
            text-decoration: none;
        }

        .btn-cv {
            background-color: #0B3D91;
        }

        .btn-supprimer {
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
    <h1>Gestion des agents</h1>

    <a href="ajouter_agent.php" class="btn-ajouter">Ajouter un agent</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Téléphone</th>
            <th>Adresse mail</th>
            <th>Spécialité</th>
            <th>CV</th>
            <th>Action</th>
        </tr>

        <?php while ($agent = $agents->fetch_assoc()): ?>
            <tr>
                <td><?= $agent['id_agent'] ?></td>
                <td><img src="<?= htmlspecialchars($agent['photo']) ?>" alt="Photo" class="photo"></td>
                <td><?= htmlspecialchars($agent['prenom']) ?></td>
                <td><?= htmlspecialchars($agent['nom']) ?></td>
                <td><?= htmlspecialchars($agent['telephone']) ?></td>
                <td><?= htmlspecialchars($agent['email']) ?></td>
                <td><?= htmlspecialchars($agent['specialite_nom'] ?? 'Non définie') ?></td>
                <td>
                    <?php if (!empty($agent['cv'])): ?>
                        <a class="btn-cv" href="<?= htmlspecialchars($agent['cv']) ?>" target="_blank">CV</a>
                    <?php else: ?>
                        Aucun
                    <?php endif; ?>
                </td>
                <td>
                    <a class="btn-supprimer" href="supprimer_agent.php?id=<?= $agent['id_agent'] ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
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
