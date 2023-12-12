<?php

require_once __DIR__ . "/vendor/autoload.php";

$user_exists = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $hashed_password = password_hash($_POST["password"], PASSWORD_BCRYPT);
  $mysqli = require_once __DIR__ . "/database.php";

  $username = $_POST["username"];
  $email = $_POST["email"];

  try {
    $query = "INSERT INTO users (username, email, password) 
            VALUES (?, ?, ?)";
    $query_statement = $mysqli->stmt_init();
    if (!$query_statement->prepare($query)) {
      die("Server error occurred. Please try again later.");
    }

    $query_statement->bind_param("sss",
      $_POST["username"],
      $_POST["email"],
      $hashed_password
    );

    $query_statement->execute();
    header("Location: /static_pages/registered.html");
  } catch (mysqli_sql_exception $e) {
    $user_exists = true;
  }
}

$loader = new Twig\Loader\FilesystemLoader("templates");
$twig = new Twig\Environment($loader);
echo $twig->render("register.html", [
  "user_exists" => $user_exists,
]);

?>