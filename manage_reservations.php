<?php
$title = "Gerer les reservations";
$roles = ['gestionnaire', 'admin'];
require 'includes/header.php';

if (isset($_POST['annuler'])) {
    Reservation::cancel($_POST['id']);
    $_SESSION['flash'] = ['success', 'Reservation annulee par le gestionnaire.'];
    header("Location: manage_reservations.php");
    exit();
}

$filtre = isset($_GET['statut']) ? $_GET['statut'] : '';
$list = [];
foreach (Db::read('reservations') as $r) {
    if ($filtre == '' || $r['statut'] == $filtre) {
        $list[] = $r;
    }
}
?>
<h2 class="mb-3">Gerer les reservations</h2>
<form method="GET" class="row g-2 mb-3" style="max-width: 420px;">
    <div class="col-8">
        <select name="statut" class="form-select" aria-label="Filtrer par statut">
            <option value="">Tous les statuts</option>
            <option value="confirmee" <?= $filtre == 'confirmee' ? 'selected' : '' ?>>Confirmees</option>
            <option value="annulee" <?= $filtre == 'annulee' ? 'selected' : '' ?>>Annulees</option>
        </select>
    </div>
    <div class="col-4"><button class="btn btn-primary w-100">Filtrer</button></div>
</form>
<div class="table-responsive">
    <table class="table align-middle">
        <thead><tr><th>#</th><th>Utilisateur</th><th>Salle</th><th>Date</th><th>Horaires</th><th>Statut</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($list as $r) {
            $u = Db::find('users', 'idUser', $r['idUser']);
            $room = Db::find('rooms', 'idSalle', $r['idSalle']); ?>
            <tr>
                <td><?= $r['idReservation'] ?></td>
                <td><?= htmlspecialchars($u ? $u['prenom'] . ' ' . $u['nom'] : 'Inconnu') ?></td>
                <td><?= htmlspecialchars($room['nom']) ?></td>
                <td><?= $r['date'] ?></td>
                <td><?= $r['heureDebut'] ?> - <?= $r['heureFin'] ?></td>
                <td><span class="badge <?= $r['statut'] == 'annulee' ? 'rb-badge-ko' : 'rb-badge-ok' ?>"><?= $r['statut'] == 'annulee' ? '✖' : '✔' ?> <?= $r['statut'] ?></span></td>
                <td>
                    <?php if ($r['statut'] != 'annulee') { ?>
                        <form method="POST">
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
<?php if (count($list) == 0) { ?><div class="alert alert-info">Aucune reservation.</div><?php } ?>
<?php require 'includes/footer.php'; ?>
