<?php

session_start();

if (isset($_SESSION["pengelolah_id"])) {

	$mysqli = require __DIR__ . "/database.php";

	$sql = "SELECT * FROM tb_pengelolah
            WHERE id_pengelolah = {$_SESSION["pengelolah_id"]}";

	$result = $mysqli->query($sql);

	$pengelolah = $result->fetch_assoc();

	$kmsampah = mysqli_query($mysqli, "SELECT DISTINCT user.*, sampah.*, DATE_FORMAT(STR_TO_DATE(sampah.waktu, '%d/%m/%Y %H:%i:%s'), '%d-%m-%Y') AS formatwaktu FROM sampah INNER JOIN user ON sampah.nik = user.nik ORDER BY id_user ASC");

	$datapengguna = mysqli_query($mysqli, "SELECT * FROM user WHERE LENGTH(nik) = 16 GROUP BY nik ORDER BY id_user ASC");
	if (isset($_POST['verifikasi_akun'])) {
		$nikToVerify = mysqli_real_escape_string($mysqli, $_POST['nik_to_verify']);

		$updateQuery = "UPDATE user SET verifikasi_akun = 1 WHERE nik = '$nikToVerify'";
		$updateResult = $mysqli->query($updateQuery);

		if ($updateResult) {
			echo json_encode(['success' => true]);
			exit;
		} else {
			echo json_encode(['success' => false, 'error' => $mysqli->error]);
			exit;
		}
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
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

	<!-- My CSS -->
	<link rel="stylesheet" href="CSS/Webstyle.css">
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
				<li class="active">
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
				<form action="" method="post">
					<div class="form-input">
						<input type="search" name="keyword" size="40" autofocus placeholder="Cari..." autocomplete="off">
						<button type="submit" name="cari" class="search-btn"><i class='bx bx-search'></i></button>
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
								<a class="active" href="#">Data Pengguna</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="table-data">
					<div class="order">
						<div class="head">
							<h3>Data verifikasi pengguna</h3>
							<i class='bx bx-chevron-down'></i>
						</div>
						<table>
							<thead>
								<tr>
									<th>NIK</th>
									<th>Nama</th>
									<th>Email</th>
									<th>Telepon</th>
									<th>Verifikasi Akun</th>
								</tr>
							</thead>
							<tbody>
								<?php
								while ($row = mysqli_fetch_array($datapengguna)) {
								?>
									<tr>
										<td><?php echo $row['nik'] ?></td>
										<td><?php echo $row['nama_kepala'] ?></td>
										<td><?php echo $row['email'] ?>
											<?php if ($row['verifikasi_email'] == 1) : ?>
												<i class='bx bxs-check-circle'></i>
											<?php else : ?>
												<i class='bx bxs-x-circle'></i>
											<?php endif; ?>
										</td>
										<td><?php echo $row['telp'] ?>
											<?php if ($row['verifikasi_telp'] == 1) : ?>
												<i class='bx bxs-check-circle'></i>
											<?php else : ?>
												<i class='bx bxs-x-circle'></i>
											<?php endif; ?>
										</td>
										<td>
											<?php if ($row['verifikasi_akun'] == 1) : ?>
												<span class="terverif"><i class='bx bx-check'></i> Terverifikasi</span>
											<?php else : ?>
												<div class="kotakbelum-verif">
													<span class="belumverif"><i class='bx bx-x'></i> Belum Terverifikasi</span>
													<button class="verifakun">Verifikasi akun</button>
												</div>
											<?php endif; ?>
										</td>
									</tr>
								<?php }
								?>
							</tbody>
						</table>
					</div>
				</div>
				<div class="table-data">
					<div class="order">
						<div class="head">
							<h3>Data sampah pengguna</h3>
							<i class='bx bx-chevron-down'></i>
						</div>
						<table>
							<thead>
								<tr>
									<th>NIK</th>
									<th>Nama</th>
									<th>Anggota keluarga</th>
									<th>Organik</th>
									<th>Plastik</th>
									<th>Logam Kaca</th>
									<th>Tanaman</th>
									<th>Jumlah sampah (Kg)</th>
									<th>Waktu</th>
								</tr>
							</thead>
							<tbody>
								<?php
								while ($row = mysqli_fetch_array($kmsampah)) {
									$tanggal = $row['formatwaktu'];
								?>
									<tr>
										<td><?php echo $row['nik'] ?></td>
										<td><?php echo $row['nama_kepala'] ?></td>
										<td><?php echo $row['jumlah_keluarga'] ?></td>
										<td><?php echo $row['organik'] ?></td>
										<td><?php echo $row['plastik'] ?></td>
										<td><?php echo $row['logam_kaca'] ?></td>
										<td><?php echo $row['tanaman'] ?></td>
										<td><?php echo $row['total'] ?></td>
										<td><?php echo $tanggal ?></td>
									</tr>
								<?php }
								?>
							</tbody>
						</table>
					</div>
				</div>
			</main>
			<!-- MAIN -->
		</section>
		<!-- CONTENT -->
		<script src="JS/script.js"></script>
</body>
<script>
	$(document).ready(function() {
		$(".verifakun").click(function() {
			var button = $(this);
			var row = button.closest('tr');
			var spanBelumVerif = row.find('.belumverif');
			var kotakbelumperip = row.find('.kotakbelum-verif');
			var nikToVerify = row.find('td:first').text();
			$.ajax({
				type: 'POST',
				url: 'datapengguna.php',
				data: {
					verifikasi_akun: true,
					nik_to_verify: nikToVerify
				},
				dataType: 'json',
				success: function(response) {
					if (response.success) {
						console.log('Verifikasi berhasil');
						var spanTerverif = $("<span class='terverif'><i class='bx bx-check'></i> Terverifikasi</span>");
						kotakbelumperip.replaceWith(spanTerverif);
						button.remove();
						div
					} else {
						console.error('Error: ' + response.error);
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error: ' + error);
				}
			});
		});
	});
	//ra ketok tabele
	document.addEventListener("DOMContentLoaded", function () {
        var orderElements = document.querySelectorAll('.order');
        orderElements.forEach(function (orderElement) {
            var headElement = orderElement.querySelector('.bx');

            headElement.addEventListener('click', function () {
                orderElement.classList.toggle('collapsed');

                var tableElement = orderElement.querySelector('table');

                if (tableElement.style.display === 'none') {
                    tableElement.style.display = 'table';
                } else {
                    tableElement.style.display = 'none';
                }
            });
        });
    });
</script>
<?php else :
		header("Location: index.html"); ?>
<?php endif; ?>

</html>