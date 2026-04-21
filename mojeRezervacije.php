<?php
session_start();
if(!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$korisnik_id = $_SESSION['id'];
$mysqli = new mysqli("localhost","root","","bovoyage_db");
if($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

// Dohvati sve rezervacije korisnika sa podacima o destinacijama
$sql = "SELECT r.id AS rez_id, r.broj_osoba, r.status, r.datum_rezervacije,
        d.naziv AS destinacija, d.cena, d.datum_polaska, d.trajanje, d.slika
        FROM rezervacije r
        JOIN destinacije d ON r.destinacija_id = d.id
        WHERE r.korisnik_id = ?
        ORDER BY r.datum_rezervacije DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i",$korisnik_id);
$stmt->execute();
$rezervacije = $stmt->get_result();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Moje rezervacije - BoVoyage</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/stil.css">
</head>
<body>
<?php include 'header.php'; ?>

<section class="container py-5">
    <h2 class="display-5 text-center mb-4">Moje rezervacije</h2>
    <div class="row g-4">
      <?php if($rezervacije->num_rows>0): ?>
        <?php while($r = $rezervacije->fetch_assoc()): ?>
          <div class="col-12 col-md-6 col-lg-4">
            <div class="card shadow-sm">
              <img src="./slike/<?php echo $r['slika']; ?>" class="card-img-top" style="height:200px;object-fit:cover;">
              <div class="card-body">
                <h5 class="card-title"><?php echo $r['destinacija']; ?></h5>
                <p>Datum polaska: <?php echo date("d.m.Y", strtotime($r['datum_polaska'])); ?></p>
                <p>Broj osoba: <?php echo $r['broj_osoba']; ?></p>
                <p>Status: <?php echo ucfirst($r['status']); ?></p>
                <?php 
        // za valutu
        $valuta = ($r['cena'] < 1000) ? "EUR" : "RSD";
        $osiguranje = ($valuta == "EUR") ? 2 : 210; 
        $ukupno = ($r['cena'] * $r['broj_osoba']) + ($osiguranje * $r['broj_osoba']);
    ?>
    <p>Ukupno: <strong><?php echo number_format($ukupno, 0, ",", "."); ?> <?php echo $valuta; ?></strong></p>
                <a href="stampajRezervaciju.php?id=<?php echo $r['rez_id']; ?>" target="_blank" class="btn btn-sm btn-primary">Štampa</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">Nemate nijednu rezervaciju.</p>
      <?php endif; ?>
    </div>
</section>

 <!-- footer-start -->
  <footer>
    <div class="footer-container">
    <div class="footer-left">
    <a href="index.php">
    <h1>BoVoyage</h1>
    <p>~Putuj pametno~</p>
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
      <a href="tel:+381112471099 "><p>Telefon: +381 11 2471 099 </p></a>
      <a href="rezervacija.php">Rezervacija</a>
    </div>
    </div>
    </div>
    <div class="footer-social">
      <div class="social-links">
      <a href="https://www.instagram.com/viserbgd" target="_blank" class="ig"><i class="fa-brands fa-instagram"></i></a>
      <a href="https://www.facebook.com/viserbgd/?locale=sr_RS" target="_blank" class="f"><i class="fa-brands fa-facebook"></i></a>
      <a href="https://www.youtube.com/@avt_viser_atuss" target="_blank" class="y"><i class="fa-brands fa-youtube"></i></a>
      <a href="https://www.linkedin.com/school/viser-belgrade/" target="_blank" class="i"><i class="fa-brands fa-linkedin"></i></a>
      </div>
      </div>
    <div class="payment-options">
      <img src="./slike/payment.png" alt="Opcije placanja">
    </div>
    </div>
    </div>
  </footer>
  <!-- footer-end -->
 
 
  <!-- copyright-start -->
  <div class="copyright" >
  <div class="copyright-content">
  <div>
  <div>
  <span><a href="index.php" style="text-decoration: none; color: #000;">BoVoyage</a>, <i class="fa-solid fa-copyright"></i> Sva prava zadrzava.</span>
  </div>
  <div>
  Designed By NRT14623
  </div>
  </div>
  </div>
  </div>
  <!-- copyright-end -->



    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./fontawesome-free-6.7.2-web/js/all.min.js"></script>
  </body>
</html>