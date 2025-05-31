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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $mysqli->real_escape_string($_POST['nom_titulaire']);
    $numero = $mysqli->real_escape_string($_POST['numero_carte']);
    $expiration = $mysqli->real_escape_string($_POST['date_expiration']);
    $cvc = $mysqli->real_escape_string($_POST['code_cvc']);

    // Vérifie si une ligne existe déjà
    $check = $mysqli->query("SELECT * FROM infos_bancaires WHERE id_user = $id_user");
    if ($check->num_rows > 0) {
        $mysqli->query("UPDATE infos_bancaires SET nom_titulaire='$nom', numero_carte='$numero', date_expiration='$expiration', code_cvc='$cvc' WHERE id_user=$id_user");
    } else {
        $mysqli->query("INSERT INTO infos_bancaires (id_user, nom_titulaire, numero_carte, date_expiration, code_cvc) VALUES ($id_user, '$nom', '$numero', '$expiration', '$cvc')");
    }

    // Redirection vers le compte
    header("Location: moncompte.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Informations bancaires</title>
    <link rel="stylesheet" href="style-header.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 30px;
        }

        .form-wrapper {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
        }

        input, label, button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
        }

        button {
            background-color: #0B3D91;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="form-wrapper">
    <h1>Mes informations bancaires</h1>
    <form method="POST">
        <label for="nom_titulaire">Nom du titulaire :</label>
        <input type="text" name="nom_titulaire" required>

        <label for="numero_carte">Numéro de carte :</label>
        <input type="text" name="numero_carte" required maxlength="20">

        <label for="date_expiration">Date d'expiration :</label>
        <input type="date" name="date_expiration" required>

        <label for="code_cvc">Code CVC :</label>
        <input type="text" name="code_cvc" required maxlength="4">

        <button type="submit">Enregistrer</button>
    </form>
</div>
</body>
</html>
