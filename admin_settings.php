<?php
$title = "Parametres";
$roles = ['admin'];
require 'includes/header.php';

if (isset($_POST['save'])) {
    Db::write('settings', [
        "heureOuverture" => (int) $_POST['heureOuverture'],
        "heureFermeture" => (int) $_POST['heureFermeture'],
        "dureeMax" => (int) $_POST['dureeMax'],
        "delaiAnnulation" => (int) $_POST['delaiAnnulation']
    ]);
    $_SESSION['flash'] = ['success', 'Parametres enregistres.'];
    header("Location: admin_settings.php");
    exit();
}
$s = Db::read('settings');
?>
<h2 class="mb-3">Parametres generaux</h2>
<form method="POST" class="rb-card" style="max-width: 520px;">
    <div class="row g-3">
        <div class="col-6"><label for="o">Ouverture (h)</label><input id="o" type="number" min="0" max="23" name="heureOuverture" value="<?= $s['heureOuverture'] ?>" class="form-control"></div>
        <div class="col-6"><label for="f">Fermeture (h)</label><input id="f" type="number" min="1" max="24" name="heureFermeture" value="<?= $s['heureFermeture'] ?>" class="form-control"></div>
        <div class="col-6"><label for="d">Duree max d'une reservation (h)</label><input id="d" type="number" min="1" name="dureeMax" value="<?= $s['dureeMax'] ?>" class="form-control"></div>
        <div class="col-6"><label for="a">Delai d'annulation (h avant)</label><input id="a" type="number" min="0" name="delaiAnnulation" value="<?= $s['delaiAnnulation'] ?>" class="form-control"></div>
    </div>
    <button name="save" class="btn btn-primary mt-3">Enregistrer</button>
</form>
<?php require 'includes/footer.php'; ?>
