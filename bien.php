<?php
session_start();

$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Vérifie l'ID passé en URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de bien invalide.");
}

$id_bien = (int)$_GET['id'];

// Requête : récupère les infos du bien + infos agent
$stmt = $mysqli->prepare("
    SELECT b.*, b.vendu, a.prenom, a.nom AS nom_agent, a.email, a.telephone, a.photo AS photo_agent 
    FROM biens b 
    LEFT JOIN agents a ON b.agent_id = a.id_agent 
    WHERE b.id_bien = ?
");
$stmt->bind_param("i", $id_bien);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Bien introuvable.");
}

$bien = $result->fetch_assoc();
$stmt->close();

// Connexion utilisateur
$isLoggedIn = isset($_SESSION['id_user']);
$userId = $isLoggedIn ? $_SESSION['id_user'] : null;

// Vérifie si bien est vendu
$isSold = ($bien['vendu'] == 1);

// Vérifie si l'utilisateur a enregistré une carte
$hasCard = false;
if ($isLoggedIn) {
    $stmtCard = $mysqli->prepare("SELECT * FROM infos_bancaires WHERE id_user = ?");
    $stmtCard->bind_param("i", $userId);
    $stmtCard->execute();
    $resCard = $stmtCard->get_result();
    $cardData = $resCard->fetch_assoc();
    $hasCard = $cardData && !empty($cardData['numero_carte']) && !empty($cardData['date_expiration']) && !empty($cardData['code_cvc']);
    $stmtCard->close();
}


$mysqli->close();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($bien['categorie']) ?> - Détails</title>
    <link rel="stylesheet" href="style-header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f5f5;
        }

        .wrapper {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }

        .bien-photo {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .infos {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .actions {
            margin-top: 30px;
            display: flex;
            gap: 20px;
        }

        .actions a {
            padding: 10px 20px;
            background: #0B3D91;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        .agent {
            margin-top: 40px;
            background: #eee;
            padding: 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .agent-info {
            flex: 1;
        }

        .agent-photo {
            height: 100px;
            border-radius: 6px;
            margin-left: 20px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="wrapper">
    <h1><?= htmlspecialchars($bien['categorie']) ?> - <?= number_format($bien['prix'], 0, ',', ' ') ?> €</h1>
    <img src="<?= htmlspecialchars($bien['photo']) ?>" class="bien-photo" alt="Photo du bien">

    <div class="infos">
        <p><strong>Adresse :</strong> <?= htmlspecialchars($bien['adresse']) ?></p>
        <p><strong>Surface :</strong> <?= $bien['surface'] ?> m²</p>
        <p><strong>Type :</strong> <?= htmlspecialchars($bien['categorie']) ?></p>
        <p><strong>Nombre de pièces :</strong> <?= $bien['pieces'] ?></p>
        <p><strong>Nombre de chambres :</strong> <?= $bien['chambres'] ?></p>
    </div>

   <div class="actions">
        <a href="rendezvous.php?agent=<?= $bien['agent_id'] ?>">Prendre rendez-vous</a>
        <a href="faire_offre.php?bien=<?= $bien['id_bien'] ?>">Faire une offre</a>

        <?php if ($isSold): ?>
            <span style="color: red; font-weight: bold;">Ce bien a déjà été vendu.</span>
        <?php elseif ($isLoggedIn): ?>
            <a href="<?= $hasCard ? 'paiement.php?id=' . $bien['id_bien'] : 'infos_bancaires.php?redirect=paiement&id=' . $bien['id_bien'] ?>">Payer ce bien</a>
        <?php else: ?>
            <a href="votrecompte.php">Connectez-vous pour acheter</a>
        <?php endif; ?>
    </div>

    <div class="agent">
        <?php if ($bien['nom_agent']): ?>
            <div class="agent-info">
                <h2>Agent en charge</h2>
                <p><strong><?= htmlspecialchars($bien['prenom']) . ' ' . htmlspecialchars($bien['nom_agent']) ?></strong></p>
                <p>Email : <?= htmlspecialchars($bien['email']) ?></p>
                <p>Téléphone : <?= htmlspecialchars($bien['telephone']) ?></p>
            </div>
            <img src="<?= htmlspecialchars($bien['photo_agent']) ?>" alt="Photo agent" class="agent-photo">
        <?php else: ?>
            <p>Aucun agent assigné pour le moment.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
