<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$isLoggedIn = isset($_SESSION) && isset($_SESSION['id_user']);
$comptePage = $isLoggedIn ? 'moncompte.php' : 'VotreCompte.php';
?>
<div class="header">
    <img src="LogoOmnesImmo.png" alt="Logo Omnes Immobilier">
    <div class="nav">
        <a href="Accueil.php" class="nav-btn accueil <?= $currentPage == 'Accueil.php' ? 'active' : '' ?>">Accueil</a>
        <a href="Parcourir.php" class="nav-btn parcourir <?= $currentPage == 'Parcourir.php' ? 'active' : '' ?>">Tout parcourir</a>
        <a href="Recherche.php" class="nav-btn recherche <?= $currentPage == 'Recherche.php' ? 'active' : '' ?>">Recherche</a>
        <a href="Rendezvous.php" class="nav-btn rendezvous <?= $currentPage == 'Rendezvous.php' ? 'active' : '' ?>">Rendez-vous</a>
        <a href="<?= $comptePage ?>" class="nav-btn compte <?= in_array($currentPage, ['VotreCompte.php', 'moncompte.php']) ? 'active' : '' ?>">Votre compte</a>
    </div>
</div>
