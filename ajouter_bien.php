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

// Vérification admin
$id = $_SESSION['id_user'];
$result = $mysqli->query("SELECT role FROM users WHERE id_user = $id");
$user = $result->fetch_assoc();

if (!$user || $user['role'] !== 'admin') {
    die("Accès refusé.");
}

// Traitement formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $categorie = $mysqli->real_escape_string($_POST['categorie']);
    $surface = intval($_POST['surface']);
    $prix = floatval($_POST['prix']);
    $adresse = $mysqli->real_escape_string($_POST['adresse']);
    $pieces = isset($_POST['pieces']) ? intval($_POST['pieces']) : 0;
    $chambres = isset($_POST['chambres']) ? intval($_POST['chambres']) : 0;
    $agent_id = intval($_POST['agent_id']);

    // Gestion du fichier photo
    $photo_path = "";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $filename = basename($_FILES['photo']['name']);
        $destination = "Photos_biens/" . $filename;
        move_uploaded_file($_FILES['photo']['tmp_name'], $destination);
        $photo_path = $destination;
    }

    // Insertion SQL
    $stmt = $mysqli->prepare("INSERT INTO biens (categorie, surface, prix, adresse, photo, pieces, chambres, agent_id)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sidssiii", $categorie, $surface, $prix, $adresse, $photo_path, $pieces, $chambres, $agent_id);
    $stmt->execute();

    header("Location: gestion_biens.php");
    exit();
}

// Liste agents
$agents = $mysqli->query("SELECT id_agent, prenom, nom FROM agents ORDER BY nom");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un bien</title>
    <link rel="stylesheet" href="style-header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
        }

        .wrapper {
            max-width: 700px;
            margin: 40px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        }

        h1 {
            text-align: center;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #aaa;
            border-radius: 5px;
        }

        .btn {
            background-color: #0B3D91;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
        }

        .btn:hover {
            background-color: #062d6f;
        }

        .conditional-fields {
            display: none;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #0B3D91;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="wrapper">
    <h1>Ajouter un bien immobilier</h1>
    <form method="post" enctype="multipart/form-data">
        <label>Catégorie</label>
        <select name="categorie" id="categorie" required>
            <option value="">-- Sélectionner --</option>
            <option value="Immobilier résidentiel">Immobilier résidentiel</option>
            <option value="Appartement à louer">Appartement à louer</option>
            <option value="Immobilier commercial">Immobilier commercial</option>
            <option value="Terrain">Terrain</option>
            <option value="Immobiliers en vente par enchère">Immobiliers en vente par enchère</option>
        </select>

        <div class="conditional-fields" id="logement-fields">
            <label>Nombre de pièces</label>
            <input type="number" name="pieces" min="0">
            <label>Nombre de chambres</label>
            <input type="number" name="chambres" min="0">
        </div>

        <label>Surface (m²)</label>
        <input type="number" name="surface" min="1" required>

        <label>Prix (€)</label>
        <input type="number" name="prix" min="0" step="100" required>

        <label>Adresse</label>
        <input type="text" name="adresse" required>

        <label>Photo du bien</label>
        <input type="file" name="photo" accept="image/*" required>

        <label>Agent responsable</label>
        <select name="agent_id" required>
            <option value="">-- Sélectionner un agent --</option>
            <?php while ($a = $agents->fetch_assoc()): ?>
                <option value="<?= $a['id_agent'] ?>"><?= htmlspecialchars($a['prenom']) . ' ' . htmlspecialchars($a['nom']) ?></option>
            <?php endwhile; ?>
        </select>

        <button class="btn" type="submit">Ajouter le bien</button>
    </form>

    <a href="gestion_biens.php">← Retour à la gestion des biens</a>
</div>

<script>
document.getElementById('categorie').addEventListener('change', function () {
    const logementFields = document.getElementById('logement-fields');
    const val = this.value;

    if (val === "Immobilier résidentiel" || val === "Appartement à louer") {
        logementFields.style.display = "block";
    } else {
        logementFields.style.display = "none";
    }
});
</script>
</body>
</html>
