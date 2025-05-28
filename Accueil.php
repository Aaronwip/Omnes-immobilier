<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style-header.css">
	<title>Omnes Immobilier</title>

	<style>
		html, body {
			margin: 0;
			padding: 0;
			font-family: Arial, sans-serif;
			background-color: white;
			width: 100%;
		}

		.wrapper {
			max-width: 1200px;
			margin: 0 auto;
			background-color: white;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
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
					
					if ($bien['categorie'] === 'Immobilier résidentiel') {
						echo '<p><strong>Pièces :</strong> ' . htmlspecialchars($bien['pieces']) . '</p>';
						echo '<p><strong>Chambres :</strong> ' . htmlspecialchars($bien['chambres']) . '</p>';
					}

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
