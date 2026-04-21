<?php
require_once "db.php";

// Proveravamo da li je AJAX poslao tekst za pretragu
if (isset($_GET['q'])) {

    // Uzimamo tekst iz pretrage i dodajemo % za LIKE uslov
    $pretraga = "%" . trim($_GET['q']) . "%";

    // Tražimo do 5 destinacija čiji naziv sadrži uneti tekst
    // Dodali smo i kolonu valuta da bismo je pravilno prikazali
    $stmt = $conn->prepare("SELECT id, naziv, cena, valuta, slika FROM destinacije WHERE naziv LIKE ? LIMIT 5");
    $stmt->bind_param("s", $pretraga);
    $stmt->execute();
    $result = $stmt->get_result();

    // Ako postoje rezultati, ispisujemo ih
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            // Pravilno prikazujemo cenu sa valutom iz baze
            $prikaz_cene = number_format($row['cena'], 0, ',', '.') . " " . htmlspecialchars($row['valuta']);

            echo "<a href='destinacija.php?id=" . $row['id'] . "' class='list-group-item list-group-item-action text-start'>";
            echo "<div class='d-flex align-items-center'>";

            // Ako postoji slika, prikazujemo je
            if (!empty($row['slika'])) {
                echo "<img src='./slike/" . htmlspecialchars($row['slika']) . "' style='width: 40px; height: 40px; object-fit: cover; margin-right: 15px;' class='rounded-circle shadow-sm' alt='slika destinacije'>";
            }

            echo "<div>";
            echo "<strong class='text-dark'>" . htmlspecialchars($row['naziv']) . "</strong><br>";
            echo "<small class='text-danger fw-bold'>" . $prikaz_cene . "</small>";
            echo "</div>";

            echo "</div></a>";
        }
    } else {
        // Ako nema rezultata, prikazujemo poruku
        echo "<div class='list-group-item text-muted text-center'>Nema rezultata za ovu pretragu.</div>";
    }

    $stmt->close();
}
?>