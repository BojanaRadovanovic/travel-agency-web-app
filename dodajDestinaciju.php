<?php
session_start();
require_once "db.php";

// Provera da li je korisnik prijavljen i da li je administrator
if (!isset($_SESSION['id']) || !isset($_SESSION['uloga']) || $_SESSION['uloga'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Promenljiva za poruku o uspehu ili grešci
$poruka = "";

// Ako je forma poslata, obrađujemo podatke
if (isset($_POST['dodaj_destinaciju'])) {

    // Preuzimanje i osnovna obrada podataka iz forme
    $naziv = trim($_POST['naziv']);
    $opis = trim($_POST['opis']);
    $cena = floatval($_POST['cena']);
    $valuta = trim($_POST['valuta']);
    $kategorija = trim($_POST['kategorija']);
    $tip_ponude = trim($_POST['tip_ponude']);
    $datum_polaska = trim($_POST['datum_polaska']);
    $trajanje = intval($_POST['trajanje']);

    // Dozvoljene vrednosti za polja koja se biraju iz liste
    $dozvoljene_valute = ['RSD', 'EUR'];
    $dozvoljene_kategorije = ['izletiEvropa', 'izletiSrbija', 'letovanja'];
    $dozvoljeni_tipovi = ['regular', 'first_minute', 'last_minute'];

    // Promenljiva za ime slike
    $slika_ime = "";

    // Serverska validacija obaveznih podataka
    if ($naziv === "" || $datum_polaska === "" || $cena <= 0 || $trajanje <= 0) {
        $poruka = "<div class='alert alert-warning'>Molimo vas da popunite sva obavezna polja i unesete ispravne vrednosti.</div>";
    } elseif (!in_array($valuta, $dozvoljene_valute)) {
        $poruka = "<div class='alert alert-danger'>Izabrana valuta nije ispravna.</div>";
    } elseif (!in_array($kategorija, $dozvoljene_kategorije)) {
        $poruka = "<div class='alert alert-danger'>Izabrana kategorija nije ispravna.</div>";
    } elseif (!in_array($tip_ponude, $dozvoljeni_tipovi)) {
        $poruka = "<div class='alert alert-danger'>Izabrani tip ponude nije ispravan.</div>";
    }

    // Obrada slike ako je korisnik poslao fajl
    if (empty($poruka) && isset($_FILES['slika']) && $_FILES['slika']['error'] !== 4) {

        // Ako postoji greška prilikom slanja slike
        if ($_FILES['slika']['error'] !== 0) {
            $poruka = "<div class='alert alert-danger'>Greška prilikom slanja slike.</div>";
        } else {
            $dozvoljene_ekstenzije = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
            $fajl_ime = $_FILES['slika']['name'];
            $fajl_tmp = $_FILES['slika']['tmp_name'];
            $fajl_velicina = $_FILES['slika']['size'];

            // Uzimamo ekstenziju fajla
            $ekstenzija = strtolower(pathinfo($fajl_ime, PATHINFO_EXTENSION));

            // Provera formata slike
            if (!in_array($ekstenzija, $dozvoljene_ekstenzije)) {
                $poruka = "<div class='alert alert-danger'>Dozvoljeni formati slike su JPG, JPEG, PNG, WEBP i AVIF.</div>";
            }
            // Provera veličine slike
            elseif ($fajl_velicina > 5000000) {
                $poruka = "<div class='alert alert-danger'>Slika ne sme biti veća od 5MB.</div>";
            } else {
                // Pravljenje jedinstvenog imena slike
                $novo_ime = uniqid("dest_", true) . "." . $ekstenzija;
                $putanja = "./slike/" . $novo_ime;

                // Prebacivanje slike na server
                if (move_uploaded_file($fajl_tmp, $putanja)) {
                    $slika_ime = $novo_ime;
                } else {
                    $poruka = "<div class='alert alert-danger'>Greška prilikom postavljanja slike na server.</div>";
                }
            }
        }
    }

    // Ako nema grešaka, upisujemo destinaciju u bazu
    if (empty($poruka)) {

        $sql = "INSERT INTO destinacije (naziv, opis, cena, valuta, kategorija, tip_ponude, datum_polaska, trajanje, slika)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        // Provera da li je upit uspešno pripremljen
        if (!$stmt) {
            $poruka = "<div class='alert alert-danger'>Greška u SQL upitu: " . htmlspecialchars($conn->error) . "</div>";
        } else {

            // Tipovi parametara:
            // s - naziv
            // s - opis
            // d - cena
            // s - valuta
            // s - kategorija
            // s - tip_ponude
            // s - datum_polaska
            // i - trajanje
            // s - slika
            $stmt->bind_param(
                "ssdssssis",
                $naziv,
                $opis,
                $cena,
                $valuta,
                $kategorija,
                $tip_ponude,
                $datum_polaska,
                $trajanje,
                $slika_ime
            );

            if ($stmt->execute()) {
                $poruka = "<div class='alert alert-success'>Nova destinacija je uspešno dodata! <a href='admin.php'>Vrati se na Admin panel</a></div>";
            } else {
                $poruka = "<div class='alert alert-danger'>Greška pri upisu u bazu: " . htmlspecialchars($stmt->error) . "</div>";
            }

            $stmt->close();
        }
    }
}
?>
<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BoVoyage - Dodaj destinaciju</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./fontawesome-free-6.7.2-web/css/all.min.css">
</head>
<body style="background-color:#f8f9fa;">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">Dodaj novu destinaciju</h3>
                </div>

                <div class="card-body">

                    <?php echo $poruka; ?>

                    <form action="dodajDestinaciju.php" method="POST" enctype="multipart/form-data">

                        <div class="mb-3">
                            <label class="form-label">Naziv destinacije *</label>
                            <input type="text" name="naziv" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Opis destinacije</label>
                            <textarea name="opis" class="form-control" rows="4"></textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Cena *</label>
                                <input type="number" step="0.01" min="0.01" name="cena" class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Valuta *</label>
                                <select name="valuta" class="form-select" required>
                                    <option value="RSD">RSD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Kategorija *</label>
                                <select name="kategorija" class="form-select" required>
                                    <option value="izletiEvropa">Izleti Evropa</option>
                                    <option value="izletiSrbija">Izleti Srbija</option>
                                    <option value="letovanja">Letovanja</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Tip ponude *</label>
                                <select name="tip_ponude" class="form-select" required>
                                    <option value="regular">Regularna ponuda</option>
                                    <option value="first_minute">First minute</option>
                                    <option value="last_minute">Last minute</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Datum polaska *</label>
                                <input type="date" name="datum_polaska" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Trajanje putovanja (broj dana) *</label>
                                <input type="number" min="1" name="trajanje" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Slika destinacije</label>
                            <input type="file" name="slika" class="form-control" accept=".jpg,.jpeg,.png,.webp,.avif">
                            <small class="text-muted">Dozvoljeni formati: JPG, JPEG, PNG, WEBP, AVIF. Maksimalna veličina: 5MB.</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="admin.php" class="btn btn-secondary">Nazad na Admin panel</a>
                            <button type="submit" name="dodaj_destinaciju" class="btn btn-success">
                                <i class="fa-solid fa-save"></i> Sačuvaj destinaciju
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>