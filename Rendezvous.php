<?php
    $mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
    if ($mysqli->connect_error) {
        die("Erreur de connexion : " . $mysqli->connect_error);
    }

    $client_id = 1; // À adapter selon session ou login

    // Annulation
    if (isset($_GET['cancel_rdv'])) {
        $id_rdv = intval($_GET['cancel_rdv']);
        $mysqli->query("UPDATE rdvs SET statut = 'annulé' WHERE id_rdv = $id_rdv AND client_id = $client_id");
    }

    // Prise de RDV
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['agent_id'], $_POST['bien_id'], $_POST['date_rdv'])) {
        $agent_id = intval($_POST['agent_id']);
        $bien_id = intval($_POST['bien_id']);
        $date_rdv = $mysqli->real_escape_string($_POST['date_rdv']);
        $mysqli->query("INSERT INTO rdvs (client_id, agent_id, bien_id, date_rdv, statut) VALUES ($client_id, $agent_id, $bien_id, '$date_rdv', 'en attente')");
    }

    // Liste des RDV
    $rdvs = $mysqli->query("
        SELECT r.id_rdv, r.date_rdv, r.statut, a.nom AS agent_nom, a.prenom AS agent_prenom, a.telephone, a.photo, b.adresse, a.id_agent
        FROM rdvs r
        JOIN agents a ON r.agent_id = a.id_agent
        JOIN biens b ON r.bien_id = b.id_bien
        WHERE r.client_id = $client_id AND r.statut != 'annulé'
        ORDER BY r.date_rdv ASC
    ");

    // Données agents et biens pour le formulaire
    $agents = $mysqli->query("SELECT id_agent, nom, prenom FROM agents");
    $biens = $mysqli->query("SELECT id_bien, adresse FROM biens");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page RDV</title> 
    <link rel="stylesheet" href="style-header.css">

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

        .rdv-card { 
            display: flex; 
            align-items: center; 
            border: 1px solid #888; 
            padding: 10px; 
            margin-bottom: 15px; 
            background: #f9f9f9; 
        }

        .rdv-card img { 
            height: 60px; 
            margin-right: 20px; 
        }

        .rdv-card button { 
            margin-left: 10px; 
            background: #1e3a8a; 
            color: white; 
            border: none; 
            padding: 10px; 
            cursor: pointer; 
        }

        .form-rdv { 
            margin-top: 30px; 
        }

        .form-rdv select, .form-rdv input, .form-rdv button { 
            margin: 5px 0; 
            width: 100%; 
            padding: 10px; 
        }

        #footer {
            padding: 20px;
            background-color: #f2f2f2;
            font-size: 14px;
            text-align: center;
        }

        /* Chat pop-up styles */
        .form-popup {
            display: none;
            position: fixed;
            bottom: 0;
            right: 15px;
            border: 3px solid #f1f1f1;
            z-index: 99;
            max-width: 320px;
            background: white;
            box-shadow: 0 2px 10px #999;
        }

        .form-container {
            max-width: 300px;
            padding: 10px;
            background-color: white;
        }

        .form-container textarea {
            width: 100%;
            padding: 15px;
            margin: 5px 0 22px 0;
            border: none;
            background: #f1f1f1;
            resize: none;
            min-height: 80px;
        }

        .form-container textarea:focus {
            background-color: #ddd;
            outline: none;
        }

        .form-container .btn {
            background-color: #0B3D91;
            color: white;
            padding: 16px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            margin-bottom:10px;
            opacity: 0.8;
        }

        .form-container .cancel {
            background-color: #d5d6d2;
        }

        .form-container .btn:hover {
            opacity: 1;
        }

        .confirmation {
            color: green;
            margin-top: 10px;
            font-weight: bold;
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
    <div class="content">
        <h2>Vos rendez-vous</h2>
        <?php while ($rdv = $rdvs->fetch_assoc()): 
            $chatId = 'chatForm_' . $rdv['id_rdv'];
        ?>
            <div class="rdv-card">
                <img src="<?= htmlspecialchars($rdv['photo']) ?>" alt="Agent">
                <div style="flex:1;">
                    <?= htmlspecialchars($rdv['agent_prenom'] . ' ' . $rdv['agent_nom']) ?> - <?= htmlspecialchars($rdv['telephone']) ?><br>
                    <?= htmlspecialchars($rdv['adresse']) ?> à <?= date('d/m/Y H:i', strtotime($rdv['date_rdv'])) ?><br>
                    Statut : <?= $rdv['statut'] ?>
                </div>
                <form method="get" action="Rendezvous.php" style="margin:0;">
                    <input type="hidden" name="cancel_rdv" value="<?= $rdv['id_rdv'] ?>">
                    <button type="submit">Annuler le RDV</button>
                </form>
                <button type="button" onclick="openForm('<?= $chatId ?>')">Envoyer un message</button>
            </div>

            <!-- source: w3schools -->
            <div class="form-popup" id="<?= $chatId ?>">
                <form method="post" class="form-container" onsubmit="return sendMessage(event, '<?= $chatId ?>')">
                    <h1>Message à <?= htmlspecialchars($rdv['agent_prenom'] . ' ' . $rdv['agent_nom']) ?></h1>
                    <textarea placeholder="Saisir message..." name="msg" required></textarea>
                    <button type="submit" class="btn">Envoyer</button>
                    <button type="button" class="btn cancel" onclick="closeForm('<?= $chatId ?>')">Fermer</button>
                    <div class="confirmation" style="display:none;">Message envoyé !</div>
                </form>
            </div>
        <?php endwhile; ?>

        <div class="form-rdv">
            <h3>Prendre un nouveau rendez-vous</h3>
            <form method="post" action="Rendezvous.php">
                <label for="agent_id">Agent :</label>
                <select name="agent_id" required>
                    <?php while ($a = $agents->fetch_assoc()): ?>
                        <option value="<?= $a['id_agent'] ?>"><?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="bien_id">Bien concerné :</label>
                <select name="bien_id" required>
                    <?php while ($b = $biens->fetch_assoc()): ?>
                        <option value="<?= $b['id_bien'] ?>"><?= htmlspecialchars($b['adresse']) ?></option>
                    <?php endwhile; ?>
                </select>

                <label for="date_rdv">Date et heure :</label>
                <input type="datetime-local" name="date_rdv" required>

                <button type="submit">Prendre le RDV</button>
            </form>
        </div>
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

<script>
function openForm(chatId) {
    // Ferme les autres pop-ups si plusieurs sont ouverts
    document.querySelectorAll('.form-popup').forEach(el => el.style.display = 'none');
    document.getElementById(chatId).style.display = "block";
}
function closeForm(chatId) {
    document.getElementById(chatId).style.display = "none";
}

// Fonction appelée lors de l'envoi du formulaire
function sendMessage(event, chatId) {
    event.preventDefault();
    // Simule l'envoi du message et affiche la confirmation
    var form = event.target;
    form.querySelector('.confirmation').style.display = 'block';
    // Vide le champ message
    form.msg.value = '';
    
    return false; // Empêche la soumission classique du formulaire
}
</script>

</body>
</html>

<?php $mysqli->close(); ?>
