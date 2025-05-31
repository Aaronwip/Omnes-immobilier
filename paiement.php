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

$id_user = $_SESSION['id_user'];

// Vérification de l'ID du bien
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID du bien invalide.");
}
$id_bien = (int)$_GET['id'];

// Récupération des informations du bien
$stmt = $mysqli->prepare("SELECT * FROM biens WHERE id_bien = ?");
$stmt->bind_param("i", $id_bien);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Bien introuvable.");
}
$bien = $result->fetch_assoc();
$stmt->close();

// Vérification des informations bancaires du client
$stmt = $mysqli->prepare("SELECT * FROM infos_bancaires WHERE id_user = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$resBancaire = $stmt->get_result();
$hasCard = $resBancaire->num_rows > 0;
$card = $hasCard ? $resBancaire->fetch_assoc() : null;
$stmt->close();

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement - <?= htmlspecialchars($bien['categorie']) ?></title>
    <link rel="stylesheet" href="style-header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f5f5f5;
        }
        .wrapper {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
        }
        .bien-photo {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }
        .recap, .paiement {
            margin-top: 20px;
        }
        .paiement {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
        }
        .btn {
            padding: 12px 20px;
            background-color: #0B3D91;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="wrapper">
    <h1>Récapitulatif du bien</h1>
    <img src="<?= htmlspecialchars($bien['photo']) ?>" class="bien-photo" alt="Photo du bien">
    <div class="recap">
        <p><strong>Adresse :</strong> <?= htmlspecialchars($bien['adresse']) ?></p>
        <p><strong>Surface :</strong> <?= $bien['surface'] ?> m²</p>
        <p><strong>Nombre de pièces :</strong> <?= $bien['pieces'] ?></p>
        <p><strong>Prix :</strong> <?= number_format($bien['prix'], 0, ',', ' ') ?> €</p>
    </div>

    <div class="paiement">
        <h2>Informations bancaires</h2>
        <?php
        $isCardValid = $hasCard &&
            !empty($card['nom_titulaire']) &&
            !empty($card['numero_carte']) &&
            !empty($card['date_expiration']);
        ?>

        <?php if ($isCardValid): ?>
            <p><strong>Nom sur la carte :</strong> <?= htmlspecialchars($card['nom_titulaire']) ?></p>
            <p><strong>Numéro :</strong> **** **** **** <?= substr($card['numero_carte'], -4) ?></p>
            <p><strong>Date d’expiration :</strong> <?= htmlspecialchars($card['date_expiration']) ?></p>

            <form method="POST" action="confirmation_paiement.php">
                <input type="hidden" name="id_bien" value="<?= $bien['id_bien'] ?>">
                <button type="submit" class="btn">Confirmer l'achat</button>
            </form>

        <?php else: ?>
            <p>Vos informations bancaires sont incomplètes ou manquantes.</p>
            <a class="btn" href="infos_bancaires.php?redirect=paiement&id=<?= $bien['id_bien'] ?>">Ajouter ou corriger mes informations bancaires</a>
        <?php endif; ?>


    </div>
</div>
</body>
</html>
