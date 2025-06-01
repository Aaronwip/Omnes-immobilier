<?php
$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");

if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}
session_start();


$results = [];
$hasSearch = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['critere'], $_POST['recherche'])) {
    $hasSearch = true;
    $critere = $_POST['critere'];
    $valeur = $mysqli->real_escape_string($_POST['recherche']);

    switch ($critere) {
        case 'agent':
            $query = "
                SELECT a.*, s.nom AS specialite_nom
                FROM agents a
                LEFT JOIN specialites s ON a.specialite_id = s.id_specialite
                WHERE a.nom LIKE '%$valeur%' OR a.prenom LIKE '%$valeur%'
            ";
            break;

        case 'bien':
            $query = "
                SELECT * FROM biens
                WHERE id_bien = '$valeur'
            ";
            break;

        case 'ville':
            $query = "
                SELECT * FROM biens
                WHERE adresse LIKE '%$valeur%'
            ";
            break;

        default:
            $query = null;
    }

    if (isset($query)) {
        $results = $mysqli->query($query);
    }
}


$biens = $mysqli->query("SELECT * FROM biens ORDER BY id_bien ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche - Omnes Immobilier</title>
    <link rel="stylesheet" href="style-header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f1f1;
            margin: 0;
        }

        .wrapper {
        width: 100%;
        background-color: white;
        }
        
        .contenu {
        max-width: 1200px;
        margin: 0 auto;
        background-color: white;
        display: flex;
        flex-direction: column;
        }

        .contenu {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            padding: 20px;
        }

        .colonne-gauche {
            width: 400px;
            padding-right: 30px;
        }

        .colonne-droite {
            flex: 1;
            max-height: 80vh;
            overflow-y: auto;
            padding-left: 20px;
            border-left: 1px solid #ccc;
        }

        .form-recherche {
            margin-bottom: 30px;
        }

        .form-recherche input,
        .form-recherche select,
        .form-recherche button {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
            font-size: 16px;
        }

        .result {
            display: flex;
            align-items: center;
            background: #f9f9f9;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            gap: 15px;
        }

        .result img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
        }

        .result-info {
            flex: 1;
        }

        .card {
            background-color: #f4f4f4;
            padding: 10px;
            margin-bottom: 15px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
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
<div class="wrapper">
    <?php include 'header.php'; ?>
    <div class="contenu">
        <!-- Colonne gauche : formulaire -->
        <div class="colonne-gauche">
            <h2>Recherche rapide</h2>
            <form method="POST" class="form-recherche">
                <label for="critere">Critère de recherche :</label>
                <select name="critere" required>
                    <option value="agent">Nom d'agent</option>
                    <option value="bien">Numéro de bien</option>
                    <option value="ville">Ville ou commune</option>
                </select>

                <label for="recherche">Recherche :</label>
                <input type="text" name="recherche" required placeholder="Recherchez un agent, un bien ou une ville...">

                <button type="submit">Rechercher</button>
            </form>
        </div>

        <!-- Colonne droite : résultats OU catalogue -->
        <div class="colonne-droite">
            <?php if ($hasSearch): ?>
                <h3>Résultats</h3>
                <?php
                if (!empty($results) && $results instanceof mysqli_result && $results->num_rows > 0) {
                    while ($row = $results->fetch_assoc()) {
                        echo '<div class="result">';
                        if (isset($row['prenom'])) {
                            echo '<img src="' . htmlspecialchars($row['photo']) . '" alt="Agent">';
                            echo '<div class="result-info">';
                            echo "<h2> {$row['prenom']} {$row['nom']}</h2>";
                            echo "<strong>Téléphone :</strong> {$row['telephone']}<br><br>";
                            echo "Agent spécialisé en <strong>" . htmlspecialchars($row['specialite_nom']) . "</strong>";
                            echo '</div>';
                        }
                        elseif (isset($row['surface'])) {
                            echo '<img src="' . htmlspecialchars($row['photo']) . '" alt="Bien">';
                            echo '<div class="result-info">';
                            echo "<strong>Bien #{$row['id_bien']}</strong><br>";
                            echo "<strong>Adresse :</strong> {$row['adresse']}<br>";
                            echo "<strong>Surface :</strong> {$row['surface']} m²<br>";
                            echo "<strong>Prix :</strong> " . number_format($row['prix'], 0, ',', ' ') . " €";
                            echo '</div>';
                        }
                        echo '</div>';
                    }
                } else {
                    echo "<p>Aucun résultat trouvé.</p>";
                }
                ?>
            <?php else: ?>
                <h3>Catalogue des biens</h3>
                <?php while ($bien = $biens->fetch_assoc()): ?>
                    <div class="card">
                        <img src="<?= htmlspecialchars($bien['photo']) ?>" alt="Photo du bien">
                        <p><strong><?= number_format($bien['prix'], 0, ',', ' ') ?> €</strong></p>
                        <p>Surface : <?= htmlspecialchars($bien['surface']) ?> m²</p>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
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

<?php $mysqli->close(); ?>