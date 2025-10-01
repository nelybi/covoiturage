<?php

namespace Elayoubi\Covoiturage\Core;

class Flash
{
  public static function set(string $message, string $type = 'success'): void
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    $_SESSION['flash'] = [
      'message' => $message,
      'type' => $type, // success | danger | warning | info
    ];
  }

  public static function display(): void
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    if (!empty($_SESSION['flash'])) {
      $flash = $_SESSION['flash'];
      echo "<div class='container'><div class='alert alert-{$flash['type']} mt-3 mb-0'>{$flash['message']}</div></div>";
      unset($_SESSION['flash']); // affiché une seule fois
    }
  }
}
