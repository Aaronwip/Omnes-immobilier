<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Omnes Immobilier</title>

	<style>
		html, body {
			margin: 0;
			padding: 0;
			font-family: Arial, sans-serif;
			background-color: #fff;
			width: 100%;
		}

		.wrapper {
			display: flex;
			flex-direction: column;
			width: 100%;
			max-width: 1000px;
			min-height: 100vh;
			margin: 0 auto;
		}

		#nav {
			display: flex;
			justify-content: center;
			flex-wrap: wrap;
			background-color: #e0e0e0;
			padding: 10px 0;
		}

		#nav a {
			margin: 0 10px;
		}

		.carousel-container {
			position: relative;
			width: 100%;
			overflow: hidden;
		}

		.carousel-slide {
			display: flex;
			width: 100%;
			transition: transform 0.5s ease-in-out;
		}

		.card {
			min-width: 100%;
			box-sizing: border-box;
			padding: 20px;
			border: 1px solid #ccc;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
			text-align: center;
		}

		.card img {
			max-width: 100%;
			height: auto;
		}
		.btn-prev,
		.btn-next {
			position: absolute;
			top: 50%;
			transform: translateY(-50%);
			background: none;
			border: none;
			cursor: pointer;
			padding: 0;
			z-index: 10;
		}

		.btn-prev img,
		.btn-next img {
			width: 50px;
			height: 50px;
		}

		.btn-prev {
			left: 10px;
		}

		.btn-next {
			right: 10px;
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
		<img src="LogoOmnesImmo.png" width="100%" alt="Logo Omnes Immobilier">

		<div id="nav">
			<a href="Accueil.php"><img src="Bouton barre header/Frame 1.png" height="50" alt="Accueil"></a>
			<a href="Parcourir.php"><img src="Bouton barre header/Frame 2.png" height="50" alt="Parcourir"></a>
			<a href="Recherche.php"><img src="Bouton barre header/Frame 3.png" height="50" alt="Recherche"></a>
			<a href="Rendezvous.php"><img src="Bouton barre header/Frame 4.png" height="50" alt="Rendez-vous"></a>
			<a href="Votrecompte.php"><img src="Bouton barre header/Frame 5.png" height="50" alt="Votre compte"></a>
		</div>

		<div class="carousel-container">
			<div class="carousel-slide" id="carouselSlide">
				<?php
				$mysqli = new mysqli("localhost", "root", "", "omnes_immobilier");
				if ($mysqli->connect_error) {
					die("Erreur de connexion : " . $mysqli->connect_error);
				}

				$result = $mysqli->query("SELECT * FROM biens ORDER BY id_bien ASC");
				while ($bien = $result->fetch_assoc()) {
					echo '<div class="card">';
					echo '<h2>' . htmlspecialchars($bien['categorie']) . ' - ' . $bien['surface'] . ' m²</h2>';
					echo '<img src="' . htmlspecialchars($bien['photo']) . '" alt="Photo du bien">';
					echo '<p><strong>Pièces :</strong> ' . htmlspecialchars($bien['pieces']) . '</p>';
					echo '<p><strong>Chambres :</strong> ' . htmlspecialchars($bien['chambres']) . '</p>';
					echo '<p><strong>Prix :</strong> ' . number_format($bien['prix'], 0, ',', ' ') . ' €</p>';
					echo '<p><strong>Adresse :</strong> ' . htmlspecialchars($bien['adresse']) . '</p>';
					echo '</div>';
				}
				$mysqli->close();
				?>
			</div>
			<button class="btn-next" onclick="nextSlide()"><img src="Defiler Droite.png" alt="suivant"></button>
			<button class="btn-prev" onclick="prevSlide()"><img src="Defiler Gauche.png" alt="précédent"></button>

		</div>

		<div id="footer">
			Copyright &copy; 2024 Omnes Immobilier<br>
			<a href="mailto:omnesimmobilier@gmail.com">omnesimmobilier@gmail.com</a><br>
			<p>+33 01 02 03 04 05 / +33 01 10 11 12 13</p>
		</div>
	</div>

	<script>
		let slideIndex = 0;
		const slide = document.getElementById('carouselSlide');
		const total = slide.children.length;

		function updateSlide() {
			slide.style.transform = 'translateX(' + (-slideIndex * 100) + '%)';
		}

		function nextSlide() {
			slideIndex = (slideIndex + 1) % total;
			updateSlide();
		}

		function prevSlide() {
			slideIndex = (slideIndex - 1 + total) % total;
			updateSlide();
		}
	</script>
</body>
</html>
