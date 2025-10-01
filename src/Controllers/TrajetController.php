<?php

namespace Elayoubi\Covoiturage\Controllers;

use Elayoubi\Covoiturage\Core\Database;
use Elayoubi\Covoiturage\Core\Flash;
use PDO;
use Exception;

class TrajetController
{
  private function requireLogin(): array
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (empty($_SESSION['user'])) {
      Flash::set("Veuillez vous connecter pour accéder à cette action.", 'warning');
      header("Location: /login");
      exit;
    }
    return $_SESSION['user'];
  }

  public function index(): void
  {
    include __DIR__ . '/../Views/layouts/header.php';

    $db = new Database();
    $pdo = $db->getConnection();

    $sql = "
            SELECT t.id_trajet,
                   a1.nom AS depart,
                   t.date_depart,
                   a2.nom AS arrivee,
                   t.date_arrivee,
                   t.nb_places_dispo
            FROM trajet t
            JOIN agence a1 ON t.id_agence_depart = a1.id_agence
            JOIN agence a2 ON t.id_agence_arrivee = a2.id_agence
            WHERE t.nb_places_dispo > 0
              AND t.date_depart > NOW()
            ORDER BY t.date_depart ASC
        ";
    $stmt = $pdo->query($sql);
    $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<div class='container mt-4'>";
    echo "<h2>🚗 Trajets disponibles</h2>";

    if (empty($trajets)) {
      echo "<div class='alert alert-info mt-3'>Aucun trajet disponible pour le moment.</div>";
    } else {
      echo "<div class='table-responsive mt-3'>";
      echo "<table class='table table-bordered table-striped align-middle'>";
      echo "<thead class='table-dark'>
                    <tr>
                        <th>Départ</th>
                        <th>Date départ</th>
                        <th>Arrivée</th>
                        <th>Date arrivée</th>
                        <th>Places dispo</th>
                        <th style='width:160px'>Action</th>
                    </tr>
                  </thead><tbody>";
      foreach ($trajets as $t) {
        echo "<tr>
                        <td>" . htmlspecialchars($t['depart']) . "</td>
                        <td>" . htmlspecialchars($t['date_depart']) . "</td>
                        <td>" . htmlspecialchars($t['arrivee']) . "</td>
                        <td>" . htmlspecialchars($t['date_arrivee']) . "</td>
                        <td>" . htmlspecialchars($t['nb_places_dispo']) . "</td>
                        <td>";
        if (!empty($_SESSION['user'])) {
          echo "<form method='POST' action='/trajets/{$t['id_trajet']}/reserver' class='d-inline'>
                            <button type='submit' class='btn btn-sm btn-primary'>Réserver 1 place</button>
                          </form>";
        } else {
          echo "<a href='/login' class='btn btn-sm btn-outline-secondary'>Se connecter</a>";
        }
        echo "  </td>
                      </tr>";
      }
      echo "</tbody></table></div>";
    }

    echo "</div>";

    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                 integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                 crossorigin="anonymous"></script>';
    echo '</body></html>';
  }

  public function createForm(): void
  {
    $user = $this->requireLogin();

    include __DIR__ . '/../Views/layouts/header.php';

    $db = new Database();
    $pdo = $db->getConnection();
    $agences = $pdo->query("SELECT id_agence, nom FROM agence ORDER BY nom ASC")
      ->fetchAll(PDO::FETCH_ASSOC);

    echo '<div class="container mt-4" style="max-width:720px">';
    echo '<h2>➕ Proposer un trajet</h2>';
    echo '<form method="POST" action="/trajets" class="mt-3">';

    echo '<div class="row g-3">';
    echo '  <div class="col-md-6">
                    <label class="form-label">Agence de départ</label>
                    <select name="id_agence_depart" class="form-select" required>';
    foreach ($agences as $a) {
      echo '<option value="' . $a['id_agence'] . '">' . htmlspecialchars($a['nom']) . '</option>';
    }
    echo '      </select>
                </div>';

    echo '  <div class="col-md-6">
                    <label class="form-label">Agence d\'arrivée</label>
                    <select name="id_agence_arrivee" class="form-select" required>';
    foreach ($agences as $a) {
      echo '<option value="' . $a['id_agence'] . '">' . htmlspecialchars($a['nom']) . '</option>';
    }
    echo '      </select>
                </div>';

    echo '  <div class="col-md-6">
                    <label class="form-label">Date & heure de départ</label>
                    <input type="datetime-local" name="date_depart" class="form-control" required>
                </div>';
    echo '  <div class="col-md-6">
                    <label class="form-label">Date & heure d\'arrivée</label>
                    <input type="datetime-local" name="date_arrivee" class="form-control" required>
                </div>';

    echo '  <div class="col-md-6">
                    <label class="form-label">Places totales</label>
                    <input type="number" name="nb_places_total" min="1" max="8" class="form-control" required>
                </div>';
    echo '  <div class="col-md-6">
                    <label class="form-label">Places disponibles</label>
                    <input type="number" name="nb_places_dispo" min="1" max="8" class="form-control" required>
                </div>';
    echo '</div>';

    echo '<div class="mt-4">
                <button type="submit" class="btn btn-primary">Enregistrer</button>
                <a href="/" class="btn btn-outline-secondary ms-2">Annuler</a>
              </div>';

    echo '</form></div>';

    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                 integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                 crossorigin="anonymous"></script>';
    echo '</body></html>';
  }

  public function store(): void
  {
    $user = $this->requireLogin();

    $id_depart  = (int)($_POST['id_agence_depart'] ?? 0);
    $id_arrivee = (int)($_POST['id_agence_arrivee'] ?? 0);
    $date_dep_in = $_POST['date_depart'] ?? '';
    $date_arr_in = $_POST['date_arrivee'] ?? '';
    $total = (int)($_POST['nb_places_total'] ?? 0);
    $dispo = (int)($_POST['nb_places_dispo'] ?? 0);

    $date_depart  = date('Y-m-d H:i:s', strtotime($date_dep_in));
    $date_arrivee = date('Y-m-d H:i:s', strtotime($date_arr_in));

    $errors = [];
    if ($id_depart <= 0 || $id_arrivee <= 0) {
      $errors[] = "Veuillez choisir les agences de départ et d'arrivée.";
    }
    if ($id_depart === $id_arrivee) {
      $errors[] = "L'agence de départ doit être différente de l'agence d'arrivée.";
    }
    if (!$date_depart || !$date_arrivee || strtotime($date_arrivee) <= strtotime($date_depart)) {
      $errors[] = "La date d'arrivée doit être postérieure à la date de départ.";
    }
    if ($total < 1 || $dispo < 1 || $dispo > $total) {
      $errors[] = "Le nombre de places doit être cohérent (disponibles ≤ totales).";
    }

    if (!empty($errors)) {
      Flash::set("Erreur : " . implode(' ', $errors), 'danger');
      header("Location: /trajets/nouveau");
      exit;
    }

    try {
      $db = new Database();
      $pdo = $db->getConnection();

      $sql = "INSERT INTO trajet 
                    (id_user, id_agence_depart, id_agence_arrivee, date_depart, date_arrivee, nb_places_total, nb_places_dispo)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        $user['id'],
        $id_depart,
        $id_arrivee,
        $date_depart,
        $date_arrivee,
        $total,
        $dispo
      ]);

      Flash::set("Trajet créé avec succès ✅", 'success');
      header("Location: /");
      exit;
    } catch (Exception $e) {
      Flash::set("Erreur d'insertion : " . $e->getMessage(), 'danger');
      header("Location: /trajets/nouveau");
      exit;
    }
  }

  // 🔥 Réserver 1 place (sécurisé par UPDATE conditionnel)
  public function reserve($id): void
  {
    $this->requireLogin();

    $id = (int)$id;
    if ($id <= 0) {
      Flash::set("Trajet invalide.", 'danger');
      header("Location: /");
      exit;
    }

    try {
      $db = new Database();
      $pdo = $db->getConnection();

      // Décrémente si et seulement s'il reste des places
      $sql = "UPDATE trajet 
                    SET nb_places_dispo = nb_places_dispo - 1
                    WHERE id_trajet = ? AND nb_places_dispo > 0";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$id]);

      if ($stmt->rowCount() === 1) {
        Flash::set("Réservation confirmée ✅", 'success');
      } else {
        Flash::set("Plus de place disponible pour ce trajet ❌", 'warning');
      }
      header("Location: /");
      exit;
    } catch (Exception $e) {
      Flash::set("Erreur réservation : " . $e->getMessage(), 'danger');
      header("Location: /");
      exit;
    }
  }
}
