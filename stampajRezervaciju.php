<?php
session_start();
if(!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$korisnik_id = $_SESSION['id'];
$mysqli = new mysqli("localhost","root","","bovoyage_db");
if($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);

// Provera da li je prosledjen ID rezervacije
if(!isset($_GET['id'])) {
    echo "Nije prosleđen ID rezervacije.";
    exit();
}

$rez_id = intval($_GET['id']);

// Dohvati rezervaciju i podatke o destinaciji
$sql = "SELECT r.id AS rez_id, r.broj_osoba, r.status, r.datum_rezervacije,
        d.naziv AS destinacija, d.cena, d.datum_polaska, d.trajanje, d.slika
        FROM rezervacije r
        JOIN destinacije d ON r.destinacija_id = d.id
        WHERE r.id = ? AND r.korisnik_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ii",$rez_id,$korisnik_id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows==0) {
    echo "Rezervacija nije pronađena.";
    exit();
}

$r = $res->fetch_assoc();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Štampa rezervacije - BoVoyage</title>
    <link href="./css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 40px; background-color: #fff; font-family: Arial, sans-serif; }
        .rez-card { max-width: 600px; margin: 0 auto; border: 1px solid #ccc; padding: 25px; border-radius: 12px; }
        .rez-card img { width: 100%; border-radius: 8px; margin-bottom: 15px; }
        .btn-print { margin-top: 20px; }
    </style>
</head>
<body>

<div class="rez-card">
    <h2 class="text-center mb-4">Rezervacija</h2>
    <img src="./slike/<?php echo $r['slika']; ?>" alt="<?php echo $r['destinacija']; ?>">
    
    <p><strong>Destinacija:</strong> <?php echo $r['destinacija']; ?></p>
    <p><strong>Datum polaska:</strong> <?php echo date("d.m.Y", strtotime($r['datum_polaska'])); ?></p>
    <p><strong>Broj osoba:</strong> <?php echo $r['broj_osoba']; ?></p>
    <p><strong>Status rezervacije:</strong> <?php echo ucfirst($r['status']); ?></p>
   <hr>
    <p><strong>Troškovi:</strong></p>
    <?php 
        //za valutu
        $valuta = ($r['cena'] < 1000) ? "EUR" : "RSD";
        $osiguranje = ($valuta == "EUR") ? 2 : 210; // 2 evra ili 210 dinara
        $ukupno = ($r['cena'] * $r['broj_osoba']) + ($osiguranje * $r['broj_osoba']);
    ?>
    <ul>
        <li>Aranžman po osobi: <?php echo number_format($r['cena'], 0, ",", "."); ?> <?php echo $valuta; ?></li>
        <li>Osiguranje po osobi: <?php echo $osiguranje; ?> <?php echo $valuta; ?></li>
        <li><strong>Ukupno: <?php echo number_format($ukupno, 0, ",", "."); ?> <?php echo $valuta; ?></strong></li>
    </ul>

    <p><strong>Datum rezervacije:</strong> <?php echo date("d.m.Y H:i", strtotime($r['datum_rezervacije'])); ?></p>

    <button onclick="window.print()" class="btn btn-primary btn-print w-100">Štampa</button>
</div>

</body>
</html>


