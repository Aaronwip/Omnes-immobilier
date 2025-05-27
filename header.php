<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="header">
    <img src="LogoOmnesImmo.png" alt="Logo Omnes Immobilier">
    <div class="nav">
        <a href="Accueil.php" class="nav-btn accueil <?= $currentPage == 'Accueil.php' ? 'active' : '' ?>">Accueil</a>
        <a href="Parcourir.php" class="nav-btn parcourir <?= $currentPage == 'Parcourir.php' ? 'active' : '' ?>">Tout parcourir</a>
        <a href="Recherche.php" class="nav-btn recherche <?= $currentPage == 'Recherche.php' ? 'active' : '' ?>">Recherche</a>
        <a href="Rendezvous.php" class="nav-btn rendezvous <?= $currentPage == 'Rendezvous.php' ? 'active' : '' ?>">Rendez-vous</a>
        <a href="Votrecompte.php" class="nav-btn compte <?= $currentPage == 'Votrecompte.php' ? 'active' : '' ?>">Votre compte</a>
    </div>
</div>
