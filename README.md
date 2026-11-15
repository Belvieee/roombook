# RoomBook – Front-End (PHP + Bootstrap)

Application web de réservation de salles pour étudiants.
Équipe : Ornis TALATALA, Brian AHANDA, Maïmouna THIAM, Kenyone OBAME.

## Lancer le projet (MAMP / XAMPP)
1. Installer et démarrer MAMP (ou XAMPP) : le serveur Apache doit être actif.
2. Copier le dossier `roombook` dans le dossier `htdocs` :
   - MAMP : `C:\MAMP\htdocs\roombook`
   - XAMPP : `C:\xampp\htdocs\roombook`
3. Ouvrir dans le navigateur : `http://localhost/roombook/`
   (avec MAMP, selon la configuration : `http://localhost:8888/roombook/`)

Aucune base de données à installer : les données sont fictives et stockées dans des fichiers JSON (dossier `data/`).

## Comptes de démonstration
| Rôle | Email | Mot de passe |
|---|---|---|
| Étudiant | etudiant@roombook.fr | etudiant123 |
| Gestionnaire | manager@roombook.fr | manager123 |
| Administrateur | admin@roombook.fr | admin123 |

## Structure du projet
- `index.php` : accueil
- `login.php`, `register.php`, `logout.php` : authentification
- `dashboard.php` : tableau de bord
- `rooms.php` : recherche des salles · `room.php` : détail d'une salle et réservation
- `reservations.php` : mes réservations · `profile.php` : profil
- `manage_rooms.php`, `manage_reservations.php` : gestion (gestionnaire / admin)
- `admin_users.php`, `admin_settings.php` : administration (admin)
- `Db.php`, `User.php`, `Reservation.php` : classes PHP (lecture et écriture des fichiers JSON)
- `data/` : données fictives (JSON)
- `style.css`, `script.js` : style et comportements

## Technologies
PHP, HTML, CSS, JavaScript, Bootstrap 5.
