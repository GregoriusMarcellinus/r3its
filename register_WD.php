<?php
require 'database.php';

$username = $_POST['username'];
$password = $_POST['password'];
$email = $_POST["email"];
$tanggal = $_POST['tanggal'];
$anggota_keluarga = $_POST['anggota_keluarga'];
$jalan = $_POST['jalan'];
$rt = $_POST['rt'];
$rw = $_POST['rw'];
$kelurahan = $_POST['Kelurahan'];
$kecamatan = $_POST['kecamatan'];
$nik = $_POST['nik'];
$nomer_hp = $_POST['nomer_hp'];
$pendidikan = $_POST['pendidikan'];
$pekerjaan = $_POST['pekerjaan'];

$query_sql = "INSERT INTO tb_user (username,password,email,tanggal,
                          anggota_keluarga,jalan,rt,rw,Kelurahan,Kecamatan,nik,nomer_hp,
                          pendidikan,pekerjaan )
              VALUE('$username', '$password', '$email', '$tanggal', '$anggota_keluarga', '$jalan', '$rt', '$rw', 
                    '$kelurahan', '$kecamatan', '$nik', '$nomer_hp', '$pendidikan', '$pekerjaan')";
if(mysqli_query($conn, $query_sql)){
    header("Location: index.html");
}else{
    echo "pendaftaran Gagal : " . mysqli_error($conn);
}