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
    <link rel="stylesheet" href="style-header.css">
    <title>Votre compte - Omnes Immobilier</title>

    <style>
            body { 
                font-family: Arial, sans-serif;
                background: white;
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

            .content { 
                background: white; 
                max-width: 800px; 
                margin: 20px auto; 
                padding: 20px; 

            }

            .form form {
                display: flex;
                flex-direction: column;
                width: 100%;
            }

            .form label {
                margin-bottom: 10px;
            }

            .form input{
                padding: 10px;
                font-size: 14px;
                margin-top: 4px;
                margin-bottom: 15px;
                border: 1px solid #ccc;
                border-radius: 6px;
            }

            .form button {
                padding: 10px;
                font-size: 16px;
                background-color: #0B3D91;
                color: white;
                border: none;
                border-radius: 6px;
                cursor: pointer;
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

        <div class="form">
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
        </div>

        <p style="color:red;"><?= htmlspecialchars($erreur) ?></p>
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
