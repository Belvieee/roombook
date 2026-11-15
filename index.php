<?php
$title = "Accueil";
require 'includes/header.php';
$rooms = Db::read('rooms');
$nbActives = 0;
foreach ($rooms as $room) {
    if ($room['disponible']) {
        $nbActives++;
    }
}
?>
<section class="rb-hero mb-4">
    <h1>Reservez une salle en quelques clics</h1>
    <p class="lead">RoomBook centralise les salles de votre etablissement et evite les doubles reservations.</p>
    <a href="rooms.php" class="btn btn-light btn-lg mt-2">Voir les salles</a>
    <?php if (!User::isLogged()) { ?>
        <a href="register.php" class="btn btn-outline-light btn-lg mt-2">Creer un compte</a>
    <?php } ?>
</section>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="rb-card">
            <h5>🔍 Rechercher</h5>
            <p>Trouvez une salle selon la date, l'horaire, la capacite ou les equipements.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="rb-card">
            <h5>🗓️ Reserver</h5>
            <p>Choisissez un creneau libre et recevez une confirmation immediate.</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="rb-card">
            <h5>✅ Gerer</h5>
            <p>Consultez et annulez vos reservations depuis votre tableau de bord.</p>
        </div>
    </div>
</div>

<h3 class="mb-3">Apercu des salles (<?= $nbActives ?> disponibles a la reservation)</h3>
<div class="row g-3">
    <?php foreach (array_slice($rooms, 0, 3) as $room) { ?>
        <div class="col-md-4">
            <div class="rb-card">
                <h5><?= htmlspecialchars($room['nom']) ?></h5>
                <p class="mb-1">👥 <?= $room['capacite'] ?> places</p>
                <p>📍 <?= htmlspecialchars($room['localisation']) ?></p>
                <a href="room.php?id=<?= $room['idSalle'] ?>" class="btn btn-primary btn-sm">Voir la salle</a>
            </div>
        </div>
    <?php } ?>
</div>
<?php require 'includes/footer.php'; ?>
