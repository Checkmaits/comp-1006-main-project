<?php

require_once __DIR__ . "/vendor/autoload.php";

$error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST["email"];
  $password = $_POST["password"];

  $mysqli = require_once __DIR__ . "/database.php";
  $query = "SELECT * FROM users WHERE email = '$email'";
  $result = $mysqli->query($query);
  $user = $result->fetch_assoc();
  if ($user) {
    if (password_verify($password, $user["password"])) {
      session_start();
      session_regenerate_id();
      $_SESSION["user_id"] = $user["id"];
      header("Location: /");

      exit();
    }
  }

  $error = true;
}

$loader = new Twig\Loader\FilesystemLoader("templates");
$twig = new Twig\Environment($loader);
echo $twig->render("login.html", [
  "error" => $error
]);

?>