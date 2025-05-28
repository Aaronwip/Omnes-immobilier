<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style-header.css">
	<title>Parcourir - Omnes Immobilier</title>

	<style>
		html, body {
			margin: 0;
			padding: 0;
			font-family: Arial, sans-serif;
			background-color: #eee;
		}

		.wrapper {
			max-width: 1200px;
			margin: 0 auto;
			background-color: #fff;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}

		.grid {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
			gap: 20px;
			padding: 40px;
		}

		.card {
			background-color: #f4f4f4;
			padding: 10px;
			text-align: center;
			box-shadow: 0 2px 5px rgba(0,0,0,0.1);
			transition: transform 0.2s;
		}

		.card:hover {
			transform: scale(1.02);
		}

		.card img {
			width: 100%;
			height: 200px;
			object-fit: cover;
			margin-bottom: 10px;
		}

		.card p {
			margin: 5px 0;
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
	<div class="wrapper">
	    <?php include 'header.php'; ?>
	
		<div class="grid">
			<?php
			$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
			if ($mysqli->connect_error) {
				die("Erreur de connexion : " . $mysqli->connect_error);
			}

			$result = $mysqli->query("SELECT * FROM biens ORDER BY id_bien ASC");
			while ($bien = $result->fetch_assoc()) {
				echo '<div class="card">';
				echo '<img src="' . htmlspecialchars($bien['photo']) . '" alt="Photo du bien">';
				echo '<p><strong>' . number_format($bien['prix'], 0, ',', ' ') . ' €</strong></p>';
				echo '<p>Surface : ' . htmlspecialchars($bien['surface']) . ' m²</p>';
				echo '</div>';
			}
			$mysqli->close();
			?>
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
