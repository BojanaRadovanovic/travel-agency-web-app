<?php
session_start();
require_once "db.php";

if(isset($_POST['register'])) {

    $ime = trim($_POST['ime']);
    $prezime = trim($_POST['prezime']);
    $email = trim($_POST['email']);
    $lozinka = $_POST['lozinka'];
    $ponovo = $_POST['ponovo-lozinka'];

   // Validacija emaila
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>Neispravan format email adrese!</div>";
    } elseif ($lozinka !== $ponovo) {
        echo "<div class='alert alert-danger'>Lozinke se ne poklapaju!</div>";
    } else {

        $provera = $conn->prepare("SELECT id FROM korisnici WHERE email = ?");
        $provera->bind_param("s", $email);
        $provera->execute();
        $provera->store_result();

        if($provera->num_rows > 0) {
            echo "<div class='alert alert-danger'>Email već postoji!</div>";
        } else {

            $hash = password_hash($lozinka, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO korisnici (ime, prezime, email, lozinka, uloga) VALUES (?, ?, ?, ?, 'korisnik')");
            $stmt->bind_param("ssss", $ime, $prezime, $email, $hash);

            if($stmt->execute()) {
                echo "<div class='alert alert-success'>Registracija uspešna!</div>";
            } else {
                echo "<div class='alert alert-danger'>Greška prilikom registracije.</div>";
            }

            $stmt->close();
        }

        $provera->close();
    }
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
    <title>BoVoyage - Nalog</title>
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
  <h2 class="display-4">Kreiranje naloga</h2>
  <hr>
  <form method="POST" action="">
    <div class="mb-3">
      <label for="ime" class="form-label"><i class="fa-solid fa-user"></i> Ime</label>
      <input type="text" class="form-control" id="ime" name="ime" placeholder="Unesite ime" required>
    </div>
    <div class="mb-3">
      <label for="prezime" class="form-label"><i class="fa-solid fa-user"></i> Prezime</label>
      <input type="text" class="form-control" id="prezime" name="prezime" placeholder="Unesite prezime" required>
    </div>
    <div class="mb-3">
      <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="Unesite email" required>
    </div>
    <div class="mb-3">
      <label for="lozinka" class="form-label"><i class="fa-solid fa-lock"></i> Lozinka</label>
      <input type="password" class="form-control" id="lozinka" name="lozinka" placeholder="Unesite lozinku" required>
    </div>
    <div class="mb-3">
      <label for="ponovo-lozinka" class="form-label"><i class="fa-solid fa-lock"></i> Ponovo unesite lozinku</label>
      <input type="password" class="form-control" id="ponovo-lozinka" name="ponovo-lozinka" placeholder="Ponovite lozinku" required>
    </div>
    <button type="submit" name="register" class="btn btn-primary">Registruj se</button>
  </form>
</div>
<br>


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

<script>
document.addEventListener("DOMContentLoaded", function () {
    const forma = document.querySelector("form");
    const lozinka = document.getElementById("lozinka");
    const ponovo = document.getElementById("ponovo-lozinka");

    forma.addEventListener("submit", function(e) {
        if (lozinka.value.length < 6) {
            alert("Lozinka mora imati najmanje 6 karaktera.");
            e.preventDefault();
            return;
        }

        if (lozinka.value !== ponovo.value) {
            alert("Lozinke se ne poklapaju.");
            e.preventDefault();
        }
    });
});
</script>