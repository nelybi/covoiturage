<?php

namespace Elayoubi\Covoiturage\Core;

use PDO;
use PDOException;

class Database
{
  private $host = "127.0.0.1";
  private $db   = "covoiturage";
  private $user = "root";     // ⚠️ adapte si besoin
  private $pass = "";         // ⚠️ ajoute ton mot de passe si tu en as un
  private $charset = "utf8mb4";

  public function getConnection(): PDO
  {
    try {
      $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
      $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      ];
      return new PDO($dsn, $this->user, $this->pass, $options);
    } catch (PDOException $e) {
      die("Erreur de connexion : " . $e->getMessage());
    }
  }
}
