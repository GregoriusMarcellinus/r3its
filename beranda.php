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
							FROM sampah 
							WHERE waktu IS NOT NULL AND DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') = CURDATE()";

	$result_terakhir = $mysqli->query($query_terakhir);
	$data_terakhir = $result_terakhir->fetch_assoc();

	// Format data untuk digunakan dalam Chart JS
	$labels = ['Tanaman', 'Plastik', 'Organik', 'Logam & Kaca'];
	$data = [$data_terakhir['total_tanaman'], $data_terakhir['total_plastik'], $data_terakhir['total_organik'], $data_terakhir['total_logam_kaca']];


	$persen_kat = "SELECT 
    ROUND(SUM(tanaman)) AS total_tanaman,
    ROUND(SUM(plastik)) AS total_plastik,
    ROUND(SUM(organik))	 AS total_organik,
    ROUND(SUM(logam_kaca)) AS total_logam_kaca
							FROM total_sampah 
							WHERE waktu IS NOT NULL AND DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') <= CURDATE()";

	$result_terbesar = $mysqli->query($persen_kat);
	$persen_terbesar = $result_terbesar->fetch_assoc();


	//total hari ini
	$total_shari = "SELECT 
    ROUND(SUM(tanaman + plastik + organik + logam_kaca)) AS total_shari
    FROM sampah 
    WHERE waktu IS NOT NULL AND DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') = CURDATE()";
	$result_shari = $mysqli->query($total_shari);
	$data_shari = $result_shari->fetch_assoc();
	//total sebelumnya
	$total_sebelumnya = " SELECT 
        ROUND(SUM(tanaman + plastik + organik + logam_kaca)) AS total_sebelumnya
    FROM total_sampah 
    WHERE waktu IS NOT NULL 
        AND DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') <= CURDATE()
";

	$result_sebelumnya = $mysqli->query($total_sebelumnya);
	$data_sebelumnya = $result_sebelumnya->fetch_assoc();
	$total_sampah = $data_sebelumnya['total_sebelumnya'];

	$total_pengguna = mysqli_query($mysqli, "SELECT COUNT(DISTINCT nik) as total_pengguna FROM user WHERE LENGTH(nik) = 16");
}

