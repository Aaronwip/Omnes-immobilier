<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'agent') {
    header("Location: votrecompte.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

$success = "";
$specialites = $mysqli->query("SELECT id_specialite, nom FROM specialites");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $mysqli->real_escape_string($_SESSION['email']);
    $telephone = $mysqli->real_escape_string($_POST['telephone']);
    $specialite_id = intval($_POST['specialite']);

    $photo_path = null;
    $cv_path = null;

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $photo_name = basename($_FILES['photo']['name']);
        $photoName = time() . "_" . basename($_FILES["photo"]["name"]);
		$photoPath = "info_agents/" . $photoName;
		move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath);

    }

    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === 0) {
        $cv_name = basename($_FILES['cv']['name']);
        $cvName = time() . "_" . basename($_FILES["cv"]["name"]);
        $cv_path = "info_agents/" . time() . "_" . $cv_name;
        move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path);
    }

    $query = "UPDATE agents SET telephone='$telephone', specialite_id=$specialite_id";
    if ($photo_path) $query .= ", photo='$photo_path'";
    if ($cv_path) $query .= ", cv='$cv_path'";
    $query .= " WHERE email='$email'";

    if ($mysqli->query($query)) {
        header("Location: moncompte.php");
        exit();
    } else {
        $success = "Erreur lors de l'enregistrement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compléter votre profil agent</title>
    <link rel="stylesheet" href="style-header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: white;
            margin: 0;
        }
        .wrapper {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        input, select, button {
            padding: 10px;
            font-size: 16px;
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
        <h2>Complétez votre profil agent</h2>
        <form method="post" enctype="multipart/form-data">
            <label>Téléphone :
                <input type="tel" name="telephone" required>
            </label>
            <label>Spécialité :
                <select name="specialite" required>
                    <?php while ($s = $specialites->fetch_assoc()): ?>
                        <option value="<?= $s['id_specialite'] ?>"><?= htmlspecialchars($s['nom']) ?></option>
                    <?php endwhile; ?>
                </select>
            </label>
            <label>Photo de profil :
                <input type="file" name="photo" accept="image/*">
            </label>
            <label>CV (PDF ou DOC) :
                <input type="file" name="cv" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            </label>
            <button type="submit">Valider</button>
        </form>
        <p style="color:red;"><?= htmlspecialchars($success) ?></p>
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
</body>
</html>
