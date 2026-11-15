<?php
require_once 'Db.php';

class User {
    public static function login($email, $password) {
        foreach (Db::read('users') as $user) {
            if ($user['email'] == $email && $user['motDePasseHash'] == hash('sha256', $password)) {
                $_SESSION['idUser'] = $user['idUser'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
                $_SESSION['role'] = $user['role'];
                return true;
            }
        }
        return false;
    }

    public static function register($nom, $prenom, $email, $password) {
        $users = Db::read('users');
        foreach ($users as $user) {
            if ($user['email'] == $email) {
                return "Cet email est deja utilise.";
            }
        }
        $users[] = [
            "idUser" => Db::nextId($users, 'idUser'),
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "motDePasseHash" => hash('sha256', $password),
            "role" => "etudiant"
        ];
        Db::write('users', $users);
        return "";
    }

    public static function isLogged() {
        return isset($_SESSION['idUser']);
    }

    public static function role() {
        return isset($_SESSION['role']) ? $_SESSION['role'] : 'invite';
    }

    public static function isManager() {
        return self::role() == 'gestionnaire' || self::role() == 'admin';
    }
}
