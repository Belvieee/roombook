<?php
session_start();
require_once __DIR__ . '/../User.php';
require_once __DIR__ . '/../Reservation.php';

// Protection des pages : chaque page peut definir $roles avant d'inclure le header
if (isset($roles)) {
    if (!User::isLogged()) {
        header("Location: login.php");
        exit();
    }
    if (!in_array(User::role(), $roles)) {
        header("Location: dashboard.php");
        exit();
    }
}
$page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'RoomBook' ?> - RoomBook</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg rb-navbar">
    <div class="container">
        <a class="navbar-brand rb-logo" href="index.php">📚 RoomBook</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?= $page == 'index.php' ? 'active' : '' ?>" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link <?= $page == 'rooms.php' || $page == 'room.php' ? 'active' : '' ?>" href="rooms.php">Salles</a></li>
                <?php if (User::isLogged()) { ?>
                    <li class="nav-item"><a class="nav-link <?= $page == 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link <?= $page == 'reservations.php' ? 'active' : '' ?>" href="reservations.php">Mes réservations</a></li>
                <?php } ?>
                <?php if (User::isManager()) { ?>
                    <li class="nav-item"><a class="nav-link <?= $page == 'manage_rooms.php' ? 'active' : '' ?>" href="manage_rooms.php">Gérer les salles</a></li>
                    <li class="nav-item"><a class="nav-link <?= $page == 'manage_reservations.php' ? 'active' : '' ?>" href="manage_reservations.php">Gérer les réservations</a></li>
                <?php } ?>
                <?php if (User::role() == 'admin') { ?>
                    <li class="nav-item"><a class="nav-link <?= $page == 'admin_users.php' ? 'active' : '' ?>" href="admin_users.php">Utilisateurs</a></li>
                    <li class="nav-item"><a class="nav-link <?= $page == 'admin_settings.php' ? 'active' : '' ?>" href="admin_settings.php">Parametres</a></li>
                <?php } ?>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <?php if (User::isLogged()) { ?>
                    <a href="profile.php" class="rb-user"><?= htmlspecialchars($_SESSION['prenom']) ?> <span class="badge text-bg-light"><?= $_SESSION['role'] ?></span></a>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">Deconnexion</a>
                <?php } else { ?>
                    <a href="login.php" class="btn btn-outline-light btn-sm">Connexion</a>
                    <a href="register.php" class="btn btn-light btn-sm">Inscription</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>
<main class="container py-4">
<?php if (isset($_SESSION['flash'])) { ?>
    <div class="alert alert-<?= $_SESSION['flash'][0] ?>" role="alert"><?= htmlspecialchars($_SESSION['flash'][1]) ?></div>
    <?php unset($_SESSION['flash']); ?>
<?php } ?>
