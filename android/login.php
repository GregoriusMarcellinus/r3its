
<?php
require_once('koneksi.php');

if ((isset($_POST['MM_login'])) && ($_POST['MM_login'] == "oiaya")) {
    $nama_kepala = $_POST['nama_kepala'];
    $katasandi = $_POST['katasandi'];

    $LoginRS__query = sprintf(
        "SELECT id_user, nama_kepala, nik, email, telp, tanggal_lahir, jumlah_keluarga, alamat, pekerjaan, tingkat_pendidikan, katasandi, level, verifikasi_email, verifikasi_telp, verifikasi_akun FROM user WHERE nama_kepala=%s AND katasandi=%s",
        app($koneksi, $nama_kepala, "text"),
        app($koneksi, $katasandi, "text")
    );
    $LoginRS = mysqli_query($koneksi, $LoginRS__query) or die(mysqli_error($koneksi));
    $row_rs_LoginRS = mysqli_fetch_assoc($LoginRS);
    $loginFoundUser = mysqli_num_rows($LoginRS);

    if ($loginFoundUser) {
        $response['kode'] = 1;
        $response['pesan'] = "sukses";
        
        //tambahan pada video login 2
        $response['data'] = array();
        $no = 1;
         foreach ($LoginRS as $row_rs_LoginRS) {
             $Data['id_user'] = $row_rs_LoginRS['id_user'];
             $Data['nama_kepala'] = $row_rs_LoginRS['nama_kepala'];
             $Data['nik'] = $row_rs_LoginRS['nik'];
             $Data['email'] = $row_rs_LoginRS['email'];
             $Data['telp'] = $row_rs_LoginRS['telp'];
             $Data['tanggal_lahir'] = $row_rs_LoginRS['tanggal_lahir'];
             $Data['jumlah_keluarga'] = $row_rs_LoginRS['jumlah_keluarga'];
             $Data['alamat'] = $row_rs_LoginRS['alamat'];
             $Data['pekerjaan'] = $row_rs_LoginRS['pekerjaan'];
             $Data['tingkat_pendidikan'] = $row_rs_LoginRS['tingkat_pendidikan'];
             $Data['level'] = $row_rs_LoginRS['level'];
             $Data['verifikasi_email'] = $row_rs_LoginRS['verifikasi_email'];
             $Data['verifikasi_telp'] = $row_rs_LoginRS['verifikasi_telp'];
             $Data['verifikasi_akun'] = $row_rs_LoginRS['verifikasi_akun'];
             array_push($response['data'], $Data);
             $no++;
        }
        //---
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "gagal";
    }

    echo json_encode($response);
    mysqli_close($koneksi);
}


if ((isset($_POST['MM_otp'])) && ($_POST['MM_otp'] == "oiaya")) {
    $email = $_POST['email'];

    $LoginRS__query = sprintf(
        "SELECT id_user, nama_kepala, nik, email, telp, tanggal_lahir, jumlah_keluarga, alamat, pekerjaan, tingkat_pendidikan, katasandi, level, verifikasi_email, verifikasi_telp, verifikasi_akun FROM user WHERE email=%s",
        app($koneksi, $email, "text")
    );
    $LoginRS = mysqli_query($koneksi, $LoginRS__query) or die(mysqli_error($koneksi));
    $row_rs_LoginRS = mysqli_fetch_assoc($LoginRS);
    $loginFoundUser = mysqli_num_rows($LoginRS);

    if ($loginFoundUser) {
        $response['kode'] = 1;
        $response['pesan'] = "sukses";
        
        //tambahan pada video login 2
        $response['data'] = array();
        $no = 1;
         foreach ($LoginRS as $row_rs_LoginRS) {
             $Data['id_user'] = $row_rs_LoginRS['id_user'];
             $Data['nama_kepala'] = $row_rs_LoginRS['nama_kepala'];
             $Data['nik'] = $row_rs_LoginRS['nik'];
             $Data['email'] = $row_rs_LoginRS['email'];
             $Data['telp'] = $row_rs_LoginRS['telp'];
             $Data['tanggal_lahir'] = $row_rs_LoginRS['tanggal_lahir'];
             $Data['jumlah_keluarga'] = $row_rs_LoginRS['jumlah_keluarga'];
             $Data['alamat'] = $row_rs_LoginRS['alamat'];
             $Data['pekerjaan'] = $row_rs_LoginRS['pekerjaan'];
             $Data['tingkat_pendidikan'] = $row_rs_LoginRS['tingkat_pendidikan'];
             $Data['level'] = $row_rs_LoginRS['level'];
             $Data['verifikasi_email'] = $row_rs_LoginRS['verifikasi_email'];
             $Data['verifikasi_telp'] = $row_rs_LoginRS['verifikasi_telp'];
             $Data['verifikasi_akun'] = $row_rs_LoginRS['verifikasi_akun'];
             array_push($response['data'], $Data);
             $no++;
        }
        //---
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "gagal";
    }

    echo json_encode($response);
    mysqli_close($koneksi);
}

if ((isset($_GET['MM_pass'])) && ($_GET['MM_pass'] == "oiaya")) {
    $email = $_GET['email'];
    $katasandi = $_GET['katasandi'];

    $updateSQL = sprintf(
        "UPDATE `user` SET `katasandi`='$katasandi' WHERE `email`='$email'",

    );
    $Resultupdate = mysqli_query($koneksi, $updateSQL) or die(mysqli_error($koneksi));

        if ($Resultupdate) {
            $response['kode'] = 1;
            $response['pesan'] = "Kata Sandi berhasil diubah";
        }else{
    
        $response['kode'] = 0;
        $response['pesan'] = "Kat Sandi gagal dirubah";
        }

    echo json_encode($response);
    mysqli_close($koneksi);
}



