<?php
session_start();
require_once 'User.php';

$error = "";
if (isset($_POST['register'])) {
    if ($_POST['password'] != $_POST['confirm']) {
        $error = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($_POST['password']) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $error = User::register($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['password']);
        if ($error == "") {
            User::login($_POST['email'], $_POST['password']);
            $_SESSION['flash'] = ['success', 'Compte créé, bienvenue sur RoomBook !'];
            header("Location: dashboard.php");
            exit();
        }
    }
}

$title = "Inscription";
require 'includes/header.php';
?>
<div class="rb-auth">
    <h2 class="mb-3">Creer un compte</h2>
    <form action="register.php" method="POST">
        <div class="row">
            <div class="col-6">
                <label for="prenom">Prenom</label>
                <input id="prenom" name="prenom" class="form-control" required>
            </div>
            <div class="col-6">
                <label for="nom">Nom</label>
                <input id="nom" name="nom" class="form-control" required>
            </div>
        </div>
        <div class="form-group mt-3">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" class="form-control" required>
        </div>
        <div class="form-group mt-3">
            <label for="password">Mot de passe</label>
            <input id="password" name="password" type="password" class="form-control" required>
        </div>
        <div class="form-group mt-3">
            <label for="confirm">Confirmer le mot de passe</label>
            <input id="confirm" name="confirm" type="password" class="form-control" required>
        </div>
        <input name="register" type="submit" value="S'inscrire" class="btn btn-primary w-100 mt-3">
        <p class="mt-3"><a href="login.php">Deja un compte ? Se connecter</a></p>
        <p class="text-danger mt-3"><?= $error ?></p>
    </form>
</div>
<?php require 'includes/footer.php'; ?>
