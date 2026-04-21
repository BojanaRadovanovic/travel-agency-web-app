<?php
session_start();
require_once "db.php";

// Provera da li je admin
if (!isset($_SESSION['id']) || $_SESSION['uloga'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$poruka = "";

// Mora postojati ID destinacije u URL-u
if (!isset($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

$id = intval($_GET['id']);

// Prvo učitavamo postojeću destinaciju iz baze
$stmt = $conn->prepare("SELECT * FROM destinacije WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$rezultat = $stmt->get_result();

if ($rezultat->num_rows === 0) {
    die("Destinacija nije pronađena.");
}

$dest = $rezultat->fetch_assoc();
$stmt->close();

/*
|--------------------------------------------------------------------------
| Kada admin pošalje formu, ažuriramo podatke destinacije
|--------------------------------------------------------------------------
*/
if (isset($_POST['sacuvaj_izmene'])) {
    $naziv = trim($_POST['naziv']);
    $opis = trim($_POST['opis']);
    $cena = floatval($_POST['cena']);
    $datum_polaska = $_POST['datum_polaska'];
    $trajanje = intval($_POST['trajanje']);

    // Podrazumevano zadržavamo staru sliku
    $slika_ime = $dest['slika'];

    // Ako je poslata nova slika, obrađujemo upload
    if (isset($_FILES['slika']) && $_FILES['slika']['error'] === 0) {
        $dozvoljene_ekstenzije = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
        $fajl_ime = $_FILES['slika']['name'];
        $fajl_tmp = $_FILES['slika']['tmp_name'];
        $fajl_velicina = $_FILES['slika']['size'];
        $ekstenzija = strtolower(pathinfo($fajl_ime, PATHINFO_EXTENSION));

        if (!in_array($ekstenzija, $dozvoljene_ekstenzije)) {
            $poruka = "<div class='alert alert-danger'>Dozvoljeni formati su JPG, JPEG, PNG, WEBP i AVIF.</div>";
        } elseif ($fajl_velicina > 5000000) {
            $poruka = "<div class='alert alert-danger'>Slika ne sme biti veća od 5MB.</div>";
        } else {
            $novo_ime = uniqid() . "." . $ekstenzija;
            $putanja = "./slike/" . $novo_ime;

            if (move_uploaded_file($fajl_tmp, $putanja)) {
                $slika_ime = $novo_ime;
            } else {
                $poruka = "<div class='alert alert-danger'>Greška prilikom upload-a slike.</div>";
            }
        }
    }

    if (empty($poruka)) {
        $stmt = $conn->prepare("UPDATE destinacije SET naziv = ?, opis = ?, cena = ?, datum_polaska = ?, trajanje = ?, slika = ? WHERE id = ?");
        $stmt->bind_param("ssdsssi", $naziv, $opis, $cena, $datum_polaska, $trajanje, $slika_ime, $id);

        if ($stmt->execute()) {
            $poruka = "<div class='alert alert-success'>Destinacija je uspešno izmenjena!</div>";

            // Osvežavamo podatke posle izmene
            $stmt_refresh = $conn->prepare("SELECT * FROM destinacije WHERE id = ?");
            $stmt_refresh->bind_param("i", $id);
            $stmt_refresh->execute();
            $dest = $stmt_refresh->get_result()->fetch_assoc();
            $stmt_refresh->close();
        } else {
            $poruka = "<div class='alert alert-danger'>Greška prilikom izmene destinacije.</div>";
        }

        $stmt->close();
    }
}
?>
<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <title>Izmena destinacije</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h2>Izmena destinacije</h2>
    <?php echo $poruka; ?>

    <form method="POST" enctype="multipart/form-data" class="mt-4">
        <div class="mb-3">
            <label class="form-label">Naziv</label>
            <input type="text" name="naziv" class="form-control" value="<?php echo htmlspecialchars($dest['naziv']); ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Opis</label>
            <textarea name="opis" class="form-control" rows="4"><?php echo htmlspecialchars($dest['opis']); ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Cena</label>
                <input type="number" step="0.01" name="cena" class="form-control" value="<?php echo htmlspecialchars($dest['cena']); ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Datum polaska</label>
                <input type="date" name="datum_polaska" class="form-control" value="<?php echo htmlspecialchars($dest['datum_polaska']); ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Trajanje</label>
                <input type="number" name="trajanje" class="form-control" value="<?php echo htmlspecialchars($dest['trajanje']); ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Nova slika (opciono)</label>
            <input type="file" name="slika" class="form-control" accept=".jpg,.jpeg,.png,.webp,.avif">
        </div>

        <?php if (!empty($dest['slika'])): ?>
            <div class="mb-3">
                <p>Trenutna slika:</p>
                <img src="./slike/<?php echo htmlspecialchars($dest['slika']); ?>" width="180" class="rounded shadow">
            </div>
        <?php endif; ?>

        <a href="admin.php" class="btn btn-secondary">Nazad</a>
        <button type="submit" name="sacuvaj_izmene" class="btn btn-primary">Sačuvaj izmene</button>
    </form>
</div>
</body>
</html>