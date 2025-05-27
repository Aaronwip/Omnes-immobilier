<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style-header.css">
	<title>Parcourir - Omnes Immobilier</title>

	<style>
		body {
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
			Copyright &copy; 2025 Omnes Immobilier<br>
			<a href="mailto:aaron.wipliez@edu.ece.fr">aaron.wipliez@edu.ece.fr</a><br>
			<p>+33 06 33 78 63 73</p>
		</div>
	</div>
</body>
</html>
