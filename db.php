<?php
// Uključujemo izveštavanje o greškama u obliku izuzetaka
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $server = "localhost";
    $username = "root";
    $password = ""; 
    $database = "bovoyage_db"; 

    $conn = new mysqli($server, $username, $password, $database);
    $conn->set_charset("utf8");

} catch (Exception $e) {
    
    die("Došlo je do greške pri konekciji sa bazom: " . $e->getMessage());
}
?>