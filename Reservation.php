<?php
require_once 'Db.php';

class Reservation {
    // Verifie s'il n'y a pas de chevauchement sur la salle (regle metier principale)
    public static function isFree($idSalle, $date, $debut, $fin) {
        foreach (Db::read('reservations') as $r) {
            if ($r['idSalle'] == $idSalle && $r['date'] == $date && $r['statut'] != 'annulee') {
                if ($debut < $r['heureFin'] && $fin > $r['heureDebut']) {
                    return false;
                }
            }
        }
        return true;
    }

    // Retourne une chaine d'erreur, ou "" si la reservation a ete creee
    public static function create($idUser, $idSalle, $date, $debut, $fin) {
        $settings = Db::read('settings');
        $room = Db::find('rooms', 'idSalle', $idSalle);
        if (!$room || !$room['disponible']) {
            return "Cette salle est desactivee.";
        }
        if ($date < date('Y-m-d')) {
            return "La date ne peut pas etre dans le passe.";
        }
        if ($debut >= $fin) {
            return "L'heure de fin doit etre apres l'heure de debut.";
        }
        $duree = (strtotime($fin) - strtotime($debut)) / 3600;
        if ($duree > $settings['dureeMax']) {
            return "Duree maximale : " . $settings['dureeMax'] . " heures.";
        }
        if (!self::isFree($idSalle, $date, $debut, $fin)) {
            return "Ce creneau est deja reserve. Choisissez un autre horaire.";
        }
        $list = Db::read('reservations');
        $list[] = [
            "idReservation" => Db::nextId($list, 'idReservation'),
            "idUser" => $idUser,
            "idSalle" => $idSalle,
            "date" => $date,
            "heureDebut" => $debut,
            "heureFin" => $fin,
            "statut" => "confirmee"
        ];
        Db::write('reservations', $list);

        $notifs = Db::read('notifications');
        $notifs[] = [
            "idNotif" => Db::nextId($notifs, 'idNotif'),
            "idUser" => $idUser,
            "message" => "Votre reservation de la salle " . $room['nom'] . " est confirmee.",
            "dateEnvoi" => date('Y-m-d'),
            "lu" => false
        ];
        Db::write('notifications', $notifs);
        return "";
    }

    public static function cancel($idReservation) {
        $list = Db::read('reservations');
        foreach ($list as $i => $r) {
            if ($r['idReservation'] == $idReservation) {
                $list[$i]['statut'] = 'annulee';
            }
        }
        Db::write('reservations', $list);
    }
}
