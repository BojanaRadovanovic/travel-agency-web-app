<?php
require_once "db.php"; // Povezivanje sa bazom

$uspeh = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['posalji_kontakt'])) {
    $ime = htmlspecialchars($_POST['ime']);
    $email = htmlspecialchars($_POST['email']);
    $telefon = htmlspecialchars($_POST['telefon']);
    $poruka = htmlspecialchars($_POST['poruka']);

    // SQL upit za unos u bazu
    $stmt = $conn->prepare("INSERT INTO kontakt_poruke (ime, email, telefon, poruka) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $ime, $email, $telefon, $poruka);

    if ($stmt->execute()) {
        $uspeh = true;
    }
    $stmt->close();
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Na sajtu BoVoyage možete 
    pronaći inspiraciju za putovanja, ponude aranžmana, 
    savete za avanture i rezervaciju vašeg sledećeg 
    odmora iz snova.">
    <meta name="keywords" content="Putovanja, Avantura, Odmor, Rezervacije, Destinacije">
    <meta name="author" content="Bojana Radovanovic, bojananrt14623@gs.viser.edu.rs">
    <title>BoVoyage - Poruka</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/stil.css">
    <link rel="stylesheet" href="./fontawesome-free-6.7.2-web/css/all.min.css">
  </head>
  <body>
<div class="top-logo">
  <img src="./slike/logo1.png" alt="BoVoyage glavni logo">
</div>

   <?php include 'header.php'; ?>

 <div class="container mt-4">
  <h2 class="display-4 text-center mb-4">Obaveštenje</h2>
  <hr>
  <div class="row justify-content-center">
    <div class="col-12 col-md-8">
      <?php if ($uspeh): ?>
        <div class="alert alert-success text-center" role="alert">
          <h4 class="alert-heading">Uspešno poslato!</h4>
          <p>Hvala vam, <strong><?php echo $ime; ?></strong>. Vaša poruka je primljena i uskoro ćemo vam odgovoriti na <strong><?php echo $email; ?></strong>.</p>
          <hr>
          <p class="mb-0">Za nastavak posetite <a href="index.php" class="alert-link">početnu stranu</a>.</p>
        </div>
      <?php else: ?>
        <div class="alert alert-danger text-center" role="alert">
          <h4 class="alert-heading">Greška!</h4>
          <p>Nažalost, došlo je do problema prilikom slanja poruke. Molimo pokušajte ponovo kasnije.</p>
          <hr>
          <p class="mb-0"><a href="kontakt.php" class="alert-link">Vratite se na kontakt formu</a>.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>


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
