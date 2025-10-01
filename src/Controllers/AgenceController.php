<?php

namespace Elayoubi\Covoiturage\Controllers;

use Elayoubi\Covoiturage\Core\Database;
use Elayoubi\Covoiturage\Core\Flash;
use PDO;
use Exception;

class AgenceController
{
  private function requireAdmin(): array
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (empty($_SESSION['user'])) {
      Flash::set("Veuillez vous connecter.", 'warning');
      header("Location: /login");
      exit;
    }
    if ($_SESSION['user']['role'] !== 'admin') {
      Flash::set("Accès réservé à l’administrateur.", 'danger');
      header("Location: /");
      exit;
    }
    return $_SESSION['user'];
  }

  public function index(): void
  {
    $this->requireAdmin();
    include __DIR__ . '/../Views/layouts/header.php';

    $db = new Database();
    $pdo = $db->getConnection();

    $agences = $pdo->query("SELECT id_agence, nom FROM agence ORDER BY nom ASC")
      ->fetchAll(PDO::FETCH_ASSOC);

    echo '<div class="container mt-4">';
    echo '<h2>🏢 Gestion des agences</h2>';

    // Formulaire ajout
    echo '<form method="POST" action="/admin/agences" class="row g-3 mt-2" style="max-width:520px">';
    echo '  <div class="col-8">
                    <label class="form-label">Nom de l\'agence</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>
                <div class="col-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Ajouter</button>
                </div>';
    echo '</form>';

    // Liste
    echo '<div class="table-responsive mt-4">';
    echo '<table class="table table-striped align-middle">';
    echo '<thead class="table-dark"><tr><th>Agence</th><th style="width:120px">Action</th></tr></thead><tbody>';
    foreach ($agences as $a) {
      echo '<tr>
                    <td>' . htmlspecialchars($a['nom']) . '</td>
                    <td>
                      <form method="POST" action="/admin/agences/' . $a['id_agence'] . '/delete" onsubmit="return confirm(\'Supprimer cette agence ?\');">
                        <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                      </form>
                    </td>
                 </tr>';
    }
    echo '</tbody></table></div>';
    echo '</div>';

    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
                 integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
                 crossorigin="anonymous"></script>';
    echo '</body></html>';
  }

  public function store(): void
  {
    $this->requireAdmin();
    $nom = trim($_POST['nom'] ?? '');

    if ($nom === '') {
      Flash::set("Le nom de l'agence est obligatoire.", 'danger');
      header("Location: /admin/agences");
      exit;
    }

    try {
      $db = new Database();
      $pdo = $db->getConnection();

      $stmt = $pdo->prepare("INSERT INTO agence (nom) VALUES (?)");
      $stmt->execute([$nom]);

      Flash::set("Agence « {$nom} » ajoutée ✅", 'success');
    } catch (Exception $e) {
      // Doublon UNIQUE -> message propre
      if ($e->getCode() === '23000') {
        Flash::set("Cette agence existe déjà.", 'warning');
      } else {
        Flash::set("Erreur ajout agence : " . $e->getMessage(), 'danger');
      }
    }
    header("Location: /admin/agences");
    exit;
  }

  public function destroy($id): void
  {
    $this->requireAdmin();
    $id = (int)$id;

    try {
      $db = new Database();
      $pdo = $db->getConnection();

      $stmt = $pdo->prepare("DELETE FROM agence WHERE id_agence = ?");
      $stmt->execute([$id]);

      if ($stmt->rowCount() === 1) {
        Flash::set("Agence supprimée ✅", 'success');
      } else {
        Flash::set("Agence introuvable ❌", 'warning');
      }
    } catch (Exception $e) {
      // Si contrainte FK (agence utilisée dans des trajets)
      Flash::set("Impossible de supprimer : agence utilisée dans des trajets.", 'danger');
    }
    header("Location: /admin/agences");
    exit;
  }
}
