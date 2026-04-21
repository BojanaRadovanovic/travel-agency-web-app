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
    <title>BoVoyage - Izleti Evropa</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/stil.css">
    <link rel="stylesheet" href="./fontawesome-free-6.7.2-web/css/all.min.css">
  </head>
  <body>
<div class="top-logo">
  <img src="./slike/logo1.png" alt="BoVoyage glavni logo">
</div>

<?php
require_once "db.php";
include "header.php"; // Ukljucujemo navigaciju

// Proveravamo da li je ID prosleđen u URL-u
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Izvlacimo detalje samo za tu destinaciju
    $stmt = $conn->prepare("SELECT * FROM destinacije WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $rezultat = $stmt->get_result();
    
    if ($row = $rezultat->fetch_assoc()) {
        $naziv = $row['naziv'];
        $opis = $row['opis']; 
        $cena = $row['cena'];
        $slika = $row['slika'];
        $datum = $row['datum_polaska'];
    } else {
        die("Destinacija nije pronađena.");
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6">
            <img src="./slike/<?php echo $slika; ?>" class="img-fluid rounded shadow" alt="<?php echo $naziv; ?>">
        </div>
        <div class="col-md-6">
            <h1 class="display-4"><?php echo $naziv; ?></h1>
            <p class="text-muted"><i class="fa-solid fa-calendar-days"></i> Polazak: <?php echo date("d.m.Y", strtotime($datum)); ?></p>
            <hr>
            <h3>Opis putovanja:</h3>
            <p class="lead"><?php echo $opis; ?></p>
            
            <div class="bg-light p-4 rounded border mt-4">
                <h4 class="mb-3">Cena: <strong><?php echo number_format($cena, 0, ',', '.'); ?> <?php echo ($cena > 1000 ? "RSD" : "EUR"); ?></strong></h4>
                <a href="rezervacija.php?destinacija_id=<?php echo $id; ?>" class="btn btn-primary btn-lg w-100">
                    Rezerviši odmah
                </a>
            </div>
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