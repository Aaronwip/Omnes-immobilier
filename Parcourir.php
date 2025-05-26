<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
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

		#nav {
			display: flex;
			justify-content: center;
			flex-wrap: wrap;
			background-color: #e0e0e0;
			padding: 10px;
		}

		#nav a {
			margin: 0 20px;
			text-decoration: none;
		}

		#nav img {
			height: 50px;
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
			text-align: center;
			font-size: 14px;
		}
	</style>
</head>
<body>
	<div class="wrapper">
		<img src="LogoOmnesImmo.png" width="100%" alt="Logo Omnes Immobilier">

		<div id="nav">
			<a href="Accueil.php"><img src="Bouton barre header/Frame 1.png" alt="Accueil"></a>
			<a href="Parcourir.php"><img src="Bouton barre header/Frame 2.png" alt="Parcourir"></a>
			<a href="Recherche.php"><img src="Bouton barre header/Frame 3.png" alt="Recherche"></a>
			<a href="Rendezvous.php"><img src="Bouton barre header/Frame 4.png" alt="Rendez-vous"></a>
			<a href="Votrecompte.php"><img src="Bouton barre header/Frame 5.png" alt="Votre compte"></a>
		</div>

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
			Copyright &copy; 2024 Omnes Immobilier<br>
			<a href="mailto:omnesimmobilier@gmail.com">omnesimmobilier@gmail.com</a><br>
			<p>+33 01 02 03 04 05 / +33 01 10 11 12 13</p>
		</div>
	</div>
</body>
</html>
