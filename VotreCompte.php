<?php
$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
session_start();

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $mysqli->real_escape_string($_POST["nom"] ?? '');
    $prenom = $mysqli->real_escape_string($_POST["prenom"] ?? '');
    $email = $mysqli->real_escape_string($_POST["email"]);
    $mdp = $_POST["motdepasse"];
    $role = "utilisateur";

    if ($_POST["action"] == "inscription") {
        $hash = password_hash($mdp, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (nom, prenom, email, mot_de_passe, role)
                VALUES ('$nom', '$prenom', '$email', '$hash', '$role')";

        if ($mysqli->query($sql)) {
            $erreur = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $erreur = "Erreur lors de l'inscription : email peut-être déjà utilisé.";
        }
    } elseif ($_POST["action"] == "connexion") {
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $mysqli->query($sql);
        if ($result && $result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($mdp, $user["mot_de_passe"])) {
                $_SESSION["id_user"] = $user["id_user"];
                $_SESSION["email"] = $user["email"];
                $_SESSION["nom"] = $user["nom"];
                $_SESSION["role"] = $user["role"];
                header("Location: Accueil.php");
                exit();
            } else {
                $erreur = "Mot de passe incorrect.";
            }
        } else {
            $erreur = "Aucun compte trouvé avec cet email.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre compte - Omnes Immobilier</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="POST">
        <input type="hidden" name="action" value="connexion">
        <label>Email : <input type="email" name="email" required></label><br>
        <label>Mot de passe : <input type="password" name="motdepasse" required></label><br>
        <button type="submit">Se connecter</button>
    </form>

    <h2>Inscription</h2>
    <form method="POST">
        <input type="hidden" name="action" value="inscription">
        <label>Nom : <input type="text" name="nom" required></label><br>
        <label>Prénom : <input type="text" name="prenom" required></label><br>
        <label>Email : <input type="email" name="email" required></label><br>
        <label>Mot de passe : <input type="password" name="motdepasse" required></label><br>
        <button type="submit">S'inscrire</button>
    </form>

    <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
</body>
</html>
