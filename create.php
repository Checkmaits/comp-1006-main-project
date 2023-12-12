<?php

require_once __DIR__ . "/vendor/autoload.php";

session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: /login.php");
  exit();
}

// Fetch user data
$mysqli = require_once __DIR__ . "/database.php";
$query = "SELECT * FROM users WHERE id = {$_SESSION["user_id"]}";
$result = $mysqli->query($query);
$user_data = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user_id = $user_data["id"];
  $title = $_POST["title"];
  $content = $_POST["content"];

  try {
    $query = "INSERT INTO posts (user_id, title, content) 
            VALUES (?, ?, ?)";
    $query_statement = $mysqli->stmt_init();
    if (!$query_statement->prepare($query)) {
      die("Server error occurred. Please try again later.");
    }

    $query_statement->bind_param("sss",
      $user_id,
      $title,
      $content
    );

    $query_statement->execute();
    header("Location: /profile.php");
  } catch (mysqli_sql_exception $e) {
    echo "" . $e->getMessage() . "";
  }
}

$loader = new Twig\Loader\FilesystemLoader("templates");
$twig = new Twig\Environment($loader);
echo $twig->render("create.html", [
  "user_data" => $user_data,
]);

?>