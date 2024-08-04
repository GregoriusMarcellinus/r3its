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
    <title>Pendaftaran Warga desa</title>
    	<link rel="icon" type="image/png" sizes="32x32" href="./img/favicon_io/favicon-32x32.png">
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

        <form action="register_WD.php" method="POST">
            <div class="detail-pengguna">
                <div class="input-box">
                    <label class="required" >Nama kepala keluarga</label>
                    <input type="text"name="username" placeholder="Masukkan Nama" required>
                </div>
                <div class="input-box">
                    <label class="required" >Email</label>
                    <input type="text"  name="email" placeholder="Masukkan email" required>
                </div>
                <div class="input-box">
                    <label class="required" >Kata sandi</label>
                    <input type="password"  name="password" placeholder="Masukkan kata sandi" required>
                </div>
                <div class="input-box">
                    <label class="required" >NIK(kepala keluarga)</label>
                    <input type="text" name="nik" placeholder="Masukkan NIK" required>
                </div>
                <div class="input-box">
                    <label class="required" >Tanggal lahir</label>
                    <input type="date" name="tanggal" required>
                </div>
                <div class="input-box">
                    <label class="required" >Nomor handphone</label>
                    <input type="text" name="nomer_hp" placeholder="Masukkan No handphone" required>
                </div>
                <div class="input-box">
                    <label class="required" >Jumlah anggota keluarga</label>
                    <input type="text"  name="anggota_keluarga" placeholder="Masukkan jumlah anggota keluarga" required>
                </div>
                <div class="input-box">
                    <label class="required" >Tingkat pendidikan</label>
                    <input type="text" name="pendidikan" placeholder="Masukkan tingkat pendidikaan"  required>
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
                    <label class="required">Pekerjaan</label>
                    <input type="text" name="pekerjaan" placeholder="Masukkan pekerjaan"  required>
                </div>
             </div>

                <div class="button">
                <button type="submit" class="tmbldaftar" >daftar</button>
                </div>
        </form>
    </div>
       <!-- <div class="borderijo"></div> -->
    <?php else :
		header("Location: index.html"); ?>
	<?php endif; ?>
</body>
</html>