<?php

require_once __DIR__ . "/vendor/autoload.php";

session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: /login.php");
  exit();
}

if (!isset($_GET["id"])) {
  header("Location: /profile.php");
  exit();
}

// Fetch user data
$mysqli = require_once __DIR__ . "/database.php";
$query = "SELECT * FROM users WHERE id = {$_SESSION["user_id"]}";
$result = $mysqli->query($query);
$user_data = $result->fetch_assoc();

// Fetch post data
$id = $_GET["id"];
$post_query = "SELECT * FROM posts WHERE id = $id";
$post_result = $mysqli->query($post_query);
$post_data = $post_result->fetch_assoc();
if (!$post_data) {
  header("Location: /profile.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $user_id = $user_data["id"];
  $title = $_POST["title"];
  $content = $_POST["content"];

  try {
    $query = "UPDATE posts SET title = ?, content = ? WHERE id = $id";

    $query_statement = $mysqli->stmt_init();
    if (!$query_statement->prepare($query)) {
      die("Server error occurred. Please try again later.");
    }

    $query_statement->bind_param("ss",
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
echo $twig->render("edit.html", [
  "user_data" => $user_data,
  "post_data" => $post_data,
]);

?>