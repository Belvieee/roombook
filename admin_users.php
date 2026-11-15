<?php
$title = "Utilisateurs";
$roles = ['admin'];
require 'includes/header.php';

$users = Db::read('users');

if (isset($_POST['role'])) {
    foreach ($users as $i => $u) {
        if ($u['idUser'] == $_POST['id'] && $u['idUser'] != $_SESSION['idUser']) {
            $users[$i]['role'] = $_POST['role'];
        }
    }
    Db::write('users', $users);
    $_SESSION['flash'] = ['success', 'Rôle mis à jour.'];
    header("Location: admin_users.php");
    exit();
}

if (isset($_POST['supprimer'])) {
    $reste = [];
    foreach ($users as $u) {
        if ($u['idUser'] != $_POST['id'] || $u['idUser'] == $_SESSION['idUser']) {
            $reste[] = $u;
        }
    }
    Db::write('users', $reste);
    $_SESSION['flash'] = ['success', 'Utilisateur supprimé.'];
    header("Location: admin_users.php");
    exit();
}
?>
<h2 class="mb-3">Gestion des utilisateurs</h2>
<div class="table-responsive">
    <table class="table align-middle">
        <thead><tr><th>Nom</th><th>Email</th><th>Role</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($users as $u) { ?>
            <tr>
                <td><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td>
                    <?php if ($u['idUser'] == $_SESSION['idUser']) { ?>
                        <span class="badge text-bg-secondary"><?= $u['role'] ?> (vous)</span>
                    <?php } else { ?>
                        <form method="POST" class="d-flex gap-2">
                            <input type="hidden" name="id" value="<?= $u['idUser'] ?>">
                            <select name="role" class="form-select form-select-sm" aria-label="Rôle">
                                <?php foreach (['etudiant', 'gestionnaire', 'admin'] as $role) { ?>
                                    <option value="<?= $role ?>" <?= $u['role'] == $role ? 'selected' : '' ?>><?= $role ?></option>
                                <?php } ?>
                            </select>
                            <button class="btn btn-outline-primary btn-sm">OK</button>
                        </form>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($u['idUser'] != $_SESSION['idUser']) { ?>
                        <form method="POST">
                            <input type="hidden" name="id" value="<?= $u['idUser'] ?>">
                            <button name="supprimer" class="btn btn-outline-danger btn-sm" data-confirm="Supprimer cet utilisateur ?">Supprimer</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php require 'includes/footer.php'; ?>
