<?php
session_start();

// Vérification admin
if (!isset($_SESSION['id_user'])) {
    header("Location: votrecompte.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}

// Vérifier le rôle
$id_user = $_SESSION['id_user'];
$res = $mysqli->query("SELECT role FROM users WHERE id_user = $id_user");
$user = $res->fetch_assoc();
if (!$user || $user['role'] !== 'admin') {
    die("Accès refusé.");
}

// Récupérer les spécialités
$specialites_result = $mysqli->query("SELECT id_specialite, nom FROM specialites");

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $specialite_id = $_POST['specialite_id'];
    $photoPath = '';
    $cvPath = '';

    if (!empty($_FILES['photo']['name'])) {
        $photoPath = 'uploads/photos/' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    if (!empty($_FILES['cv']['name'])) {
        $cvPath = 'uploads/cv/' . basename($_FILES['cv']['name']);
        move_uploaded_file($_FILES['cv']['tmp_name'], $cvPath);
    }

    // Insérer dans agents
    $stmt = $mysqli->prepare("INSERT INTO agents (prenom, nom, telephone, email, photo, cv, specialite_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $prenom, $nom, $telephone, $email, $photoPath, $cvPath, $specialite_id);
    $stmt->execute();
    $id_agent = $stmt->insert_id;
    $stmt->close();

    // Insérer les créneaux 30 min automatiquement
    foreach ($_POST['dispo'] as $jour => $plage) {
        $debut = $plage['debut'];
        $fin = $plage['fin'];

        if ($debut && $fin && $debut >= "08:00" && $fin <= "20:00" && $debut < $fin) {
            $start = new DateTime($debut);
            $end = new DateTime($fin);

            while ($start < $end) {
                $next = clone $start;
                $next->modify('+30 minutes');

                if ($next > $end) break;

                $h_debut = $start->format("H:i");
                $h_fin = $next->format("H:i");

                $stmt = $mysqli->prepare("INSERT INTO disponibilites (agent_id, jour, heure_debut, heure_fin) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $id_agent, $jour, $h_debut, $h_fin);
                $stmt->execute();
                $stmt->close();

                $start = $next;
            }
        }
    }

    header("Location: gestion_agents.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un agent</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eee;
            padding: 40px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-top: 10px;
        }

        input, select {
            padding: 8px;
            margin-top: 4px;
        }

        fieldset {
            margin-top: 20px;
            padding: 10px;
        }

        legend {
            font-weight: bold;
        }

        .dispo-day {
            margin-bottom: 10px;
        }

        .dispo-day input {
            margin-right: 10px;
            width: 140px;
        }

        button {
            margin-top: 20px;
            background-color: #0B3D91;
            color: white;
            border: none;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Ajouter un agent</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Prénom :</label>
        <input type="text" name="prenom" required>

        <label>Nom :</label>
        <input type="text" name="nom" required>

        <label>Téléphone :</label>
        <input type="text" name="telephone" required>

        <label>Email :</label>
        <input type="email" name="email" required>

        <label>Spécialité :</label>
        <select name="specialite_id" required>
            <?php while ($spec = $specialites_result->fetch_assoc()): ?>
                <option value="<?= $spec['id_specialite'] ?>"><?= htmlspecialchars($spec['nom']) ?></option>
            <?php endwhile; ?>
        </select>

        <label>Photo :</label>
        <input type="file" name="photo" accept="image/*">

        <label>CV :</label>
        <input type="file" name="cv" accept=".pdf,.doc,.docx">

        <fieldset>
            <legend>Disponibilités hebdomadaires (créneaux de 30min)</legend>
            <?php
            $jours = ['lundi','mardi','mercredi','jeudi','vendredi','samedi'];

            // Génère les créneaux horaires entre 08:00 et 20:00
            function generer_options_heures() {
                $options = "";
                $heure = new DateTime('08:00');
                $fin = new DateTime('20:00');
                while ($heure <= $fin) {
                    $val = $heure->format('H:i');
                    $options .= "<option value=\"$val\">$val</option>";
                    $heure->modify('+30 minutes');
                }
                return $options;
            }

            $options = generer_options_heures();

            foreach ($jours as $jour):
            ?>
                <div class="dispo-day">
                    <label><?= ucfirst($jour) ?> :</label>
                    <select name="dispo[<?= $jour ?>][debut]">
                        <option value="">Début</option>
                        <?= $options ?>
                    </select>
                    <select name="dispo[<?= $jour ?>][fin]">
                        <option value="">Fin</option>
                        <?= $options ?>
                    </select>
                </div>
            <?php endforeach; ?>
        </fieldset>


        <button type="submit">Ajouter l’agent</button>
    </form>
</div>

<script>
document.querySelector("form").addEventListener("submit", function (e) {
    const jours = ["lundi","mardi","mercredi","jeudi","vendredi","samedi"];
    let erreur = false;

    jours.forEach(jour => {
        const debut = document.querySelector(`[name='dispo[${jour}][debut]']`).value;
        const fin = document.querySelector(`[name='dispo[${jour}][fin]']`).value;

        if (debut && fin && debut >= fin) {
            alert(`Erreur : l'heure de fin doit être après celle de début pour ${jour}.`);
            erreur = true;
        }
    });

    if (erreur) {
        e.preventDefault(); // Bloque la soumission du formulaire
    }
});
</script>

</body>
</html>
