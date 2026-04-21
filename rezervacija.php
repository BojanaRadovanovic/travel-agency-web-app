<?php
session_start();
if(!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$korisnik_id = $_SESSION['id'];
$mysqli = new mysqli("localhost", "root", "", "bovoyage_db");
if($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

// Dohvati sve destinacije za select u formi
$destinacije_res = $mysqli->query("SELECT id, naziv, cena, datum_polaska, slika FROM destinacije");

// Ako je forma poslata
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novarez'])) {
    $dest_id = $_POST['destinacija_id'];
    $broj_osoba = $_POST['broj_osoba'];
    $status = 'aktivan';

    $stmt = $mysqli->prepare("INSERT INTO rezervacije (korisnik_id, destinacija_id, broj_osoba, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $korisnik_id, $dest_id, $broj_osoba, $status);
    $stmt->execute();
    $stmt->close();

    // Preusmeri na moje rezervacije nakon uspesne rezervacije
    header("Location: mojeRezervacije.php");
    exit();
}

// Ako dolazi sa stranice destinacije, iz GET
$odabrana_dest = null;
if(isset($_GET['destinacija_id']) && !empty($_GET['destinacija_id'])) {
    $dest_id = intval($_GET['destinacija_id']); // intval radi sigurnosti
    // Dodata 'slika' u upit
    $stmt2 = $mysqli->prepare("SELECT id, naziv, cena, datum_polaska, slika FROM destinacije WHERE id=?");
    $stmt2->bind_param("i", $dest_id);
    $stmt2->execute();
    $res = $stmt2->get_result();
    if($res->num_rows > 0) {
        $odabrana_dest = $res->fetch_assoc();
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BoVoyage - Rezervacija</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/stil.css">
    <link rel="stylesheet" href="./fontawesome-free-6.7.2-web/css/all.min.css">
  </head>
  <body style="background-color: #f8f9fa;">
    
  <div class="top-logo bg-white">
    <img src="./slike/logo1.png" alt="BoVoyage glavni logo">
  </div>
  
  <?php include 'header.php'; ?>

  <section class="py-5">
    <div class="container">
      <h2 class="display-5 text-center mb-5">Nova rezervacija</h2>
      
      <div class="row g-4 align-items-stretch">
        
        <div class="col-12 col-md-5">
          <?php if($odabrana_dest): ?>
            <div class="card h-100 shadow-sm border-0">
              <img src="./slike/<?php echo htmlspecialchars($odabrana_dest['slika'] ?? 'default.jpg'); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($odabrana_dest['naziv']); ?>" style="height: 300px; object-fit: cover;">
              <div class="card-body text-center d-flex flex-column justify-content-center">
                <h4 class="card-title"><?php echo htmlspecialchars($odabrana_dest['naziv']); ?></h4>
                <hr>
                <p class="card-text mb-1 text-muted">Datum polaska: <?php echo date("d.m.Y", strtotime($odabrana_dest['datum_polaska'])); ?></p>
               <p class="card-text fs-5 text-primary">
             <strong>Cena po osobi: 
             <?php 
       if ($odabrana_dest['cena'] > 1500) {
        echo number_format($odabrana_dest['cena'], 0, ',', '.') . " RSD";
       } else {
        echo number_format($odabrana_dest['cena'], 0, ',', '.') . " EUR";
       }
        ?>
       </strong>
              </p>
              </div>
            </div>
          <?php else: ?>
            <div class="card h-100 shadow-sm border-0 bg-light d-flex justify-content-center align-items-center" style="min-height: 300px;">
              <div class="card-body text-center text-muted d-flex flex-column justify-content-center">
                <i class="fa-solid fa-map-location-dot fa-4x mb-3 text-secondary"></i>
                <h5>Niste izabrali destinaciju</h5>
                <p class="small">Izaberite željenu destinaciju iz padajućeg menija da biste videli detalje aranžmana.</p>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <div class="col-12 col-md-7">
          <div class="card h-100 shadow-sm border-0 p-4">
            <h4 class="mb-4">Unesite detalje rezervacije</h4>
            <form method="post" action="rezervacija.php">
              
              <div class="mb-4">
                <label class="form-label fw-bold">Destinacija</label>
                <select name="destinacija_id" class="form-select form-select-lg" required onchange="window.location.href='rezervacija.php?destinacija_id='+this.value">
                  <option value="">-- Izaberite destinaciju --</option>
                  <?php while($row = $destinacije_res->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>" <?php if($odabrana_dest && $odabrana_dest['id'] == $row['id']) echo "selected"; ?>>
                      <?php echo htmlspecialchars($row['naziv']) . " - " . date("d.m.Y", strtotime($row['datum_polaska'])); ?>
                    </option>
                  <?php endwhile; ?>
                </select>
              </div>
              
              <div class="mb-4">
                <label class="form-label fw-bold">Broj putnika</label>
                <input type="number" name="broj_osoba" class="form-control form-select-lg" min="1" max="20" value="1" required>
              </div>
              
              <div class="d-grid gap-2 mt-5">
                <button type="submit" name="novarez" class="btn btn-primary btn-lg"><i class="fa-solid fa-check"></i> Potvrdi rezervaciju</button>
              </div>
            </form>
          </div>
        </div>

      </div>

      <div class="mt-5 text-center">
        <a href="mojeRezervacije.php" class="btn btn-outline-secondary"><i class="fa-solid fa-list"></i> Pregled svih mojih rezervacija</a>
      </div>
    </div>
  </section>

  <footer class="bg-dark text-white mt-auto">
    <div class="footer-container">
      <div class="footer-left">
        <a href="index.php">
          <h1 class="text-white">BoVoyage</h1>
          <p class="text-white">~Putuj pametno~</p>
        </a>
      </div>
      <div class="footer-columns">
        <div class="footer-column">
          <h4>Više o nama</h4>
          <a href="onama.php">O nama</a>
          <a href="kontakt.php">Kontakt</a>
          <a href="mapa.php">Mapa sajta</a>
          <a href="faq.php">FAQ</a>
        </div>
        <div class="footer-column">
          <h4>Gde nas možete pronaći</h4>
          <p>Adresa: Vojvode Stepe 283</p>
          <a href="mailto:office@viser.edu.rs"><p>Email: office@viser.edu.rs</p></a>
          <a href="tel:+381112471099"><p>Telefon: +381 11 2471 099</p></a>
          <a href="rezervacija.php">Rezervacija</a>
        </div>
      </div>
    </div>
    <div class="footer-social">
      <div class="social-links">
        <a href="https://www.instagram.com/viserbgd" target="_blank" class="ig text-white"><i class="fa-brands fa-instagram"></i></a>
        <a href="https://www.facebook.com/viserbgd/?locale=sr_RS" target="_blank" class="f text-white"><i class="fa-brands fa-facebook"></i></a>
        <a href="https://www.youtube.com/@avt_viser_atuss" target="_blank" class="y text-white"><i class="fa-brands fa-youtube"></i></a>
        <a href="https://www.linkedin.com/school/viser-belgrade/" target="_blank" class="i text-white"><i class="fa-brands fa-linkedin"></i></a>
      </div>
    </div>
    <div class="payment-options">
      <img src="./slike/payment.png" alt="Opcije placanja">
    </div>
  </footer>
  <div class="copyright bg-dark border-top border-secondary text-white py-3">
    <div class="container d-flex justify-content-between">
      <div>
        <span><a href="index.php" style="text-decoration: none; color: #fff;">BoVoyage</a>, <i class="fa-solid fa-copyright"></i> Sva prava zadržana.</span>
      </div>
      <div>Designed By NRT14623</div>
    </div>
  </div>
  <script src="./js/bootstrap.bundle.min.js"></script>
  <script src="./fontawesome-free-6.7.2-web/js/all.min.js"></script>
  </body>
</html>