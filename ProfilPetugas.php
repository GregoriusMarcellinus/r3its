<?php

session_start();

if (isset($_SESSION["pengelolah_id"])) {

	$mysqli = require __DIR__ . "/database.php";

	$sql = "SELECT * FROM tb_pengelolah
            WHERE id_pengelolah = {$_SESSION["pengelolah_id"]}";

	$result = $mysqli->query($sql);

	$pengelolah = $result->fetch_assoc();
	$hari 		= mysqli_query($mysqli, "SELECT hari FROM sampah_harian order by id asc");
	$sampah     = mysqli_query($mysqli, "SELECT jumlah_sampah FROM sampah_harian order by id asc");
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
				<li>
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
				<li class="active">
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
				<img src="./img/logoITS.png" alt="logo" class="switch-mode">
				<img src="./img/logoKementrian.png" alt="logo" class="switch-mode">
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
								<a class="active" href="#">Profil Pengelolah</a>
							</li>
						</ul>
					</div>
				</div>

				<ul class="box-profil">
					<li>
						<i class='bx bx-user'></i>
						<span class="text">
							<h1>Akun Pengelola</h1>
							<p><?= htmlspecialchars($pengelolah["username"]) ?></p>
						</span>
					</li>
				</ul>

				<ul class="box-profil">
					<li>
						<div class="informasi">
							<div class="head-profil">
								<h1>Informasi Pengelola</h1>
							</div>
							<h5>Alamat</h5>
							<p><?= htmlspecialchars($pengelolah["alamat"]) ?>
							<p>
							<h5>Tempat, tanggal, dan lahir</h5>
							<p><?= htmlspecialchars($pengelolah["TTL"]) ?>
							<p>
							<h5>NIK</h5>
							<p><?= htmlspecialchars($pengelolah["nik"]) ?>
							<p>
						</div>
					</li>
				</ul>
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