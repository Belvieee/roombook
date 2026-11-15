<?php
$title = "Details de la salle";
require 'includes/header.php';

$room = Db::find('rooms', 'idSalle', isset($_GET['id']) ? $_GET['id'] : 0);
if (!$room) {
    echo '<div class="alert alert-danger">Salle introuvable.</div>';
    require 'includes/footer.php';
    exit();
}
$settings = Db::read('settings');
$date = isset($_GET['date']) && $_GET['date'] != '' ? $_GET['date'] : date('Y-m-d');
$error = "";

// Reservation (etudiants, gestionnaires et admins connectes)
if (isset($_POST['reserver']) && User::isLogged()) {
    $date = $_POST['date'];
    $error = Reservation::create($_SESSION['idUser'], $room['idSalle'], $date, $_POST['debut'], $_POST['fin']);
    if ($error == "") {
        $_SESSION['flash'] = ['success', 'Reservation confirmee pour le ' . $date . ' de ' . $_POST['debut'] . ' a ' . $_POST['fin'] . '.'];
        header("Location: reservations.php");
        exit();
    }
}
?>
<a href="rooms.php" class="text-decoration-none">← Retour aux salles</a>
<div class="row g-3 mt-1">
    <div class="col-lg-5">
        <div class="rb-card">
            <h3><?= htmlspecialchars($room['nom']) ?></h3>
            <?php if ($room['disponible']) { ?>
                <span class="badge rb-badge-ok">✔ Reservable</span>
            <?php } else { ?>
                <span class="badge rb-badge-ko">✖ Salle desactivee</span>
            <?php } ?>
            <p class="mt-3 mb-1">👥 Capacite : <?= $room['capacite'] ?> places</p>
            <p class="mb-1">📍 <?= htmlspecialchars($room['localisation']) ?></p>
            <p class="mb-1">🔧 Equipements :</p>
            <ul>
                <?php foreach ($room['equipements'] as $e) { ?><li><?= htmlspecialchars($e) ?></li><?php } ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="rb-card">
            <h5>Disponibilites du <?= htmlspecialchars($date) ?></h5>
            <form method="GET" class="row g-2 mb-3">
                <input type="hidden" name="id" value="<?= $room['idSalle'] ?>">
                <div class="col-8"><input type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="form-control no-past" aria-label="Date"></div>
                <div class="col-4"><button class="btn btn-outline-primary w-100">Voir</button></div>
            </form>
            <div class="rb-slots mb-4">
                <?php for ($h = $settings['heureOuverture']; $h < $settings['heureFermeture']; $h++) {
                    $d = sprintf('%02d:00', $h);
                    $f = sprintf('%02d:00', $h + 1);
                    $libre = $room['disponible'] && Reservation::isFree($room['idSalle'], $date, $d, $f); ?>
                    <div class="rb-slot <?= $libre ? 'libre' : 'occupe' ?>">
                        <?= $d ?> - <?= $f ?><br><small><?= $libre ? '✔ Libre' : '✖ Occupé' ?></small>
                    </div>
                <?php } ?>
            </div>

            <?php if (!User::isLogged()) { ?>
                <div class="alert alert-info mb-0">
                    <a href="login.php">Connectez-vous</a> ou <a href="register.php">creez un compte</a> pour reserver cette salle.
                </div>
            <?php } elseif (!$room['disponible']) { ?>
                <div class="alert alert-warning mb-0">Cette salle est desactivee et ne peut pas etre reservee.</div>
            <?php } else { ?>
                <h5>Reserver</h5>
                <form method="POST" class="row g-2">
                    <div class="col-md-4">
                        <label for="rdate">Date</label>
                        <input id="rdate" type="date" name="date" value="<?= htmlspecialchars($date) ?>" class="form-control no-past" required>
                    </div>
                    <div class="col-6 col-md-4">
                        <label for="rdebut">Debut</label>
                        <input id="rdebut" type="time" name="debut" class="form-control" required>
                    </div>
                    <div class="col-6 col-md-4">
                        <label for="rfin">Fin</label>
                        <input id="rfin" type="time" name="fin" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <button name="reserver" class="btn btn-primary">Confirmer la reservation</button>
                        <small class="text-muted ms-2">Duree max : <?= $settings['dureeMax'] ?> h</small>
                    </div>
                    <?php if ($error != "") { ?><p class="text-danger mt-2"><?= $error ?></p><?php } ?>
                </form>
            <?php } ?>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
