<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

use Elayoubi\Covoiturage\Core\Flash;
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <title>Covoiturage</title>
  <!-- Bootstrap CSS (CDN) -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="/">🚗 Covoiturage</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navMain">
        <ul class="navbar-nav ms-auto">
          <?php if (isset($_SESSION['user'])): ?>
            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
              <li class="nav-item me-2">
                <a class="btn btn-outline-warning btn-sm mt-1" href="/admin/agences">Admin : Agences</a>
              </li>
            <?php endif; ?>
            <li class="nav-item me-2">
              <a class="btn btn-success btn-sm mt-1" href="/trajets/nouveau">+ Proposer un trajet</a>
            </li>
            <li class="nav-item">
              <span class="nav-link">Bonjour, <?= htmlspecialchars($_SESSION['user']['prenom']) ?> 👋</span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/logout">Déconnexion</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="/login">Connexion</a>
            </li>
          <?php endif; ?>
        </ul>


      </div>
    </div>
  </nav>

  <?php
  // ⬇️ Affiche le flash juste sous la navbar
  Flash::display();
  ?>