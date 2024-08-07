<?php
require_once('koneksi.php');


//CHECK DATA
// if ((isset($_POST['MM_check'])) && ($_POST['MM_check'] == "oiaya")) {
//     $username = $_POST['username'];
//     $LoginRS__query = sprintf(
//         "SELECT username FROM tb_user WHERE username=%s",
//         app($koneksi, $username, "text"),
//     );
//     $LoginRS = mysqli_query($koneksi, $LoginRS__query) or die(mysqli_error($koneksi));
//     $row_rs_LoginRS = mysqli_fetch_assoc($LoginRS);
//     $loginFoundUser = mysqli_num_rows($LoginRS);

//     if ($loginFoundUser) {
//         $response['kode'] = 1;
//         $response['pesan'] = "Username Telah Digunakan";    
//     } else {
//         $response['kode'] = 0;
//         $response['pesan'] = "Username Tersedia";
//     }

//     echo json_encode($response);
//     mysqli_close($koneksi);
// }


//INSERT DATA
if ((isset($_POST['MM_insert'])) && ($_POST['MM_insert'] == "oiaya")) {

    $insertSQL = sprintf(
        "INSERT INTO `user` (`id_user`, `nama_kepala`, `nik`, `email`, `telp`, `tanggal_lahir`, `jumlah_keluarga`, `alamat`, `pekerjaan`, `tingkat_pendidikan`, `katasandi`) VALUES (%d, %s, %s, %s, %s, %s, %d, %s, %s, %s, %s)",
        app($koneksi, $_POST['id_user'], "int"),
        app($koneksi, $_POST['nama_kepala'], "text"),
        app($koneksi, $_POST['nik'], "text"),
        app($koneksi, $_POST['email'], "text"),
        app($koneksi, $_POST['telp'], "text"),
        app($koneksi, $_POST['tanggal_lahir'], "text"),
        app($koneksi, $_POST['jumlah_keluarga'], "int"),
        app($koneksi, $_POST['alamat'], "text"),
        app($koneksi, $_POST['pekerjaan'], "text"),
        app($koneksi, $_POST['tingkat_pendidikan'], "text"),
        app($koneksi, $_POST['katasandi'], "text")
    );

    $Result1 = mysqli_query($koneksi, $insertSQL) or die(mysqli_error($koneksi));


    if ($Result1) {
        $response['kode'] = 1;
        $response['pesan'] = "Data berhasil disimpan";
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "Data gagal disimpan";
    }

    echo json_encode($response);
    mysqli_close($koneksi);
}

//VIEW DATA
if ((isset($_GET['MM_view'])) && ($_GET['MM_view'] == "oiaya")) {
    $id_user = "-1";
    if(isset($_GET['id_user'])){
        $id_user = $_GET['id_user'];
    }
    $query = sprintf(
        "SELECT * FROM  tb_user WHERE id_user=%s",
        app($koneksi, $id_user, "int")
    );
    $data = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    $rs_data = mysqli_fetch_assoc($data);
    $ResultData = mysqli_num_rows($data);

    if ($ResultData > 0) {
        $response['kode'] = 1;
        $response['pesan'] = "Data Tersedia";
        $response['data'] = array();
        foreach ($data as $user) {
            $arr['id_user'] = $user['id_user'];
            $arr['nama'] = $user['nama'];
            $arr['username'] = $user['username'];
            $arr['email'] = $user['email'];
            $arr['telp'] = $user['telp'];
            $arr['alamat'] = $user['alamat'];
            $arr['foto_profil'] = $user['foto_profil'];
            array_push($response['data'], $arr);
        }
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "Data tidak ditemukan!";
    }

    echo json_encode($response);
    mysqli_close($koneksi);
}

//EDIT DATA
if ((isset($_POST['MM_update'])) && ($_POST['MM_update'] == "oiaya")) {

    $id_user = $_POST['id_user'];
    $cari_query = sprintf(
        "SELECT id_user FROM tb_user WHERE id_user=%d",
        app($koneksi, $id_user, "int")
    );
    $cari = mysqli_query($koneksi, $cari_query) or die(mysqli_error($koneksi));
    $ResultCari = mysqli_num_rows($cari);

    if ($ResultCari > 0) {
        $updateSQL = sprintf(
            "UPDATE `tb_user` SET `nama`=%s,`username`=%s,`email`=%s,`telp`=%s,`alamat`=%s,`foto_profil`=%s WHERE `id_user`=%d",
            app($koneksi, $_POST['nama'], "text"),
            app($koneksi, $_POST['username'], "text"),
            app($koneksi, $_POST['email'], "text"),
            app($koneksi, $_POST['telp'], "text"),
            app($koneksi, $_POST['alamat'], "text"),
            app($koneksi, $_POST['foto_profil'], "text"),
            app($koneksi, $_POST['id_user'], "int")
        );

        $Result1 = mysqli_query($koneksi, $updateSQL) or die(mysqli_error($koneksi));

        if ($Result1) {
            $response['kode'] = 1;
            $response['pesan'] = "Data berhasil diubah";
        }
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "ID tidak ditemukan!";
    }
    echo json_encode($response);
    mysqli_close($koneksi);
}

//DELETE DATA
if ((isset($_POST['MM_delete'])) && ($_POST['MM_delete'] == "oiaya")) {

    $id = $_POST['id'];
    $cari_query = sprintf(
        "SELECT id FROM tb_user WHERE id=%s",
        app($koneksi, $id, "int")
    );
    $cari = mysqli_query($koneksi, $cari_query) or die(mysqli_error($koneksi));
    $ResultCari = mysqli_num_rows($cari);

    if ($ResultCari > 0) {

        $deleteSQL = sprintf(
            "DELETE FROM `tb_user` WHERE id = %s",
            app($koneksi, $_POST['id'], "int")
        );

        $Result1 = mysqli_query($koneksi, $deleteSQL) or die(mysqli_error($koneksi));

        if ($Result1) {
            $response['kode'] = 1;
            $response['pesan'] = "Data berhasil dihapus!";
        }
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "ID tidak ditemukan!";
    }


    echo json_encode($response);
    mysqli_close($koneksi);
}

//EDIT SALDO
if ((isset($_POST['MM_withdraw'])) && ($_POST['MM_withdraw'] == "oiaya")) {
    $id_user = $_POST['id_user'];
    $cari_query = sprintf(
        "SELECT id_user FROM tb_user WHERE id_user=%d",
        app($koneksi, $id_user, "int")
    );
    $cari = mysqli_query($koneksi, $cari_query) or die(mysqli_error($koneksi));
    $ResultCari = mysqli_num_rows($cari);

    if ($ResultCari > 0) {
        $updateSQL = sprintf(
            "UPDATE `tb_user` SET `saldo`=%d WHERE `id_user`=%d",
            app($koneksi, $_POST['saldo'], "int"),
            app($koneksi, $_POST['id_user'], "int")
        );

        $Result1 = mysqli_query($koneksi, $updateSQL) or die(mysqli_error($koneksi));

        if ($Result1) {
            $response['kode'] = 1;
            $response['pesan'] = "Saldo berhasil dirubah";
        }
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "Saldo gagal dirubah";
    }
    echo json_encode($response);
    mysqli_close($koneksi);
}
