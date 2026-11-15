<?php
$title = "Mon profil";
$roles = ['etudiant', 'gestionnaire', 'admin'];
require 'includes/header.php';

if (isset($_POST['save'])) {
    $users = Db::read('users');
    foreach ($users as $i => $u) {
        if ($u['idUser'] == $_SESSION['idUser']) {
            $users[$i]['nom'] = $_POST['nom'];
            $users[$i]['prenom'] = $_POST['prenom'];
            $users[$i]['email'] = $_POST['email'];
            if ($_POST['password'] != '') {
                $users[$i]['motDePasseHash'] = hash('sha256', $_POST['password']);
            }
        }
    }
    Db::write('users', $users);
    $_SESSION['nom'] = $_POST['nom'];
    $_SESSION['prenom'] = $_POST['prenom'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['flash'] = ['success', 'Profil mis a jour.'];
    header("Location: profile.php");
    exit();
}
$user = Db::find('users', 'idUser', $_SESSION['idUser']);
?>
<h2 class="mb-3">Mon profil</h2>
<form method="POST" class="rb-card" style="max-width: 520px;">
    <div class="row">
        <div class="col-6"><label for="prenom">Prenom</label><input id="prenom" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" class="form-control" required></div>
        <div class="col-6"><label for="nom">Nom</label><input id="nom" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" class="form-control" required></div>
    </div>
    <label for="email" class="mt-3">Email</label>
    <input id="email" name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control" required>
    <label for="password" class="mt-3">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
    <input id="password" name="password" type="password" class="form-control">
    <p class="mt-3 mb-0 text-muted">Role : <strong><?= $user['role'] ?></strong></p>
    <button name="save" class="btn btn-primary mt-3">Enregistrer</button>
</form>
<?php require 'includes/footer.php'; ?>
