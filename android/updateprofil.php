<?php
require_once('koneksi.php');

if ((isset($_GET['MM_update'])) && ($_GET['MM_update'] == "oiaya")) {
    $nik = $_GET['nik'];
    $email = $_GET['email'];
    $telp = $_GET['telp'];
    $jumlah_keluarga = $_GET['jumlah_keluarga'];

    $updateSQL = sprintf(
        "UPDATE `user` SET `email`='$email',`telp`='$telp',`jumlah_keluarga`='$jumlah_keluarga' WHERE `nik`='$nik'",

    );
    $Resultupdate = mysqli_query($koneksi, $updateSQL) or die(mysqli_error($koneksi));

        if ($Resultupdate) {
            $response['kode'] = 1;
            $response['pesan'] = "Data berhasil diubah";
        }else{
    
        $response['kode'] = 0;
        $response['pesan'] = "gagal menambahkan ke keranjang";
        }

    echo json_encode($response);
    mysqli_close($koneksi);
}
