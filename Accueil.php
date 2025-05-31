<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style-header.css">
         <!-- Dernier CSS compilé et minifié --> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> 
  
    <!-- Bibliothèque jQuery --> 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script> 
 
    <!-- Dernier JavaScript compilé --> 
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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

        .carousel-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            height: 700px;
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
        height: 400px !important;
        object-fit: cover;
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
        .btn-consulter {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #0B3D91;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .btn-consulter:hover {
            background-color: #062a63;
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


        .article {
          color: inherit;         
          text-decoration: none;  
        }

        .article:hover {
          text-decoration: none; 
        }


    

    </style>
</head>
<body>
    <div class="wrapper">
        <?php include 'header.php'; ?>

        <div class="contenu">

        <div class="evenement"> 
           <div class="row">
            <div class="col-sm-6">
                <!--article -->
            <a href="article.php?id=1"  class="article" style="display: block;">
                <h2><strong>Ne manquez pas les Portes Ouvertes d’Omnes Immobilier !</strong></h2>
                 <p>Du 1er au 3 juin, de 10h à 19h, découvrez une large sélection de biens : appartements, locaux commerciaux, terrains, ventes aux enchères… Nos agents seront présents pour vous accueillir, vous conseiller et vous accompagner dans tous vos projets immobiliers.</p>
            </a>
            </div>

             <!--recherche -->
              <div class="col-sm-6" style="padding: 20px;">
                <form action="Recherche.php" method="POST" class="form-recherche">
                    <label for="critere">Critère :</label>
                    <select name="critere" required class="form-control">
                        <option value="agent">Nom d'agent</option>
                        <option value="bien">Numéro de bien</option>
                        <option value="ville">Ville ou commune</option>
                    </select>

                    <label for="recherche">Rechercher :</label>
                    <input type="text" name="recherche" class="form-control" placeholder="Tapez votre recherche" required>

                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Rechercher</button>
                </form>
            </div>
            </div>
 
        </div>

        <div id="carousel" class="carousel-container">
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
                    echo '<a href="bien.php?id=' . $bien['id_bien'] . '" class="btn-consulter">Voir le bien</a>';
                    echo '</div>';

                }
                $mysqli->close();
                ?>
            </div>
            <button class="btn-next" onclick="nextSlide()"><img src="Defiler Droite.png" alt="suivant"></button>
            <button class="btn-prev" onclick="prevSlide()"><img src="Defiler Gauche.png" alt="précédent"></button>

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
        //php pour faire défiler les slides du carousel #1
        document.addEventListener("DOMContentLoaded", function () {
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

        //php pour faire défiler les slides du carousel #2
        setInterval(() => {
            nextSlide();
        }, 3000);

        document.querySelector('.btn-next').addEventListener('click', nextSlide);
        document.querySelector('.btn-prev').addEventListener('click', prevSlide);
    });
    </script>

</body>
</html>