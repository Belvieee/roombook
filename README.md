# RoomBook – Front-End (PHP + Bootstrap)

Application web de reservation de salles pour etudiants.
Equipe : Ornis TALATALA, Brian AHANDA, Maimouna THIAM, Kenyone OBAME.

## Lancer le projet
1. Installer PHP (8+) : https://www.php.net/downloads
2. Dans le dossier du projet : `php -S localhost:8000`
3. Ouvrir http://localhost:8000

## Comptes de demonstration
| Role | Email | Mot de passe |
|---|---|---|
| Etudiant | etudiant@roombook.fr | etudiant123 |
| Gestionnaire | manager@roombook.fr | manager123 |
| Administrateur | admin@roombook.fr | admin123 |

## Structure
- `index.php` accueil . `login.php` / `register.php` / `logout.php`
- `dashboard.php` tableau de bord . `rooms.php` recherche . `room.php` detail + reservation
- `reservations.php` mes reservations . `profile.php` profil
- `manage_rooms.php`, `manage_reservations.php` (gestionnaire/admin)
- `admin_users.php`, `admin_settings.php` (admin)
- `Db.php`, `User.php`, `Reservation.php` : classes (lecture/ecriture de `data/*.json`)
- `data/` : donnees fictives (JSON) . `style.css` . `script.js`
