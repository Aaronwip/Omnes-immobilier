<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    header("Location: votrecompte.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

// Vérification du rôle admin
$id_user = $_SESSION['id_user'];
$res = $mysqli->query("SELECT role FROM users WHERE id_user = $id_user");
$row = $res->fetch_assoc();
if (!$row || $row['role'] !== 'admin') {
    die("Accès refusé.");
}

// Suppression d'une vente (si requête POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_vente'])) {
    $id_vente = (int)$_POST['id_vente'];

    // Supprimer la vente
    $mysqli->query("DELETE FROM ventes WHERE id_vente = $id_vente");
}

$result = $mysqli->query("
    SELECT v.id_vente, v.id_bien, v.id_user, v.date_vente, v.montant,
           b.adresse, b.photo
    FROM ventes v
    INNER JOIN biens b ON v.id_bien = b.id_bien
    ORDER BY v.date_vente DESC
");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des ventes</title>
    <link rel="stylesheet" href="style-header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #0B3D91;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px 12px;
            text-align: center;
        }

        th {
            background-color: #0B3D91;
            color: white;
        }

        img {
            width: 100px;
            height: auto;
            border-radius: 6px;
        }

        form {
            margin: 0;
        }

        .btn {
            padding: 8px 14px;
            background-color: red;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <h1>Historique des ventes</h1>
    <table>
        <tr>
            <th>ID Vente</th>
            <th>ID Bien</th>
            <th>ID Client</th>
            <th>Photo</th>
            <th>Adresse</th>
            <th>Prix</th>
            <th>Date de vente</th>
            <th>Action</th>
        </tr>
        <?php while ($vente = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $vente['id_vente'] ?></td>
                <td><?= $vente['id_bien'] ?></td>
                <td><?= $vente['id_user'] ?></td>
                <td><img src="<?= htmlspecialchars($vente['photo']) ?>" alt="photo bien"></td>
                <td><?= htmlspecialchars($vente['adresse']) ?></td>
                <td><?= number_format($vente['montant'], 0, ',', ' '). ' €' ?></td>
                <td><?= $vente['date_vente'] ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="id_vente" value="<?= $vente['id_vente'] ?>">
                        <button type="submit" class="btn" onclick="return confirm('Supprimer cette vente ?')">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
