<?php

session_start();

if (isset($_SESSION["pengelolah_id"])) {

	$mysqli = require __DIR__ . "/database.php";

	$sql = "SELECT * FROM tb_pengelolah
            WHERE id_pengelolah = {$_SESSION["pengelolah_id"]}";

	$result = $mysqli->query($sql);

	$pengelolah = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Petugas sampah</title>
    <link rel="stylesheet" href="CSS/formulirstyle.css">
    <link rel="stylesheet"
     href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    
    <?php if (isset($pengelolah)) : ?>
    <div class="container">
    <a href="beranda.php" class="btnkembali" ><i class='bx bx-arrow-back'></i></a>
        <div class="header">
            <div class="header-title">
                <h1 class="title">Pendaftaran akun</h1>
            </div>
            <ul class="nav">
                <li class="nav-item"><a href="WargaDesa.php">Warga Desa</a></li>
                <li class="nav-item"><a href="PetugasSampah.php">Petugas Sampah</a></li>
                <li class="nav-item"><a href="Pengelolah.php">Pengelolah</a></li>
            </ul>
        </div>


        <form action="register_PS.php">
            <div class="detail-pengguna">
                <div class="input-box">
                    <label class="required" name="nama">Nama petugas</label>
                    <input type="text" placeholder="Masukkan nama lengkap" required>
                </div>
                <div class="input-box">
                    <label class="required" name="h-kerja">Hari kerja</label>
                    <input type="text" placeholder="Masukkan hari kerja" required>
                </div>
                <div class="input-box">
                    <label class="required" name="j-kerja">Jam kerja</label>
                    <input type="text" placeholder="Masukkan jam kerja" required>
                </div>
                <div class="input-box">
                    <label class="required" name="NIKP">NIK (Petugas)</label>
                    <input type="text" placeholder="Masukkan NIK" required>
                </div>
                <div class="input-box">
                    <label class="required">Alamat (sesuai KTP)</label>
                    <div class="alamat">
                        <input type="text" name="jalan" placeholder="Jalan" required>
                        <input type="text" name="rt" placeholder="RT" required>
                        <input type="text" name="rw" placeholder="RW" required>
                        <input type="text" name="Kelurahan" placeholder="Kelurahan/Desa" required>
                        <input type="text" name="kecamatan" placeholder="kecamatan" required>
                    </div>
                </div>
                <div class="input-box">
                    <label class="required" name="no_hp">Nomor handphone</label>
                    <input type="text" placeholder="Masukkan nomor handphone" required>
                </div>
            </div>
            <div class="button">
                <button type="submit"  class="tmbldaftar">daftar</button>
            </div>
        </form>
    </div>
	<?php else :
		header("Location: index.html"); ?>
	<?php endif; ?>
</body>
</html>