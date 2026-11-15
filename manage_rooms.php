<?php
$title = "Gerer les salles";
$roles = ['gestionnaire', 'admin'];
require 'includes/header.php';

$rooms = Db::read('rooms');

// Activer / desactiver une salle
if (isset($_POST['toggle'])) {
    foreach ($rooms as $i => $room) {
        if ($room['idSalle'] == $_POST['id']) {
            $rooms[$i]['disponible'] = !$room['disponible'];
        }
    }
    Db::write('rooms', $rooms);
    $_SESSION['flash'] = ['success', 'Etat de la salle mis a jour.'];
    header("Location: manage_rooms.php");
    exit();
}

// Ajouter ou modifier une salle
if (isset($_POST['save'])) {
    $equip = array_filter(array_map('trim', explode(',', $_POST['equipements'])));
    if ($_POST['id'] == '') {
        $rooms[] = [
            "idSalle" => Db::nextId($rooms, 'idSalle'),
            "nom" => $_POST['nom'],
            "capacite" => (int) $_POST['capacite'],
            "localisation" => $_POST['localisation'],
            "disponible" => true,
            "equipements" => array_values($equip)
        ];
        $_SESSION['flash'] = ['success', 'Salle ajoutee.'];
    } else {
        foreach ($rooms as $i => $room) {
            if ($room['idSalle'] == $_POST['id']) {
                $rooms[$i]['nom'] = $_POST['nom'];
                $rooms[$i]['capacite'] = (int) $_POST['capacite'];
                $rooms[$i]['localisation'] = $_POST['localisation'];
                $rooms[$i]['equipements'] = array_values($equip);
            }
        }
        $_SESSION['flash'] = ['success', 'Salle modifiee.'];
    }
    Db::write('rooms', $rooms);
    header("Location: manage_rooms.php");
    exit();
}

$edit = isset($_GET['edit']) ? Db::find('rooms', 'idSalle', $_GET['edit']) : null;
?>
<h2 class="mb-3">Gerer les salles</h2>
<div class="row g-3">
    <div class="col-lg-4">
        <form method="POST" class="rb-card">
            <h5><?= $edit ? 'Modifier la salle' : 'Ajouter une salle' ?></h5>
            <input type="hidden" name="id" value="<?= $edit ? $edit['idSalle'] : '' ?>">
            <label for="nom" class="mt-2">Nom</label>
            <input id="nom" name="nom" value="<?= $edit ? htmlspecialchars($edit['nom']) : '' ?>" class="form-control" required>
            <label for="capacite" class="mt-2">Capacite</label>
            <input id="capacite" name="capacite" type="number" min="1" value="<?= $edit ? $edit['capacite'] : '' ?>" class="form-control" required>
            <label for="localisation" class="mt-2">Localisation</label>
            <input id="localisation" name="localisation" value="<?= $edit ? htmlspecialchars($edit['localisation']) : '' ?>" class="form-control" required>
            <label for="equipements" class="mt-2">Equipements (separes par des virgules)</label>
            <input id="equipements" name="equipements" value="<?= $edit ? htmlspecialchars(implode(', ', $edit['equipements'])) : '' ?>" class="form-control">
            <button name="save" class="btn btn-primary mt-3">Enregistrer</button>
            <?php if ($edit) { ?><a href="manage_rooms.php" class="btn btn-outline-secondary mt-3">Annuler</a><?php } ?>
        </form>
    </div>
    <div class="col-lg-8">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Salle</th><th>Capacite</th><th>Etat</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($rooms as $room) { ?>
                    <tr>
                        <td><?= htmlspecialchars($room['nom']) ?><br><small class="text-muted"><?= htmlspecialchars($room['localisation']) ?></small></td>
                        <td><?= $room['capacite'] ?></td>
                        <td><span class="badge <?= $room['disponible'] ? 'rb-badge-ok' : 'rb-badge-ko' ?>"><?= $room['disponible'] ? '✔ Active' : '✖ Desactivee' ?></span></td>
                        <td>
                            <a href="manage_rooms.php?edit=<?= $room['idSalle'] ?>" class="btn btn-outline-primary btn-sm">Modifier</a>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="id" value="<?= $room['idSalle'] ?>">
                                <button name="toggle" class="btn btn-outline-secondary btn-sm"><?= $room['disponible'] ? 'Desactiver' : 'Activer' ?></button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require 'includes/footer.php'; ?>
