<?php
session_start();
require_once "db.php";

// Definisemo putanju do tekstualnog fajla za logovanje (Zahtev 10)
$log_fajl = "log.txt";

if(isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $lozinka = $_POST['lozinka'];
    
    // Belezimo trenutno vreme i IP adresu za log fajl
    $vreme = date("d-m-Y H:i:s");
    $ip_adresa = $_SERVER['REMOTE_ADDR'];

    $stmt = $conn->prepare("SELECT id, ime, uloga, lozinka FROM korisnici WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1) {

        $korisnik = $result->fetch_assoc();

        if(password_verify($lozinka, $korisnik['lozinka'])) {

            $_SESSION['id'] = $korisnik['id'];
            $_SESSION['ime'] = $korisnik['ime'];
            $_SESSION['uloga'] = $korisnik['uloga'];

            // UPIS U LOG FAJL (Zahtev 10) - Uspesna prijava
            $log_poruka = "[$vreme] USPESNA PRIJAVA: Korisnik {$korisnik['ime']} ($email) sa IP: $ip_adresa\n";
            file_put_contents($log_fajl, $log_poruka, FILE_APPEND);

            // KOLACICI (Zahtev 5) - "Zapamti me"
            if(isset($_POST['zapamti_me'])) {
                // cuvamo kolacic 30 dana (86400 sekundi * 30)
                setcookie("zapamcen_email", $email, time() + (86400 * 30), "/");
            } else {
                // Ako korisnik odznaci polje, brisemo kolacic
                setcookie("zapamcen_email", "", time() - 3600, "/");
            }

            header("Location: index.php");
            exit();

        } else {
            $greska = "Pogrešna lozinka!";
            // UPIS U LOG FAJL - Neuspesna prijava (pogresna lozinka)
            $log_poruka = "[$vreme] NEUSPESNA PRIJAVA (Pogresna lozinka): Pokusaj za email $email sa IP: $ip_adresa\n";
            file_put_contents($log_fajl, $log_poruka, FILE_APPEND);
        }

    } else {
        $greska = "Korisnik sa tim emailom ne postoji!";
        // UPIS U LOG FAJL - Neuspesna prijava (nepostojeci email)
        $log_poruka = "[$vreme] NEUSPESNA PRIJAVA (Nepostojeci email): Pokusaj za email $email sa IP: $ip_adresa\n";
        file_put_contents($log_fajl, $log_poruka, FILE_APPEND);
    }

    $stmt->close();
}

// Proveravamo da li postoji sacuvan kolacic za email kako bismo ga popunili u formi
$sacuvan_email = isset($_COOKIE['zapamcen_email']) ? $_COOKIE['zapamcen_email'] : "";
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

   <div class="container mt-4 mb-5">
    <?php
    if(isset($_GET['registracija']) && $_GET['registracija'] == 'uspesna') {
        echo "<div class='alert alert-success'>Registracija uspešna! Prijavite se.</div>";
    }

    if(isset($greska)) {
        echo "<div class='alert alert-danger'>$greska</div>";
    }
    if(isset($uspesno)) {
        echo "<div class='alert alert-success'>$uspesno</div>";
    }
    ?>

    <h2 class="display-4">Prijava korisnika</h2>
    <hr>
    
    <div class="row">
        <div class="col-md-6">
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label"><i class="fa-solid fa-envelope"></i> Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($sacuvan_email); ?>" placeholder="Unesite email" required>
                </div>
                <div class="mb-3">
                    <label for="lozinka" class="form-label"><i class="fa-solid fa-lock"></i> Lozinka</label>
                    <input type="password" class="form-control" id="lozinka" name="lozinka" placeholder="Unesite lozinku" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="zapamti_me" name="zapamti_me" <?php if(!empty($sacuvan_email)) echo "checked"; ?>>
                    <label class="form-check-label" for="zapamti_me">Zapamti me</label>
                </div>
                <button type="submit" name="login" class="btn btn-primary">Prijavi se</button>
            </form>
            <p class="mt-4">Nemate nalog? Registrujte se <a href="nalog.php">ovde</a>.</p>
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