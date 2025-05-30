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
$result = $mysqli->query("SELECT * FROM users WHERE id_user = $id");

if (!$result || $result->num_rows != 1) {
    die("Utilisateur introuvable.");
}

$user = $result->fetch_assoc();

$agent = null;
$photoAgent = null;
$specialite = "";

if ($user['role'] === 'agent') {
    $email = $mysqli->real_escape_string($user['email']);
    $agentResult = $mysqli->query("
        SELECT a.*, s.nom AS specialite_nom
        FROM agents a
        LEFT JOIN specialites s ON a.specialite_id = s.id_specialite
        WHERE a.email = '$email'
    ");
    if ($agentResult && $agentResult->num_rows == 1) {
        $agent = $agentResult->fetch_assoc();
        $photoAgent = $agent['photo'];
        $specialite = $agent['specialite_nom'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="style-header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white;
            margin: 0;
        }

        .wrapper {
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content {
            display: flex;
            flex-direction: row;
            background: white;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            box-sizing: border-box;
        }

        .colonne-gauche {
            width: 600px;
            padding-right: 20px;
            box-sizing: border-box;
        }

        .colonne-droite {
            width: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-left: 1px solid #ccc;
            padding-left: 20px;
            box-sizing: border-box;
        }

        .colonne-droite img.agent-photo {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .nav-btn {
            display: block;
            height: 30px;
            width: 150px;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            text-indent: -9999px;
            border-radius: 12px;
            transition: background-image 0.3s ease;
            box-sizing: border-box;
        }

        .nav-btn.logout {
            background-image: url('Bouton barre header/Frame 11.png');
        }
        .nav-btn.logout:hover {
            background-image: url('Bouton barre header/Frame 12.png');
        }

        #footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
            background-color: #f2f2f2;
            font-size: 14px;
            text-align: left;
            flex-wrap: wrap;
            gap: 20px;
        }

        .footer-colonne-gauche {
            flex: 1;
            font-size: 16px;
            min-width: 250px;
        }

        .footer-colonne-droite {
            flex: 1;
            min-width: 300px;
            max-width: 800px;
        }

    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="wrapper">
    <div class="content">
        <div class="colonne-gauche">
            <h1>Bienvenue <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?></h1>

            <?php if ($user['role'] === 'agent' && $agent): ?>
                <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Rôle :</strong> <?= htmlspecialchars($user['role']) ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($agent['telephone']) ?></p>
                <p><strong>Spécialité :</strong> <?= htmlspecialchars($specialite) ?></p>
            <?php endif; ?>

            <?php if ($user['role'] === 'admin'): ?>
                <h2>Mon espace de gestion</h2>
                <div style="display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px;">
                    <a href="gestion_agents.php" style="flex: 1 1 200px; text-align: center; background-color: #0B3D91; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold;">Gestion des agents</a>
                    <a href="gestion_biens.php" style="flex: 1 1 200px; text-align: center; background-color: #0B3D91; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold;">Gestion des biens</a>
                    <a href="gestion_clients.php" style="flex: 1 1 200px; text-align: center; background-color: #0B3D91; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold;">Gestion des clients</a>
                    <a href="historique_ventes.php" style="flex: 1 1 200px; text-align: center; background-color: #0B3D91; color: white; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold;">Historique des ventes</a>
                </div>
            <?php endif; ?>

            <br><a href="logout.php" class="nav-btn logout <?= $currentPage == 'votrecompte.php' ? 'active' : '' ?>">Déconnexion</a>
        </div>

        <?php if ($user['role'] === 'agent' && $photoAgent): ?>
            <div class="colonne-droite">
                <img src="<?= htmlspecialchars($photoAgent) ?>" alt="Photo agent" class="agent-photo">
            </div>
        <?php endif; ?>
    </div>

    <div id="footer">
        <div class="footer-colonne-gauche">
            <p>Copyright &copy; 2025 Omnes Immobilier</p>
            <p><a href="mailto:aaron.wipliez@edu.ece.fr">aaron.wipliez@edu.ece.fr</a></p>
            <p>+33 06 33 78 63 73</p>
        </div>
        <div class="footer-colonne-droite">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2625.2109797866384!2d2.285401276723513!3d48.849283500895236!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e671439c2b03a5%3A0x49ebfb04d1b51a5d!2s10%20Rue%20Sextius%20Michel%2C%2075015%20Paris!5e0!3m2!1sfr!2sfr!4v1716898664727!5m2!1sfr!2sfr"
                width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</div>
</body>
</html>
