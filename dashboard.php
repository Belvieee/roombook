<?php
$title = "Tableau de bord";
$roles = ['etudiant', 'gestionnaire', 'admin'];
require 'includes/header.php';

$today = date('Y-m-d');
$all = Db::read('reservations');
$rooms = Db::read('rooms');
$mine = [];
$aVenir = 0;
foreach ($all as $r) {
    if ($r['idUser'] == $_SESSION['idUser'] && $r['statut'] != 'annulée') {
        $mine[] = $r;
        if ($r['date'] >= $today) {
            $aVenir++;
        }
    }
}
$reservationsJour = 0;
foreach ($all as $r) {
    if ($r['date'] == $today && $r['statut'] != 'annulée') {
        $reservationsJour++;
    }
}
$notifs = [];
foreach (Db::read('notifications') as $n) {
    if ($n['idUser'] == $_SESSION['idUser']) {
        $notifs[] = $n;
    }
}
?>
<h2 class="mb-4">Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?> 👋</h2>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="rb-card"><div class="rb-stat"><?= $aVenir ?></div>Reservations a venir</div></div>
    <?php if (User::isManager()) { ?>
        <div class="col-md-4"><div class="rb-card"><div class="rb-stat"><?= count($rooms) ?></div>Salles au total</div></div>
        <div class="col-md-4"><div class="rb-card"><div class="rb-stat"><?= $reservationsJour ?></div>Reservations aujourd'hui</div></div>
    <?php } else { ?>
        <div class="col-md-4"><div class="rb-card"><div class="rb-stat"><?= count($mine) ?></div>Reservations au total</div></div>
        <div class="col-md-4"><div class="rb-card"><div class="rb-stat"><?= count($notifs) ?></div>Notifications</div></div>
    <?php } ?>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="rb-card">
            <h5>Mes prochaines reservations</h5>
            <?php $n = 0; foreach ($mine as $r) { if ($r['date'] >= $today) { $n++;
                $room = Db::find('rooms', 'idSalle', $r['idSalle']); ?>
                <p class="mb-2">🗓️ <?= $r['date'] ?> de <?= $r['heureDebut'] ?> a <?= $r['heureFin'] ?> - <strong><?= htmlspecialchars($room['nom']) ?></strong></p>
            <?php } } ?>
            <?php if ($n == 0) { ?><p class="text-muted">Aucune reservation a venir.</p><?php } ?>
            <a href="rooms.php" class="btn btn-primary btn-sm">Reserver une salle</a>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="rb-card">
            <h5>Notifications</h5>
            <?php foreach ($notifs as $notif) { ?>
                <p class="mb-2">🔔 <?= htmlspecialchars($notif['message']) ?> <small class="text-muted">(<?= $notif['dateEnvoi'] ?>)</small></p>
            <?php } ?>
            <?php if (count($notifs) == 0) { ?><p class="text-muted">Aucune notification.</p><?php } ?>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
