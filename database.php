<?php

$mysqli = new mysqli(
  hostname: "db",
  username: "final_project",
  password: "Brock2003!",
  database: "final_project_db"
);

if ($mysqli->connect_errno) {
  die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

return $mysqli;

?>