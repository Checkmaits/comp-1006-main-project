<?php

session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: /login.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  if (!isset($_GET["id"])) {
    header("Location: /profile.php");
    exit();
  }

  $id = $_GET["id"];
  try {
    $mysqli = require_once __DIR__ . "/database.php";
    $query = "DELETE FROM posts WHERE id = $id";
    $result = $mysqli->query($query);
  } catch (mysqli_sql_exception $e) {
    echo $e->getMessage();
    exit();
  }

  header("Location: /profile.php");
}

?>