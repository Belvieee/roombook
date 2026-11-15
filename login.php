<?php
session_start();
require_once 'User.php';

$error = "";
if (isset($_POST['login'])) {
    if (User::login($_POST['email'], $_POST['password'])) {
        header("Location: dashboard.php");
        exit();
    }
    $error = "Email ou mot de passe incorrect.";
}

$title = "Connexion";
require 'includes/header.php';
?>
<div class="rb-auth">
    <h2 class="mb-3">Connexion</h2>
    <form action="login.php" method="POST">
        <div class="form-group mt-3">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="Email" class="form-control" required>
        </div>
        <div class="form-group mt-3">
            <label for="password">Mot de passe</label>
            <input id="password" name="password" placeholder="Mot de passe" type="password" class="form-control" required>
        </div>
        <input name="login" type="submit" value="Se connecter" class="btn btn-primary w-100 mt-3">
        <p class="mt-3"><a href="register.php">Pas de compte ? Creer un compte</a></p>
        <p class="text-danger mt-3"><?= $error ?></p>
    </form>
    <hr>
    <p class="small text-muted mb-1">Comptes de demonstration :</p>
    <ul class="small text-muted">
        <li>etudiant@roombook.fr / etudiant123</li>
        <li>manager@roombook.fr / manager123</li>
        <li>admin@roombook.fr / admin123</li>
    </ul>
</div>
<?php require 'includes/footer.php'; ?>
