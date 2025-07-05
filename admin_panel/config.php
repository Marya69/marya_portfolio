<?php
// $servername = "sql306.infinityfree.com";
// $username = "if0_38949651";
// $password = "Zc2hL3M3WIiPp";
// $database = "if0_38949651_mystystem";

// $conn = new mysqli($servername, $username, $password, $database);


// if (!$conn) {
//     die("Database connection failed: " . mysqli_connect_error());
// }
$servername = "localhost";
$username = "root";
$password = "";
$database = "mystystem";

$conn = new mysqli($servername, $username, $password, $database);


if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>