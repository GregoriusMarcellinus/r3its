<?php

session_start();

function fetchData($result)
{
	$data = [];
	while ($q = mysqli_fetch_assoc($result)) {
		$data[] = [
			'tanggal' => $q['tanggal'],
			'total' => $q['total']
		];
	}
	return $data;
}

if (isset($_SESSION["pengelolah_id"])) {

	$mysqli = require __DIR__ . "/database.php";

	$sql = "SELECT * FROM tb_pengelolah
            WHERE id_pengelolah = {$_SESSION["pengelolah_id"]}";

	$result = $mysqli->query($sql);

	$pengelolah = $result->fetch_assoc();

	$tanaman = mysqli_query($mysqli, "SELECT DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%d-%m-%Y') as tanggal, ROUND(SUM(tanaman)) AS total 
                                   FROM sampah 
                                   WHERE waktu IS NOT NULL
                                   GROUP BY DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d')
                                   ORDER BY waktu ASC");
	$plastik = mysqli_query($mysqli, "SELECT DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%d-%m-%Y') as tanggal, ROUND(SUM(plastik)) AS total
                                   FROM sampah 
                                   WHERE waktu IS NOT NULL
                                   GROUP BY DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d')
                                   ORDER BY waktu ASC");
	$organik = mysqli_query($mysqli, "SELECT DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%d-%m-%Y') as tanggal, ROUND(SUM(organik)) AS total 
                                   FROM sampah 
                                   WHERE waktu IS NOT NULL
                                   GROUP BY DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d')
                                   ORDER BY waktu ASC");
	$logamkaca = mysqli_query($mysqli, "SELECT DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%d-%m-%Y') as tanggal, ROUND(SUM(logam_kaca)) AS total
                                   FROM sampah 
                                   WHERE waktu IS NOT NULL
                                   GROUP BY DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d')
                                   ORDER BY waktu ASC");

	$query_tanaman_terakhir = "SELECT ROUND(SUM(tanaman)) AS total_tanaman FROM total_sampah WHERE waktu IS NOT NULL AND DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') <= CURDATE()";
	$result_tanaman_terakhir = $mysqli->query($query_tanaman_terakhir);
	$row_tanaman_terakhir = $result_tanaman_terakhir->fetch_assoc();
	$tanaman_hari_terakhir = $row_tanaman_terakhir['total_tanaman'];

	$query_plastik_terakhir = "SELECT ROUND(SUM(plastik)) AS total_plastik FROM total_sampah WHERE waktu IS NOT NULL AND DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') <= CURDATE()";
	$result_plastik_terakhir = $mysqli->query($query_plastik_terakhir);
	$row_plastik_terakhir = $result_plastik_terakhir->fetch_assoc();
	$plastik_hari_terakhir = $row_plastik_terakhir['total_plastik'];

	$query_organik_terakhir = "SELECT ROUND(SUM(organik)) AS total_organik FROM total_sampah WHERE waktu IS NOT NULL AND DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') <= CURDATE()";
	$result_organik_terakhir = $mysqli->query($query_organik_terakhir);
	$row_organik_terakhir = $result_organik_terakhir->fetch_assoc();
	$organik_hari_terakhir = $row_organik_terakhir['total_organik'];

	$query_logam_kaca_terakhir = "SELECT ROUND(SUM(logam_kaca)) AS total_logam_kaca FROM total_sampah WHERE waktu IS NOT NULL AND DATE_FORMAT(STR_TO_DATE(waktu, '%d/%m/%Y %H:%i:%s'), '%Y-%m-%d') <= CURDATE()";
	$result_logam_kaca_terakhir = $mysqli->query($query_logam_kaca_terakhir);
	$row_logam_kaca_terakhir = $result_logam_kaca_terakhir->fetch_assoc();
	$logam_kaca_hari_terakhir = $row_logam_kaca_terakhir['total_logam_kaca'];

	if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ajax_request'])) {
		$data = array(
			'tanaman' => fetchData($tanaman),
			'plastik' => fetchData($plastik),
			'organik' => fetchData($organik),
			'logamkaca' => fetchData($logamkaca)
		);
		header('Content-Type: application/json');
		echo json_encode($data);
		exit;
	}
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
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
				<li class="active">
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
				<!-- <div class="jsampah_hari"> -->
				<ul class="box-sampah">
					<li>
						<div class="tanaman">
							<span>Tanaman Kering</span>
						</div>
						<div class="text">
							<div class="totalS">
								<span>Total Sampah : </span>
							</div>
							<h3><?php echo $tanaman_hari_terakhir; ?> kg</h3>
						</div>
					</li>
					<li>
						<div class="plastik">
							<span>Plastik</span>
						</div>
						<div class="text">
							<div class="totalS">
								<span>Total Sampah : </span>
							</div>
							<h3><?php echo $plastik_hari_terakhir; ?> kg</h3>
						</div>
					</li>

					<li>
						<div class="organik">
							<span>Organik</span>
						</div>
						<div class="text">
							<div class="totalS">
								<span>Total Sampah : </span>
							</div>
							<h3><?php echo $organik_hari_terakhir; ?> kg</h3>
						</div>
					</li>
					<li>
						<div class="logam">
							<span>Logam & Kaca</span>
						</div>
						<div class="text">
							<div class="totalS">
								<span>Total Sampah : </span>
							</div>
							<h3><?php echo $logam_kaca_hari_terakhir; ?> kg</h3>
						</div>
					</li>
				</ul>
				<!-- </div> -->

				<div class="table-data">
					<div class="order">
						<div class="head">
							<h3>Histori grafik komposisi sampah/Kg</h3>
						</div>
						<div id="chart"></div>
					</div>
				</div>

			</main>
			<!-- MAIN -->
		</section>
		<!-- CONTENT -->
		<script src="JS/script.js"></script>
		<script type='text/javascript'>
			$(document).ready(function() {
				var ajaxEndpoint = 'komposisiSampah.php?ajax_request=true';
				$.ajax({
					url: ajaxEndpoint,
					type: 'GET',
					dataType: 'json',
					success: function(data) {
						console.log(data);
						var options = {
							chart: {
								type: 'bar',
								height: 350,
								stacked: true,
							},
							responsive: [{
								breakpoint: 480,
								options: {
									legend: {
										position: 'bottom',
										offsetX: -10,
										offsetY: 0
									}
								}
							}],
							plotOptions: {
								bar: {
									horizontal: false,
									borderRadius: 10,
									dataLabels: {
										total: {
											enabled: true,
											style: {
												fontSize: '13px',
											}
										}
									}
								},
							},
							series: [{
									name: 'Tanaman Kering',
									data: data.tanaman.map(function(item) {
										return item.total;
									}),
								},
								{
									name: 'Plastik',
									data: data.plastik.map(function(item) {
										return item.total;
									}),
								},
								{
									name: 'Organik',
									data: data.organik.map(function(item) {
										return item.total;
									}),
								},
								{
									name: 'Logam & Kaca',
									data: data.logamkaca.map(function(item) {
										return item.total;
									}),
								}
							],
							xaxis: {
								categories: data.tanaman.map(function(item) {
									return item.tanggal;
								}),
							},
							colors: ['#4ACA7B', '#FF6836', '#DE0062', '#4DCCFF'],
							fill: {
								opacity: 1
							},
						};

						var chart = new ApexCharts(document.querySelector("#chart"), options);
						chart.render();
					},
					error: function(xhr, status, error) {
						console.error("AJAX Error:", status, error);
					}
				});
			});
		</script>
	<?php else :
		header("Location: index.html"); ?>
	<?php endif; ?>
</body>

</html>