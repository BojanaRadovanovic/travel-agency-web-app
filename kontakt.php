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
    <title>BoVoyage - Kontakt</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/stil.css">
    <link rel="stylesheet" href="./fontawesome-free-6.7.2-web/css/all.min.css">
  </head>
  <body>
  <div class="top-logo">
  <img src="./slike/logo1.png" alt="BoVoyage glavni logo">
</div>

  <?php include 'header.php'; ?>

    <div class="container">
  <h2 class="display-4 text-center text-primary">Kontaktirajte nas</h2>
  <hr>
  <?php if(isset($_SESSION['ime'])): ?>
      <div class="alert alert-info text-center shadow-sm" style="border-radius: 10px;">
          <h5 class="mb-0">
              <i class="fa-solid fa-hand-wave"></i> 
              Dobro došli, <strong><?php echo htmlspecialchars($_SESSION['ime']); ?></strong>! 
              Kako možemo da Vam pomognemo? Slobodno nam pišite.
          </h5>
      </div>
  <?php else: ?>
      <div class="alert alert-secondary text-center shadow-sm" style="border-radius: 10px;">
          <h5 class="mb-0">
              Dobro došli! Kako možemo da Vam pomognemo? Slobodno nam pišite. 
              <br><small><a href="login.php" class="text-decoration-none">Prijavite se</a> za bržu komunikaciju.</small>
          </h5>
      </div>
  <?php endif; ?>
  <div class="row">
    
    <div class="col-12 col-md-6">
      <form action="poruka.php" method="POST"> <div class="mb-3 position-relative">
    <label class="form-label">Ime i prezime</label>
    <div class="input-group">
      <span class="input-group-text bg-light text-primary"><i class="fa-solid fa-user"></i></span>
      <input type="text" name="ime" class="form-control" placeholder="Unesite ime i prezime" required>
    </div>
  </div>

  <div class="mb-3 position-relative">
    <label class="form-label">Email</label>
    <div class="input-group">
      <span class="input-group-text bg-light text-danger"><i class="fa-solid fa-envelope"></i></span>
      <input type="email" name="email" class="form-control" placeholder="Unesite email" required>
    </div>
  </div>

  <div class="mb-3 position-relative">
    <label class="form-label">Telefon</label>
    <div class="input-group">
      <span class="input-group-text bg-light text-success"><i class="fa-solid fa-phone"></i></span>
      <input type="tel" name="telefon" class="form-control" placeholder="Unesite broj telefona">
    </div>
  </div>

  <div class="mb-3 position-relative">
    <label class="form-label">Poruka</label>
    <div class="input-group">
      <span class="input-group-text bg-light text-warning"><i class="fa-solid fa-comment-dots"></i></span>
      <textarea name="poruka" class="form-control" rows="5" placeholder="Unesite poruku" required></textarea>
    </div>
  </div>

  <div class="d-grid gap-2">
    <button type="submit" name="posalji_kontakt" class="btn btn-primary">Pošalji</button>
  </div>
</form>
    </div>

    <!-- Mapa -->
    <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2832.6132005965187!2d20.47745667623633!3d44.76830267107114!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x475a70f8df8b60e1%3A0xd12e2aad5590e385!2z0JLQuNGB0L7QutCwINGI0LrQvtC70LAg0LXQu9C10LrRgtGA0L7RgtC10YXQvdC40LrQtSDQuCDRgNCw0YfRg9C90LDRgNGB0YLQstCwINGB0YLRgNGD0LrQvtCy0L3QuNGFINGB0YLRg9C00LjRmNCwINCR0LXQvtCz0YDQsNC0!5e0!3m2!1ssr!2srs!4v1734817864121!5m2!1ssr!2srs" width="100%" height="450" style="border: 3px solid #e3e3e3; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1);" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
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
