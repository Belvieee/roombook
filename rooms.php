<?php
$title = "Salles";
require 'includes/header.php';

$rooms = Db::read('rooms');
$date = isset($_GET['date']) ? $_GET['date'] : '';
$debut = isset($_GET['debut']) ? $_GET['debut'] : '';
$fin = isset($_GET['fin']) ? $_GET['fin'] : '';
$capacite = isset($_GET['capacite']) ? (int) $_GET['capacite'] : 0;
$equipement = isset($_GET['equipement']) ? $_GET['equipement'] : '';
$search = $date != '' && $debut != '' && $fin != '';

// Liste de tous les equipements pour le filtre
$allEquip = [];
foreach ($rooms as $room) {
    foreach ($room['equipements'] as $e) {
        if (!in_array($e, $allEquip)) {
            $allEquip[] = $e;
        }
    }
}

// Filtrage des salles
$result = [];
foreach ($rooms as $room) {
    if ($capacite > 0 && $room['capacite'] < $capacite) {
        continue;
    }
    if ($equipement != '' && !in_array($equipement, $room['equipements'])) {
        continue;
    }
    $room['libre'] = $room['disponible'];
    if ($search && $room['disponible']) {
        $room['libre'] = Reservation::isFree($room['idSalle'], $date, $debut, $fin);
    }
    $result[] = $room;
}
?>
<h2 class="mb-3">Rechercher une salle</h2>
<form method="GET" action="rooms.php" class="rb-card mb-4">
    <div class="row g-3">
        <div class="col-md-3">
            <label for="date">Date</label>
            <input id="date" type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="form-control no-past">
        </div>
        <div class="col-md-2 col-6">
            <label for="debut">Debut</label>
            <input id="debut" type="time" name="debut" value="<?= htmlspecialchars($debut) ?>" class="form-control">
        </div>
        <div class="col-md-2 col-6">
            <label for="fin">Fin</label>
            <input id="fin" type="time" name="fin" value="<?= htmlspecialchars($fin) ?>" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="capacite">Capacite min.</label>
            <input id="capacite" type="number" min="0" name="capacite" value="<?= $capacite ?>" class="form-control">
        </div>
        <div class="col-md-3">
            <label for="equipement">Equipement</label>
            <select id="equipement" name="equipement" class="form-select">
                <option value="">Tous</option>
                <?php foreach ($allEquip as $e) { ?>
                    <option value="<?= htmlspecialchars($e) ?>" <?= $equipement == $e ? 'selected' : '' ?>><?= htmlspecialchars($e) ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="mt-3">
        <button type="submit" class="btn btn-primary">Rechercher</button>
        <a href="rooms.php" class="btn btn-outline-secondary">Reinitialiser</a>
    </div>
</form>

<p class="text-muted"><?= count($result) ?> salle(s) trouvee(s)</p>
<div class="row g-3">
    <?php foreach ($result as $room) { ?>
        <div class="col-md-6 col-lg-4">
            <div class="rb-card">
                <div class="d-flex justify-content-between align-items-start">
                    <h5><?= htmlspecialchars($room['nom']) ?></h5>
                    <?php if ($room['libre']) { ?>
                        <span class="badge rb-badge-ok">✔ Disponible</span>
                    <?php } else { ?>
                        <span class="badge rb-badge-ko">✖ Indisponible</span>
                    <?php } ?>
                </div>
                <p class="mb-1">👥 <?= $room['capacite'] ?> places</p>
                <p class="mb-1">📍 <?= htmlspecialchars($room['localisation']) ?></p>
                <p class="small text-muted">🔧 <?= htmlspecialchars(implode(', ', $room['equipements'])) ?></p>
                <a href="room.php?id=<?= $room['idSalle'] ?><?= $date != '' ? '&date=' . urlencode($date) : '' ?>" class="btn btn-primary btn-sm">Details</a>
            </div>
        </div>
    <?php } ?>
</div>
<?php if (count($result) == 0) { ?>
    <div class="alert alert-info">Aucune salle ne correspond a votre recherche.</div>
<?php } ?>
<?php require 'includes/footer.php'; ?>