//grafik perminggoh hwehwehe
if (isset($_GET['action']) && $_GET['action'] == 'ambilDataGrafik') {
	// Fungsi untuk mengubah nama hari Inggris menjadi Indonesia anjay
	function translateDay($englishDay)
	{
		$daysTranslation = [
			'Monday'    => 'Senin',
			'Tuesday'   => 'Selasa',
			'Wednesday' => 'Rabu',
			'Thursday'  => 'Kamis',
			'Friday'    => 'Jumat',
			'Saturday'  => 'Sabtu',
			'Sunday'    => 'Minggu',
		];

		return $daysTranslation[$englishDay] ?? $englishDay;
	}

	// Buat array dengan semua hari dari Senin hingga Minggu
	$daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

	// Ambil data untuk minggu ini
	$query_mingguan = "SELECT 
        DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%W') AS hari_minggu,
        ROUND(SUM(tanaman + plastik + organik + logam_kaca)) AS total_per_hari
    FROM sampah 
    WHERE waktu IS NOT NULL 
        AND WEEK(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), 1) = WEEK(CURDATE(), 1)
    GROUP BY hari_minggu";

	$result_mingguan = $mysqli->query($query_mingguan);

	$data = [
		'label' => array_map('translateDay', $daysOfWeek), // Menggunakan fungsi translateDay
		'data'  => array_fill(0, count($daysOfWeek), 0) // Isi array data dengan 0 untuk semua hari
	];

	while ($row = $result_mingguan->fetch_assoc()) {
		$index = array_search($row['hari_minggu'], $daysOfWeek);
		if ($index !== false) {
			$data['data'][$index] = $row['total_per_hari'];
		}
	}

	header('Content-Type: application/json');
	echo json_encode($data);
	exit; // Hentikan eksekusi lebih lanjut
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
	<link rel="icon" type="image/png" sizes="32x32" href="./img/favicon_io/favicon-32x32.png">
	<!-- My CSS -->
	<link rel="stylesheet" href="CSS/Webstyle.css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<!-- <script src="Chart1.js"></script> -->

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
				<li class="active">
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

			</nav>
			<!-- NAVBAR -->

			<!-- MAIN -->
			<main>
				<div class="head-title">
					<div class="left">
						<h1>Halo <span><?= htmlspecialchars($pengelolah["username"]) ?>, <span class="greeting"></span></span></h1>
						<ul class="breadcrumb">
							<li>
								<a href="#">Masuk</a>
							</li>
							<li><i class='bx bx-chevron-right'></i></li>
							<li>
								<a class="active" href="#">Beranda</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="table-data">
					<div class="order">
						<ul class="box-info">
							<li>
								<i class='bx bxs-group'></i>
								<span class="text">
									<?php
									while ($total = mysqli_fetch_array($total_pengguna)) { ?>
										<h3><?php echo $total['total_pengguna'] ?></h3>
									<?php } ?>
									<p>Pengguna Aplikasi</p>
								</span>
							</li>
							<li>
								<i class='bx bxs-trash'></i>
								<span class="text">
									<?php
									echo "<h3> " . $total_sampah . " kg</h3>";
									?>
									<p>Total sampah</p>
								</span>
							</li>
						</ul>
						<div class="head">
							<h3>Grafik jumlah total sampah</h3>
						</div>

						<canvas id="grafikSampahMingguan" width="600" height="400"></canvas>
					</div>
					<div class="todo">
						<div class="head">
							<h3>Hari ini</h3>
						</div>
						<ul class="iniapa">

							<li class="inidalah">
								<i class='bx bx-calendar'></i>
								<div class="datetime">
									<div class="time"></div>
									<div class="date"></div>
								</div>
							</li>
							<li class="terbesar">
								<?php
							if ($data_shari['total_shari'] != 0) {
								echo "<p>Total Sampah Hari Ini &nbsp;&nbsp;&nbsp;:</p><h4>" . $data_shari['total_shari'] . "kg</h4>";
							} else {
								echo "<p>Total Sampah Hari Ini:</p><h5>tidak ada sampah</h5>";
							}
								?>
							</li>
							<li class="terbesar">
								<?php
								if (isset($persen_terbesar)) {
									// Hitung total sampah untuk hari terakhir
									$total_daur_ulang =$persen_terbesar['total_logam_kaca'];
									$total_bricket = $persen_terbesar['total_tanaman'] + $persen_terbesar['total_plastik'];
									$total_kompos = $persen_terbesar['total_organik'];

									if ($total_daur_ulang + $total_bricket + $total_kompos != 0) {
										// Hitung persentase untuk setiap kategori sampah
										$persentase_daur_ulang = round(($total_daur_ulang / ($total_daur_ulang + $total_bricket + $total_kompos)) * 100);
										$persentase_bricket = round(($total_bricket / ($total_daur_ulang + $total_bricket + $total_kompos)) * 100);
										$persentase_kompos = round(($total_kompos / ($total_daur_ulang + $total_bricket + $total_kompos)) * 100);

										// Tentukan kategori sampah dengan persentase terbesar
										$kategori_terbesar = '';
										$persentase_terbesar = 0;

										if ($persentase_daur_ulang > $persentase_terbesar) {
											$kategori_terbesar = 'Daur Ulang';
											$persentase_terbesar = $persentase_daur_ulang;
										}

										if ($persentase_bricket > $persentase_terbesar) {
											$kategori_terbesar = 'Bricket';
											$persentase_terbesar = $persentase_bricket;
										}

										if ($persentase_kompos > $persentase_terbesar) {
											$kategori_terbesar = 'Kompos';
											$persentase_terbesar = $persentase_kompos;
										}

										echo "<h3>$kategori_terbesar</h3>";
										echo "<p>Persentase &nbsp;: &nbsp;$persentase_terbesar%</p>";
									}
								}
								?>
								<a id="btn-popup" href="#" class="brand">
									<i class='bx bx-chevron-down'></i>
								</a>
							</li>
							<canvas id="piechart"></canvas>
						</ul>
					</div>
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

<!-- bar grafik -->
<script type='text/javascript'>
	$(document).ready(function() {
		// Fungsi untuk mengambil dan memperbarui data grafik
		function perbaruiGrafik() {
			$.ajax({
				url: 'beranda.php?action=ambilDataGrafik',
				type: 'GET',
				dataType: 'json',
				success: function(data) {
					perbaruiGrafikDenganData(data);
				},
				error: function(error) {
					console.error('Error mengambil data:', error);
				}
			});
		}

		// Fungsi untuk memperbarui Grafik dengan data baru
		function perbaruiGrafikDenganData(data) {
			var ctx = document.getElementById('grafikSampahMingguan').getContext('2d');
			var grafik = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: data.label,
					datasets: [{
						label: 'Total Sampah per Hari',
						data: data.data,
						backgroundColor: ['#75BE67'],
						borderColor: ['#39A125'],
						borderWidth: 1,
						borderRadius: 10,
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		}

		// Inisialisasi awal
		perbaruiGrafik();

		// Tetapkan interval untuk memperbarui grafik setiap menit
		setInterval(perbaruiGrafik, 60000);
	});
</script>

<!-- donut grafik -->
<script type='text/javascript'>
	const cty = document.getElementById('piechart');

	new Chart(cty, {
		type: 'polarArea',
		data: {
			labels: <?php echo json_encode($labels); ?>,
			datasets: [{
				label: 'Jumlah sampah',
				data: <?php echo json_encode($data); ?>,

				backgroundColor: [
					'#4ACA7B',
					'#FF6836',
					'#DE0062',
					'#4DCCFF',
					'#A4E5FF',
				],
				barThickness: 2,
			}]
		},
		options: {
			scales: {
				responsive: true,
				y: {
					beginAtZero: true
				}
			}
		}
	});
</script>

</html>