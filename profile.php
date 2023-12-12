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

// Fetch posts
$posts = [];

$posts_query = "SELECT
                  p.id,
                  p.user_id,
                  p.title,
                  p.content,
                  p.created_at,
                  u.username
                FROM
                  posts p
                JOIN
                  users u ON p.user_id = u.id
                WHERE
                  u.username = '{$user_data["username"]}'";

$posts_result = $mysqli->query($posts_query);
while ($post = $posts_result->fetch_assoc()) {
  $posts[] = $post;
}


$loader = new Twig\Loader\FilesystemLoader("templates");
$twig = new Twig\Environment($loader);
echo $twig->render("profile.html", [
  "user_data" => $user_data,
  "posts" => $posts
]);

?>