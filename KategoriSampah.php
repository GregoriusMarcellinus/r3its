<?php

session_start();

if (isset($_SESSION["pengelolah_id"])) {

	$mysqli = require __DIR__ . "/database.php";

	$sql = "SELECT * FROM tb_pengelolah
            WHERE id_pengelolah = {$_SESSION["pengelolah_id"]}";

	$result = $mysqli->query($sql);

	$pengelolah = $result->fetch_assoc();

	$query_terakhir = "SELECT 
    ROUND(SUM(tanaman)) AS total_tanaman,
    ROUND(SUM(plastik)) AS total_plastik,
    ROUND(SUM(organik))	 AS total_organik,
    ROUND(SUM(logam_kaca)) AS total_logam_kaca
							FROM total_sampah 
							WHERE waktu IS NOT NULL AND DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') <= CURDATE()";

	$result_terakhir = $mysqli->query($query_terakhir);
	$data_terakhir = $result_terakhir->fetch_assoc();

	// Format data untuk digunakan dalam Chart JS
	$labels = ['Tanaman', 'Plastik', 'Organik', 'Logam & Kaca'];
	$data = [$data_terakhir['total_tanaman'], $data_terakhir['total_plastik'], $data_terakhir['total_organik'], $data_terakhir['total_logam_kaca']];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" type="image/png" sizes="32x32" href="./img/favicon_io/favicon-32x32.png">
	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

	<!-- My CSS -->
	<link rel="stylesheet" href="CSS/Webstyle.css">
	<script src="Chart1.js"></script>

	<title>App Pengelolahan Sampah</title>
</head>

<body>
	<?php if (isset($pengelolah)) : ?>
		<!-- SIDEBAR -->
		<section id="sidebar">
			<a href="#" class="brand">
				<i class="ri-admin-fill"></i>
				<span class="text">Pengelola</span>
			</a>

			<a href="#" class="datetime">
				<i class='bx bx-time-five bx-tada bx-rotate-90'></i>
				<span id="timeSpan"></span>
			</a>
			<ul class="side-menu top">
				<li>
					<a href="beranda.php">
						<i class='bx bx-home-alt'></i>
						<span class="text">Beranda</span>
					</a>
				</li>
				<li class="active">
					<a href="KategoriSampah.php">
						<i class='bx bx-category-alt'></i>
						<span class="text">Kategori Sampah</span>
					</a>
				</li>
				<li>
					<a href="komposisiSampah.php">
						<i class='bx bxs-doughnut-chart'></i>
						<span class="text">Komposisi Sampah</span>
					</a>
				</li>
				<li>
					<a href="datapengguna.php">
						<i class='bx bx-data'></i>
						<span class="text">Data Pengguna</span>
					</a>
				</li>
				<li>
					<a href="ProfilPetugas.php">
						<i class='bx bxs-user-detail'></i>
						<span class="text">Profil Pengelola</span>
					</a>
				</li>
			</ul>
			<ul class="side-menu">
				<li>
					<a href="WargaDesa.php" class="daftar">
						<i class='bx bxs-user-plus'></i>
						<span class="daftar">Daftar</span>
					</a>
				</li>
				<li>
					<a href="logout.php" class="keluar">
						<i class='bx bxs-log-out-circle'></i>
						<span class="text">Keluar</span>
					</a>
				</li>
			</ul>
		</section>
		<!-- SIDEBAR -->
		<!-- CONTENT -->
		<section id="content">
			<!-- NAVBAR -->
			<nav>
				<i class='bx bx-menu'></i>
				<form action="#">
					<div class="form-input">
						<input type="search" placeholder="Cari...">
						<button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
					</div>
				</form>
				<input type="checkbox" id="switch-mode" hidden>
				<img src="./img/r3its.png" alt="logo" class="switch-mode">
				<img src="./img/logoKementrian.png" alt="logo" class="switch-mode">
				<img src="./img/logoITS.png" alt="logo" class="switch-mode">
				<!-- <label for="switch-mode" class="switch-mode"></label> -->

			</nav>
			<!-- NAVBAR -->

			<!-- MAIN -->
			<main>
				<div class="head-title">
					<div class="left">
						<ul class="breadcrumb">
							<li>
								<a href="#">Beranda</a>
							</li>
							<li><i class='bx bx-chevron-right'></i></li>
							<li>
								<a class="active" href="#">Komposisi Sampah</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="order">
					<ul class="box-kategori">
						<?php
						$total_daur_ulang = 0;
						$total_bricket = 0;
						$total_kompos = 0;
						$persentase_daur_ulang = 0;
						$persentase_bricket = 0;
						$persentase_kompos = 0;

						if (isset($data_terakhir)) {
							//daur_ulang
							if (isset($_POST['submit_logam_kaca'])) {
								$input_value = isset($_POST['input_logam_kaca']) ? $_POST['input_logam_kaca'] : 0;
								$input_value = -$input_value;
								$query_insert_tanaman = "INSERT INTO total_sampah (logam_kaca, waktu) VALUES ($input_value, DATE_FORMAT(NOW(), '%d/%m/%Y %H:%i:%s'))";
								$mysqli->query($query_insert_tanaman);
								header("Location: KategoriSampah.php");
								exit();
							}
							//bricket
							if (isset($_POST['submit_tanaman'])) {
								$input_value = isset($_POST['input_tanaman']) ? $_POST['input_tanaman'] : 0;
								$input_value = -$input_value;
								$query_insert_tanaman = "INSERT INTO total_sampah (tanaman, waktu) VALUES ($input_value, DATE_FORMAT(NOW(), '%d/%m/%Y %H:%i:%s'))";
								$mysqli->query($query_insert_tanaman);
								header("Location: KategoriSampah.php");
								exit();
							}
							if (isset($_POST['submit_plastik'])) {
								$input_value = isset($_POST['input_plastik']) ? $_POST['input_plastik'] : 0;
								$input_value = -$input_value;
								$query_insert_tanaman = "INSERT INTO total_sampah (plastik, waktu) VALUES ($input_value, DATE_FORMAT(NOW(), '%d/%m/%Y %H:%i:%s'))";
								$mysqli->query($query_insert_tanaman);
								header("Location: KategoriSampah.php");
								exit();
							}
							//kompos
							if (isset($_POST['submit_organik'])) {
								$input_value = isset($_POST['input_organik']) ? $_POST['input_organik'] : 0;
								$input_value = -$input_value;
								$query_insert_tanaman = "INSERT INTO total_sampah (organik, waktu) VALUES ($input_value, DATE_FORMAT(NOW(), '%d/%m/%Y %H:%i:%s'))";
								$mysqli->query($query_insert_tanaman);
								header("Location: KategoriSampah.php");
								exit();
							}

							$total_daur_ulang = $data_terakhir['total_logam_kaca'];
							$total_bricket = $data_terakhir['total_tanaman'] + $data_terakhir['total_plastik'];
							$total_kompos = $data_terakhir['total_organik'];

							if ($total_daur_ulang + $total_bricket + $total_kompos != 0) {
								$persentase_daur_ulang = round(($total_daur_ulang / ($total_daur_ulang + $total_bricket + $total_kompos)) * 100);
								$persentase_bricket = round(($total_bricket / ($total_daur_ulang + $total_bricket + $total_kompos)) * 100);
								$persentase_kompos = round(($total_kompos / ($total_daur_ulang + $total_bricket + $total_kompos)) * 100);
							}
						}
						?>
						<li>
							<div class="konten-kat">
								<div class="kat-atas">
									<div class="output-kat">
										<span>Bricket</span>
										<h3><?php echo $persentase_bricket; ?> % </h3>
										<p>Total : <?php echo $total_bricket; ?> Kg </p>
									</div>
								</div>
								<div class="kat-bawah">
									<div class="kotak-input">
										<p>Tanaman Kering : <?php echo $data_terakhir['total_tanaman']; ?> Kg </p>
										<form class="ambil_nilai" method="post">
											<input type="number" name="input_tanaman" required>
											<button type="submit" name="submit_tanaman">Ambil </button>
										</form>
									</div>
									<div class="kotak-input">
										<p> Plastik : <?php echo $data_terakhir['total_plastik']; ?> Kg</p>
										<form class="ambil_nilai" method="post">
											<input type="number" name="input_plastik" required>
											<button type="submit" name="submit_plastik">Ambil </button>
										</form>
									</div>
								</div>
							</div>
						</li>
						<ul class="box-kategori-2">
							<li>
								<div class="konten-kat">
									<div class="kat-atas">
										<div class="output-kat">
											<span>Daur ulang</span>
											<h3><?php echo $persentase_daur_ulang; ?> % </h3>
											<p>Total :<?php echo $total_daur_ulang; ?> Kg </p>
										</div>
									</div>
									<div class="kat-bawah">
										<div class="kotak-input">
											<p>Logam & Kaca</p>
											<form class="ambil_nilai" method="post">
												<input type="number" name="input_logam_kaca" required>
												<button type="submit" name="submit_logam_kaca">Ambil </button>
											</form>
										</div>
									</div>
							</li>

							<li>
								<div class="konten-kat">
									<div class="kat-atas">
										<div class="output-kat">
											<span>Kompos</span>
											<h3><?php echo $persentase_kompos; ?> % </h3>
											<p>Total : <?php echo $total_kompos; ?> Kg </p>
										</div>
									</div>
									<div class="kat-bawah">
										<div class="kotak-input">
											<p>Organik </p>
											<form class="ambil_nilai" method="post">
												<input type="number" name="input_organik" required>
												<button type="submit" name="submit_organik">Ambil </button>
											</form>
										</div>
									</div>
							</li>
						</ul>
					</ul>

					</ul>
				</div>
			</main>
			<!-- MAIN -->
		</section>
		<!-- CONTENT -->
		<script src="JS/script.js"></script>
	<?php else :
		header("Location: index.html"); ?>
	<?php endif; ?>
</body>

</html>