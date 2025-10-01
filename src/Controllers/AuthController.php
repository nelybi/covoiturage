<?php

namespace Elayoubi\Covoiturage\Controllers;

use Elayoubi\Covoiturage\Core\Database;
use Elayoubi\Covoiturage\Core\Flash;
use PDO;

class AuthController
{
  public function loginForm(): void
  {
    // Header commun (affiche aussi les flashes si présents)
    include __DIR__ . '/../Views/layouts/header.php';
    echo '<div class="container mt-4">
                <h2>Connexion</h2>
                <form method="POST" action="/login" class="mt-3" style="max-width:420px">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                </form>
              </div>';
    // (on peut inclure un footer ici si tu en as un)
  }

  public function login(): void
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $db = new Database();
    $pdo = $db->getConnection();

    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM user WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      $_SESSION['user'] = [
        'id'     => $user['id_user'],
        'prenom' => $user['prenom'],
        'nom'    => $user['nom'],
        'role'   => $user['role'],
        'email'  => $user['email'],
      ];
      Flash::set("Bienvenue, {$user['prenom']} 👋", 'success');
      header("Location: /");
      exit;
    } else {
      Flash::set("Identifiants invalides ❌", 'danger');
      header("Location: /login");
      exit;
    }
  }

  public function logout(): void
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    // On vide juste la session (pour conserver le flash)
    $_SESSION = [];
    Flash::set("Déconnecté. À bientôt 👋", 'success');
    header("Location: /");
    exit;
  }
}
