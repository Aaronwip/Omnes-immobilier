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
        SELECT r.id_rdv, r.date_rdv, r.statut, a.nom AS agent_nom, a.prenom AS agent_prenom, a.telephone, a.photo, b.adresse
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
                max-width: 1200px;
                margin: 0 auto;
                background-color: #fff;
                min-height: 100vh;
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
                margin-left: auto; 
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

        </style>
    </head>
    <body>

    <div class="wrapper">
         <?php include 'header.php'; ?>

        <div class="content">
            <h2>Vos rendez-vous</h2>
            <?php while ($rdv = $rdvs->fetch_assoc()): ?>
                <div class="rdv-card">
                    <img src="<?= htmlspecialchars($rdv['photo']) ?>" alt="Agent">
                    <?= htmlspecialchars($rdv['agent_prenom'] . ' ' . $rdv['agent_nom']) ?> - <?= htmlspecialchars($rdv['telephone']) ?><br>
                    <?= htmlspecialchars($rdv['adresse']) ?> à <?= date('d/m/Y H:i', strtotime($rdv['date_rdv'])) ?><br>
                    Statut : <?= $rdv['statut'] ?>
                    <form method="get" action="Rendezvous.php">
                        <input type="hidden" name="cancel_rdv" value="<?= $rdv['id_rdv'] ?>">
                        <button type="submit">Annuler le RDV</button>
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
    <div id="footer">
        Copyright &copy; 2025 Omnes Immobilier<br>
        <a href="mailto:aaron.wipliez@edu.ece.fr">aaron.wipliez@edu.ece.fr</a><br>
        <p>+33 06 33 78 63 73</p>
    </div>

    </div>
    </body>
    </html>

    <?php $mysqli->close(); ?>
