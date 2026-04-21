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
    <title>BoVoyage - Početna strana</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/stil.css">
    <link rel="stylesheet" href="./fontawesome-free-6.7.2-web/css/all.min.css">
  </head>
  <body>
<div class="top-logo">
  <img src="./slike/logo1.png" alt="BoVoyage glavni logo">
</div>

 <?php include 'header.php'; ?>


  <div class="my-4">
   <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" style="max-width: 800px; margin: auto;">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="./slike/Turska.avif" class="d-block w-100" alt="Turska" style="height: 450px; object-fit: cover;">
    </div>
    <div class="carousel-item">
      <img src="./slike/Grcka.avif" class="d-block w-100" alt="Grcka" style="height: 450px; object-fit: cover;">
    </div>
    <div class="carousel-item">
      <img src="./slike/Egipat.avif" class="d-block w-100" alt="Egipat" style="height: 450px; object-fit: cover;">
    </div>
    <div class="carousel-item">
      <img src="./slike/Spanija.avif" class="d-block w-100" alt="Spanija" style="height: 450px; object-fit: cover;">
    </div>
    <div class="carousel-item">
      <img src="./slike/Italija.avif" class="d-block w-100" alt="Italija" style="height: 450px; object-fit: cover;">
    </div>
    <div class="carousel-item">
      <img src="./slike/CrnaGora.avif" class="d-block w-100" alt="Crna Gora" style="height: 450px; object-fit: cover;">
    </div>
  </div>
 
 

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Prethodna</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Sledeća</span>
  </button>
</div>
 </div>

    <div class="naslov">
  <h1>
    <span>Putuj,</span>
    <span> istraži,</span>
    <span> doživi</span>
    <span> sa</span>
    <span> BoVoyage</span>
  </h1>
  <h3>~ Tvoje mesto za nove uspomene ~</h3>
  <hr>
</div>

<br>
<section>
  <div class="container"> 
    <div class="row">
      <div class="col-12 col-md-4 d-flex align-items-stretch">
        <div class="card h-100">
          <img src="./slike/BoVoyage.avif" class="card-img-top" alt="More" style="width: 100%; height: 300px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Zašto BoVoyage?</h5>
            <hr>
            <p class="card-text">
              <i><strong>Agencija BoVoyage</strong> nastala je sa ciljem da putovanja učinimo dostupnim svima, posebno mladima, studentima i svima koji žele da istraže svet, a pritom ne potroše previše. Verujemo da svako zaslužuje priliku da oseti duh novih destinacija, pronađe avanturu i stvori uspomene za ceo život.</i>
            </p>
            <a href="onama.php" class="btn btn-primary">O nama</a>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 d-flex align-items-stretch">
        <div class="card h-100">
          <img src="./slike/GrckaLeto.avif" class="card-img-top" alt="Grcka Letovanje" style="width: 100%; height: 300px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Doživite magiju Grčke</h5>
            <hr>
            <p class="card-text">
              Grčka je omiljena destinacija za preko <mark style="background: #00bbff8d;">17 miliona turista</mark> godišnje. Poznata je po prelepim plažama, bogatoj istoriji i živopisnoj kulturi. <strong>Posetite antičke ruševine, uživajte u ukusnoj hrani i otkrijte šarm šarmantnih gradova.</strong> Svako putovanje u Grčku donosi nezaboravne trenutke i autentično iskustvo Mediterana.
            </p>
            <a href="https://www.turizamiputovanja.com/top-10-turistickih-mesta-u-grckoj/" class="btn btn-primary">Saznaj više</a>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4 d-flex align-items-stretch">
        <div class="card h-100">
           <img src="./slike/TurskaLeto.avif" class="card-img-top" alt="TurskaRivijera" style="width: 100%; height: 300px; object-fit: cover;">
          <div class="card-body">
            <h5 class="card-title">Letovanje na Tirkiznoj obali</h5>
            <hr>
            <p class="card-text">
              <mark style="background: #00bbff8d;">Turska rivijera</mark> je idealna destinacija za letovanje sa prelepim plažama, toplim morem i bogatom istorijom. Najpoznatija letovališta kao što su Antalija, Bodrum i Marmaris nude savršen spoj odmora i avanture. <strong>Otkrijte antičke lokalitete i uživajte u prelepim pejzažima i kristalnoj vodi.</strong> Tirkizna obala je mesto koje ostavlja nezaboravan utisak.
            </p>
            <a href="https://www.maestral.co.rs/ponude/letovanje/turska/" class="btn btn-primary" >Saznaj više</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>


    <section class="py-4">
  <div class="container">
    <div class="row align-items-center">
      
      
      <div class="col-md-4 mb-3">
        <div class="card h-100 shadow">
          <img src="./slike/Krf.avif" class="card-img-top img-fluid" alt="Grčka" style="height: 100%; max-height: 450px; object-fit: cover;">
          <div class="card-body text-center">
            <h5 class="card-title"><strong>Preporuka dana: Ostrvo Krf</strong></h5>
             <button class="btn btn-primary" type="button" disabled style="background-color:#172c3a;border:none;">
             <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
             <time datetime="2025-07-20">20.07.2025.</time>
         </button>   
          </div>
        </div>
      </div>

      
      <div class="col-md-8">
        <div class="p-4 rounded shadow" style="background-color: white;">
          <p class="mb-3">
            Letovanje na <strong>jednom od najlepših grčkih ostrva - Krfu.</strong><br>
            Idealna destinacija za relaksaciju i istraživanje.
          </p>

          <p style="text-align: justify;">
            <i>
              Krf je jedno od najslikovitijih ostrva Jonskog mora. Poznat po zelenilu, kristalno čistom moru i brojnim istorijskim znamenitostima.
              Plaže su raznovrsne - od peščanih do stenovitih, a voda topla i tirkizna.<br><br>

              Letovanje na Krfu nudi kombinaciju odmora, zabave i kulture. Možete posetiti Stari grad Krf, tvrđave, palate, muzeje i tradicionalna sela.
              Hrana je izuzetna, bazirana na mediteranskoj kuhinji - masline, feta, riba i vino.<br><br>

              <strong>Ostrvo ima dušu  ovde ćete pronaći mir, osmeh lokalnog stanovništva i atmosferu koju ćete dugo pamtiti…</strong>
            </i>
          </p>

          <p>
            Letnji meseci su idealni za posetu Krfu - <u>jul i avgust su najtopliji</u>, sa puno sunca, festivala i živopisnog noćnog života.
            Ostrvo je pogodno i za porodična putovanja, parove, kao i solo avanturiste u potrazi za lepotom Mediterana.
          </p>
        </div>
      </div>
    </div>
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
