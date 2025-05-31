<?php
session_start();

// 1. Vérification de la présence et de la validité de l’ID en GET
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Article introuvable ou ID invalide.");
}
$idArticle = (int) $_GET['id'];

// 2. Connexion à la base de données
$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// 3. Requête préparée pour récupérer l’article correspondant à l’ID
$stmt = $mysqli->prepare("
    SELECT titre, contenu, image_article 
      FROM evenements 
     WHERE id = ?
");
if (!$stmt) {
    die("Erreur lors de la préparation de la requête : " . $mysqli->error);
}
$stmt->bind_param("i", $idArticle);
$stmt->execute();
$res = $stmt->get_result();

// 4. Vérification qu’on a bien trouvé un article
if ($res->num_rows !== 1) {
    $stmt->close();
    $mysqli->close();
    die("Aucun article ne correspond à cet identifiant.");
}

// 5. Récupération des données dans un tableau associatif
$article = $res->fetch_assoc();
$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8') ?></title>
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
        .article-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .texte-article {
            line-height: 1.6;
            margin-top: 20px;
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
        <h1><?= htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8') ?></h1>

        <?php if (!empty($article['image_article'])): ?>
            <img src="<?= htmlspecialchars($article['image_article'], ENT_QUOTES, 'UTF-8') ?>"
                 alt="Image de l’article" class="article-image">
        <?php endif; ?>

        <div class="texte-article">
            <?= nl2br(htmlspecialchars($article['contenu'], ENT_QUOTES, 'UTF-8')) ?>
        </div>
    </div>

    <!-- Footer intégré directement -->
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
</body>
</html>
