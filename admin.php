<?php
session_start();
require_once "db.php";

// Provera da li je korisnik prijavljen i da li je administrator
if (!isset($_SESSION['id']) || !isset($_SESSION['uloga']) || $_SESSION['uloga'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// U ovu promenljivu smeštamo poruke o uspehu ili grešci
$poruka = "";

// Čuvanje promo teksta u fajl promo.txt
if (isset($_POST['sacuvaj_promo'])) {
    $promo_tekst = trim($_POST['promo_tekst']);

    if (file_put_contents("promo.txt", $promo_tekst) !== false) {
        $poruka = "<div class='alert alert-success'>Promo obaveštenje je uspešno sačuvano!</div>";
    } else {
        $poruka = "<div class='alert alert-danger'>Greška prilikom čuvanja promo teksta.</div>";
    }
}

// Brisanje destinacije
if (isset($_POST['obrisi_destinaciju'])) {
    $id_brisanje = intval($_POST['destinacija_id']);

    // Prvo brišemo rezervacije koje su vezane za tu destinaciju
    $stmt_rez = $conn->prepare("DELETE FROM rezervacije WHERE destinacija_id = ?");
    $stmt_rez->bind_param("i", $id_brisanje);
    $stmt_rez->execute();
    $stmt_rez->close();

    // Zatim brišemo samu destinaciju
    $stmt = $conn->prepare("DELETE FROM destinacije WHERE id = ?");
    $stmt->bind_param("i", $id_brisanje);

    if ($stmt->execute()) {
        $poruka = "<div class='alert alert-success'>Destinacija je uspešno obrisana!</div>";
    } else {
        $poruka = "<div class='alert alert-danger'>Greška prilikom brisanja destinacije.</div>";
    }

    $stmt->close();
}

// Brisanje korisnika
if (isset($_POST['obrisi_korisnika'])) {
    $id_korisnika = intval($_POST['korisnik_id']);

    // Administrator ne može da obriše samog sebe
    if ($id_korisnika === intval($_SESSION['id'])) {
        $poruka = "<div class='alert alert-danger'>Ne možete obrisati sopstveni nalog!</div>";
    } else {
        // Prvo brišemo rezervacije tog korisnika
        $stmt_rez = $conn->prepare("DELETE FROM rezervacije WHERE korisnik_id = ?");
        $stmt_rez->bind_param("i", $id_korisnika);
        $stmt_rez->execute();
        $stmt_rez->close();

        // Zatim brišemo korisnika
        $stmt = $conn->prepare("DELETE FROM korisnici WHERE id = ?");
        $stmt->bind_param("i", $id_korisnika);

        if ($stmt->execute()) {
            $poruka = "<div class='alert alert-success'>Korisnik je uspešno obrisan!</div>";
        } else {
            $poruka = "<div class='alert alert-danger'>Greška prilikom brisanja korisnika.</div>";
        }

        $stmt->close();
    }
}

// Promena statusa rezervacije
if (isset($_POST['promeni_status'])) {
    $id_rez = intval($_POST['rezervacija_id']);
    $novi_status = trim($_POST['novi_status']);

    // Dozvoljavamo samo unapred definisane statuse
    $dozvoljeni_statusi = ['aktivan', 'otkazan', 'zavrsen'];

    if (in_array($novi_status, $dozvoljeni_statusi)) {
        $stmt = $conn->prepare("UPDATE rezervacije SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $novi_status, $id_rez);

        if ($stmt->execute()) {
            $poruka = "<div class='alert alert-success'>Status rezervacije je uspešno ažuriran!</div>";
        } else {
            $poruka = "<div class='alert alert-danger'>Greška prilikom ažuriranja statusa rezervacije.</div>";
        }

        $stmt->close();
    } else {
        $poruka = "<div class='alert alert-danger'>Neispravan status rezervacije!</div>";
    }
}

// Brisanje rezervacije
if (isset($_POST['obrisi_rezervaciju'])) {
    $id_rez = intval($_POST['rezervacija_id']);

    $stmt = $conn->prepare("DELETE FROM rezervacije WHERE id = ?");
    $stmt->bind_param("i", $id_rez);

    if ($stmt->execute()) {
        $poruka = "<div class='alert alert-success'>Rezervacija je uspešno obrisana!</div>";
    } else {
        $poruka = "<div class='alert alert-danger'>Greška prilikom brisanja rezervacije.</div>";
    }

    $stmt->close();
}

// Brisanje kontakt poruke
if (isset($_POST['obrisi_poruku'])) {
    $id_poruke = intval($_POST['poruka_id']);

    $stmt = $conn->prepare("DELETE FROM kontakt_poruke WHERE id = ?");
    $stmt->bind_param("i", $id_poruke);

    if ($stmt->execute()) {
        $poruka = "<div class='alert alert-success'>Kontakt poruka je uspešno obrisana!</div>";
    } else {
        $poruka = "<div class='alert alert-danger'>Greška prilikom brisanja kontakt poruke.</div>";
    }

    $stmt->close();
}

// Brisanje cele statistike poseta
if (isset($_POST['obrisi_svu_statistiku'])) {
    if ($conn->query("TRUNCATE TABLE statistika_poseta")) {
        $poruka = "<div class='alert alert-success'>Sva statistika poseta je uspešno obrisana!</div>";
    } else {
        $poruka = "<div class='alert alert-danger'>Greška prilikom brisanja statistike poseta.</div>";
    }
}

// Učitavanje postojećeg promo teksta iz fajla
$trenutni_promo = "";
if (file_exists("promo.txt")) {
    $trenutni_promo = file_get_contents("promo.txt");
}
?>
<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BoVoyage - Admin Panel</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./fontawesome-free-6.7.2-web/css/all.min.css">
</head>
<body style="background-color:#f8f9fa;">

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Panel</h1>
        <a href="index.php" class="btn btn-outline-dark">Nazad na sajt</a>
    </div>

    <?php echo $poruka; ?>

    <!-- Sekcija za upravljanje destinacijama -->
    <div class="card mb-5 shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Upravljanje destinacijama</h4>
            <a href="dodajDestinaciju.php" class="btn btn-success btn-sm">
                <i class="fa-solid fa-plus"></i> Dodaj novu destinaciju
            </a>
        </div>
        <div class="card-body overflow-auto">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Naziv</th>
                        <th>Cena</th>
                        <th>Polazak</th>
                        <th>Trajanje</th>
                        <th>Slika</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $destinacije = $conn->query("SELECT * FROM destinacije ORDER BY id DESC");

                    if ($destinacije && $destinacije->num_rows > 0):
                        while ($red = $destinacije->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $red['id']; ?></td>
                        <td><?php echo htmlspecialchars($red['naziv']); ?></td>
                        <td>
                            <?php
                            echo number_format($red['cena'], 0, ',', '.');
                            echo ($red['cena'] > 1000) ? " RSD" : " EUR";
                            ?>
                        </td>
                        <td><?php echo date("d.m.Y", strtotime($red['datum_polaska'])); ?></td>
                        <td><?php echo intval($red['trajanje']); ?> dana</td>
                        <td>
                            <?php if (!empty($red['slika'])): ?>
                                <img src="./slike/<?php echo htmlspecialchars($red['slika']); ?>" width="60" class="rounded shadow-sm" alt="slika">
                            <?php else: ?>
                                <span class="text-muted">Nema slike</span>
                            <?php endif; ?>
                        </td>
                        <td class="d-flex gap-2 flex-wrap">
                            <a href="izmeniDestinaciju.php?id=<?php echo $red['id']; ?>" class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-pen-to-square"></i> Izmeni
                            </a>

                            <form method="POST" onsubmit="return confirm('Da li ste sigurni da želite da obrišete ovu destinaciju?');">
                                <input type="hidden" name="destinacija_id" value="<?php echo $red['id']; ?>">
                                <button type="submit" name="obrisi_destinaciju" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i> Obriši
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">Trenutno nema destinacija.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sekcija za upravljanje korisnicima -->
    <div class="card mb-5 shadow">
        <div class="card-header bg-secondary text-white">
            <h4 class="mb-0">Upravljanje korisnicima</h4>
        </div>
        <div class="card-body overflow-auto">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ime i prezime</th>
                        <th>Email</th>
                        <th>Uloga</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $korisnici = $conn->query("SELECT * FROM korisnici ORDER BY id DESC");

                    if ($korisnici && $korisnici->num_rows > 0):
                        while ($k = $korisnici->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $k['id']; ?></td>
                        <td><?php echo htmlspecialchars($k['ime'] . " " . $k['prezime']); ?></td>
                        <td><?php echo htmlspecialchars($k['email']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo ($k['uloga'] === 'admin') ? 'danger' : 'primary'; ?>">
                                <?php echo htmlspecialchars($k['uloga']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($k['id'] != $_SESSION['id']): ?>
                            <form method="POST" onsubmit="return confirm('Da li ste sigurni da želite da obrišete ovog korisnika?');">
                                <input type="hidden" name="korisnik_id" value="<?php echo $k['id']; ?>">
                                <button type="submit" name="obrisi_korisnika" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i> Obriši
                                </button>
                            </form>
                            <?php else: ?>
                                <span class="text-muted small">Trenutno prijavljen admin</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Nema korisnika za prikaz.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sekcija za upravljanje rezervacijama -->
    <div class="card mb-5 shadow">
        <div class="card-header bg-info text-white">
            <h4 class="mb-0">Upravljanje rezervacijama</h4>
        </div>
        <div class="card-body overflow-auto">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID Rez</th>
                        <th>Korisnik</th>
                        <th>Destinacija</th>
                        <th>Broj osoba</th>
                        <th>Status</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $upit = "SELECT r.id, r.broj_osoba, r.status, k.ime, k.prezime, d.naziv
                             FROM rezervacije r
                             JOIN korisnici k ON r.korisnik_id = k.id
                             JOIN destinacije d ON r.destinacija_id = d.id
                             ORDER BY r.id DESC";

                    $rezervacije = $conn->query($upit);

                    if ($rezervacije && $rezervacije->num_rows > 0):
                        while ($rez = $rezervacije->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $rez['id']; ?></td>
                        <td><?php echo htmlspecialchars($rez['ime'] . " " . $rez['prezime']); ?></td>
                        <td><?php echo htmlspecialchars($rez['naziv']); ?></td>
                        <td><?php echo intval($rez['broj_osoba']); ?></td>
                        <td style="min-width: 230px;">
                            <form method="POST" class="d-flex align-items-center gap-2">
                                <input type="hidden" name="rezervacija_id" value="<?php echo $rez['id']; ?>">
                                <select name="novi_status" class="form-select form-select-sm">
                                    <option value="aktivan" <?php if ($rez['status'] === 'aktivan') echo 'selected'; ?>>Aktivan</option>
                                    <option value="otkazan" <?php if ($rez['status'] === 'otkazan') echo 'selected'; ?>>Otkazan</option>
                                    <option value="zavrsen" <?php if ($rez['status'] === 'zavrsen') echo 'selected'; ?>>Završen</option>
                                </select>
                                <button type="submit" name="promeni_status" class="btn btn-primary btn-sm">Sačuvaj</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Da li ste sigurni da želite da obrišete ovu rezervaciju?');">
                                <input type="hidden" name="rezervacija_id" value="<?php echo $rez['id']; ?>">
                                <button type="submit" name="obrisi_rezervaciju" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i> Obriši
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Trenutno nema rezervacija.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sekcija za pregled i brisanje kontakt poruka -->
    <div class="card mb-5 shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="fa-solid fa-envelope"></i> Kontakt poruke korisnika</h4>
        </div>
        <div class="card-body overflow-auto">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ime</th>
                        <th>Email</th>
                        <th>Telefon</th>
                        <th>Poruka</th>
                        <th>Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $poruke = $conn->query("SELECT * FROM kontakt_poruke ORDER BY id DESC");

                    if ($poruke && $poruke->num_rows > 0):
                        while ($p = $poruke->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>
                        <td><?php echo htmlspecialchars($p['ime']); ?></td>
                        <td><?php echo htmlspecialchars($p['email']); ?></td>
                        <td><?php echo htmlspecialchars($p['telefon']); ?></td>
                        <td style="max-width: 350px; white-space: normal;">
                            <?php echo nl2br(htmlspecialchars($p['poruka'])); ?>
                        </td>
                        <td>
                            <form method="POST" onsubmit="return confirm('Da li ste sigurni da želite da obrišete ovu kontakt poruku?');">
                                <input type="hidden" name="poruka_id" value="<?php echo $p['id']; ?>">
                                <button type="submit" name="obrisi_poruku" class="btn btn-danger btn-sm">
                                    <i class="fa-solid fa-trash"></i> Obriši
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">Nema kontakt poruka.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sekcija za statistiku poseta -->
    <div class="card mb-5 shadow">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fa-solid fa-chart-pie"></i> Statistika poseta sistemu</h4>

            <form method="POST" onsubmit="return confirm('Da li ste sigurni da želite da obrišete celu statistiku poseta?');">
                <button type="submit" name="obrisi_svu_statistiku" class="btn btn-danger btn-sm">
                    <i class="fa-solid fa-trash"></i> Obriši svu statistiku
                </button>
            </form>
        </div>
        <div class="card-body">
            <div class="row">

                <div class="col-md-6 mb-4">
                    <h5 class="text-secondary">Top 5 najposećenijih stranica</h5>
                    <ul class="list-group">
                        <?php
                        $upit_stranice = "SELECT stranica, COUNT(*) as broj_poseta
                                          FROM statistika_poseta
                                          GROUP BY stranica
                                          ORDER BY broj_poseta DESC
                                          LIMIT 5";
                        $stat_stranice = $conn->query($upit_stranice);

                        if ($stat_stranice && $stat_stranice->num_rows > 0) {
                            while ($s = $stat_stranice->fetch_assoc()):
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($s['stranica']); ?>
                                <span class="badge bg-primary rounded-pill"><?php echo $s['broj_poseta']; ?> poseta</span>
                            </li>
                        <?php
                            endwhile;
                        } else {
                            echo "<li class='list-group-item'>Nema podataka o posetama.</li>";
                        }
                        ?>
                    </ul>
                </div>

                <div class="col-md-6 mb-4">
                    <h5 class="text-secondary">Struktura posetilaca</h5>
                    <ul class="list-group">
                        <?php
                        $upit_korisnici = "
                            SELECT
                                CASE
                                    WHEN korisnik_id IS NULL THEN 'Gosti (neprijavljeni)'
                                    ELSE 'Prijavljeni korisnici'
                                END AS tip_korisnika,
                                COUNT(*) as broj
                            FROM statistika_poseta
                            GROUP BY tip_korisnika
                        ";
                        $stat_korisnici = $conn->query($upit_korisnici);

                        if ($stat_korisnici && $stat_korisnici->num_rows > 0) {
                            while ($sk = $stat_korisnici->fetch_assoc()):
                        ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($sk['tip_korisnika']); ?>
                                <span class="badge bg-success rounded-pill"><?php echo $sk['broj']; ?> poseta</span>
                            </li>
                        <?php
                            endwhile;
                        } else {
                            echo "<li class='list-group-item'>Nema podataka o strukturi posetilaca.</li>";
                        }
                        ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <!-- Sekcija za unos i izmenu promo teksta -->
    <div class="card mb-5 shadow">
        <div class="card-header bg-danger text-white">
            <h4 class="mb-0"><i class="fa-solid fa-bullhorn"></i> Promo obaveštenje (promo.txt)</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="admin.php">
                <div class="mb-3">
                    <label class="form-label text-muted">
                        Unesite tekst koji će se prikazivati kao žuti baner na vrhu svih stranica.
                    </label>
                    <textarea name="promo_tekst" class="form-control" rows="3"><?php echo htmlspecialchars($trenutni_promo); ?></textarea>
                </div>
                <button type="submit" name="sacuvaj_promo" class="btn btn-danger">
                    <i class="fa-solid fa-floppy-disk"></i> Sačuvaj promo tekst
                </button>
            </form>
        </div>
    </div>

</div>

<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>