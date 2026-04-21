<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);

// ----ZA STATISTIKU POSETA (Zahtev 11) ---
// Moramo se povezati sa bazom da bismo upisali posetu
if (!isset($conn)) {
    require_once "db.php"; 
}

// Upisujemo u bazu posetu (ako je prijavljen uzimamo ID, ako nije upisujemo NULL)
if (isset($conn)) {
    $korisnik_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
    $stmt_stat = $conn->prepare("INSERT INTO statistika_poseta (korisnik_id, stranica) VALUES (?, ?)");
    // 'is' znači integer i string
    $stmt_stat->bind_param("is", $korisnik_id, $current_page);
    $stmt_stat->execute();
    $stmt_stat->close();
}

// --- KOLACIC (Zahtev 5) ---
// Pamti vreme poslednje aktivnosti na sajtu
if (!headers_sent()) {
    setcookie("poslednja_aktivnost", date("d.m.Y H:i:s"), time() + (86400 * 7), "/"); // Traje 7 dana
}
?>

<?php
$promo_fajl = "promo.txt";
// Proveravamo da li fajl postoji i citamo njegov sadrzaj
if (file_exists($promo_fajl)) {
    $promo_tekst = file_get_contents($promo_fajl);
    
    // Ako tekst nije prazan, prikazujemo zuti baner
    if (!empty(trim($promo_tekst))) {
        echo '<div class="bg-warning text-dark text-center py-2 fw-bold shadow-sm" style="font-size: 1.1rem; width: 100%;">';
        echo '<i class="fa-solid fa-bullhorn fa-beat"></i> ' . htmlspecialchars($promo_tekst);
        echo '</div>';
    }
}
?>

<nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
  <div class="container-fluid">
   <a class="navbar-brand" href="index.php">
      <img src="./slike/logo.png" alt="BoVoyage Logo" style="width: 80px; height: 55px;">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">

      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link <?php if($current_page == 'index.php') echo 'active'; ?>" href="index.php">
            <i class="fa-solid fa-house"></i>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if($current_page == 'onama.php') echo 'active'; ?>" href="onama.php">
            O nama
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle 
          <?php if(in_array($current_page, ['izletiEvropa.php','IzletiSrbija.php','letovanje.php'])) echo 'active'; ?>"
          role="button" data-bs-toggle="dropdown">
            Putovanja
          </a>

          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item <?php if($current_page == 'izletiEvropa.php') echo 'active'; ?>" href="izletiEvropa.php">
                Izleti Evropa
              </a>
            </li>
            <li>
              <a class="dropdown-item <?php if($current_page == 'IzletiSrbija.php') echo 'active'; ?>" href="IzletiSrbija.php">
                Izleti Srbija
              </a>
            </li>
            <li>
              <a class="dropdown-item <?php if($current_page == 'letovanje.php') echo 'active'; ?>" href="letovanje.php">
                Letovanja
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link <?php if($current_page == 'galerija.php') echo 'active'; ?>" href="galerija.php">
            Galerija
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle 
          <?php if(in_array($current_page, ['first.php','last.php'])) echo 'active'; ?>"
          role="button" data-bs-toggle="dropdown">
            Specijalne ponude
          </a>

          <ul class="dropdown-menu">
            <li>
              <a class="dropdown-item <?php if($current_page == 'first.php') echo 'active'; ?>" href="first.php">
                First minute
              </a>
            </li>
            <li>
              <a class="dropdown-item <?php if($current_page == 'last.php') echo 'active'; ?>" href="last.php">
                Last minute
              </a>
            </li>
          </ul>
        </li>

      </ul>

      <form class="d-flex position-relative mx-lg-4 my-2 my-lg-0" onsubmit="return false;" style="width: 100%; max-width: 400px;">
        <input class="form-control me-2 border-primary w-100" type="search" id="live-search-input" placeholder="Pretraži destinacije..." autocomplete="off">
        <div id="search-results" class="list-group position-absolute w-100 shadow-lg" style="top: 110%; left: 0; z-index: 1050; display: none;"></div>
      </form>

      <ul class="navbar-nav mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link <?php if($current_page == 'rezervacija.php') echo 'active'; ?>" href="rezervacija.php">
            <i class="fa-solid fa-calendar-days"></i> Rezervacija
          </a>
        </li>

        <?php if(isset($_SESSION['id'])): ?>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown">
              <i class="fa-solid fa-user"></i>
              <?php echo htmlspecialchars($_SESSION['ime']); ?>
            </a>

            <ul class="dropdown-menu dropdown-menu-end">

              <?php if(isset($_SESSION['uloga']) && $_SESSION['uloga'] === 'admin'): ?>
                <li><a class="dropdown-item" href="admin.php">Admin panel</a></li>
                <li><hr class="dropdown-divider"></li>
              <?php endif; ?>

              <li>
                <a class="dropdown-item text-danger" href="logout.php">
                  <i class="fa-solid fa-right-from-bracket"></i> Odjava
                </a>
              </li>
            </ul>
          </li>

        <?php else: ?>

          <li class="nav-item">
            <a class="nav-link <?php if($current_page == 'login.php') echo 'active'; ?>" href="login.php">
              <i class="fa-solid fa-right-to-bracket"></i> Prijava
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link <?php if($current_page == 'nalog.php') echo 'active'; ?>" href="nalog.php">
              <i class="fa-solid fa-user-plus"></i> Registracija
            </a>
          </li>

        <?php endif; ?>

      </ul>

    </div>
  </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const searchInput = document.getElementById("live-search-input");
    const searchResults = document.getElementById("search-results");

    searchInput.addEventListener("keyup", function() {
        let query = this.value.trim();

        if (query.length >= 2) { // Pretrazuj tek kad unese bar 2 slova
            // Saljemo upit na nas php fajl u pozadini (Fetch API - AJAX)
            fetch("ajax_pretraga.php?q=" + encodeURIComponent(query))
                .then(response => response.text())
                .then(data => {
                    searchResults.innerHTML = data;
                    searchResults.style.display = "block"; // Prikazujemo padajuci meni
                });
        } else {
            searchResults.style.display = "none"; // Sakrij ako ima manje od 2 slova
            searchResults.innerHTML = "";
        }
    });

    // Sakrij rezultate kada korisnik klikne bilo gde van pretrage na ekranu
    document.addEventListener("click", function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.style.display = "none";
        }
    });
});
</script>