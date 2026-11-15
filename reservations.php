<?php
$title = "Mes reservations";
$roles = ['etudiant', 'gestionnaire', 'admin'];
require 'includes/header.php';

$settings = Db::read('settings');
if (isset($_POST['annuler'])) {
    $r = Db::find('reservations', 'idReservation', $_POST['id']);
    $limite = strtotime($r['date'] . ' ' . $r['heureDebut']) - $settings['delaiAnnulation'] * 3600;
    if ($r['idUser'] != $_SESSION['idUser']) {
        $_SESSION['flash'] = ['danger', "Vous ne pouvez annuler que vos reservations."];
    } elseif (time() > $limite) {
        $_SESSION['flash'] = ['danger', "Annulation impossible : delai depasse (" . $settings['delaiAnnulation'] . " h avant le debut)."];
    } else {
        Reservation::cancel($r['idReservation']);
        $_SESSION['flash'] = ['success', "Reservation annulee."];
    }
    header("Location: reservations.php");
    exit();
}

$mine = [];
foreach (Db::read('reservations') as $r) {
    if ($r['idUser'] == $_SESSION['idUser']) {
        $mine[] = $r;
    }
}
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Mes reservations</h2>
    <a href="rooms.php" class="btn btn-primary">+ Nouvelle reservation</a>
</div>
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead><tr><th>Salle</th><th>Date</th><th>Horaires</th><th>Statut</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($mine as $r) { $room = Db::find('rooms', 'idSalle', $r['idSalle']); ?>
            <tr>
                <td><?= htmlspecialchars($room['nom']) ?></td>
                <td><?= $r['date'] ?></td>
                <td><?= $r['heureDebut'] ?> - <?= $r['heureFin'] ?></td>
                <td><span class="badge <?= $r['statut'] == 'annulee' ? 'rb-badge-ko' : 'rb-badge-ok' ?>"><?= $r['statut'] == 'annulee' ? '✖' : '✔' ?> <?= $r['statut'] ?></span></td>
                <td>
                    <?php if ($r['statut'] != 'annulee' && $r['date'] >= date('Y-m-d')) { ?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="id" value="<?= $r['idReservation'] ?>">
                            <button name="annuler" class="btn btn-outline-danger btn-sm" data-confirm="Annuler cette reservation ?">Annuler</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php if (count($mine) == 0) { ?><div class="alert alert-info">Vous n'avez pas encore de reservation.</div><?php } ?>
<?php require 'includes/footer.php'; ?>
