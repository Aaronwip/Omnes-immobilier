<?php
session_start();

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

// Requête avec jointure pour inclure le nom de l'agent
$biens = $mysqli->query("
    SELECT b.*, a.prenom AS agent_prenom, a.nom AS agent_nom
    FROM biens b
    LEFT JOIN agents a ON b.agent_id = a.id_agent
");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des biens</title>
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

        .btn-supprimer {
            padding: 6px 10px;
            border: none;
            cursor: pointer;
            font-weight: bold;
            border-radius: 4px;
            color: white;
            background-color: #B30000;
            text-decoration: none;
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
    <h1>Gestion des biens</h1>

    <a href="ajouter_bien.php" class="btn-ajouter">Ajouter un bien</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Catégorie</th>
            <th>Surface</th>
            <th>Prix</th>
            <th>Adresse</th>
            <th>Agent responsable</th>
            <th>Action</th>
        </tr>

        <?php while ($bien = $biens->fetch_assoc()): ?>
            <tr>
                <td><?= $bien['id_bien'] ?></td>
                <td><img src="<?= htmlspecialchars($bien['photo']) ?>" class="photo" alt="Bien"></td>
                <td><?= htmlspecialchars($bien['categorie']) ?></td>
                <td><?= $bien['surface'] ?> m²</td>
                <td><?= number_format($bien['prix'], 0, ',', ' ') ?> €</td>
                <td><?= htmlspecialchars($bien['adresse']) ?></td>
                <td>
                    <?= $bien['agent_prenom'] && $bien['agent_nom']
                        ? htmlspecialchars($bien['agent_prenom'] . ' ' . $bien['agent_nom'])
                        : 'Non assigné' ?>
                </td>
                <td>
                    <a class="btn-supprimer" href="supprimer_bien.php?id=<?= $bien['id_bien'] ?>"
                       onclick="return confirm('Supprimer ce bien ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<div id="footer">
    Footer © 2025 Omnes Immobilier<br>
    +33 6 01 02 03 04<br>
    admin@omnesimmobilier.com
</div>
</body>
</html>
