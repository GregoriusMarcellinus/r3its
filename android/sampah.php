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
        "INSERT INTO `sampah` (`id_ambil`, `nik`, `tanaman`, `plastik`, `organik`, `logam_kaca`, `total`, `catatan`, `hari`, `tanggal`, `bulan`, `tahun`, `waktu`) VALUES (%d, %s, %f, %f, %f, %f, %f, %s, %d, %d, %d, %d, %s)",
        app($koneksi, $_POST['id_ambil'], "int"),
        app($koneksi, $_POST['nik'], "text"),
        app($koneksi, $_POST['tanaman'], "float"),
        app($koneksi, $_POST['plastik'], "float"),
        app($koneksi, $_POST['organik'], "float"),
        app($koneksi, $_POST['logam_kaca'], "float"),
        app($koneksi, $_POST['total'], "float"),
        app($koneksi, $_POST['catatan'], "text"),
        app($koneksi, $_POST['hari'], "int"),
        app($koneksi, $_POST['tanggal'], "int"),
        app($koneksi, $_POST['bulan'], "int"),
        app($koneksi, $_POST['tahun'], "int"),
        app($koneksi, $_POST['waktu'], "text")
    );
    
    $insertTotalSampahSQL = sprintf(
    "INSERT INTO `total_sampah` (`tanaman`, `plastik`, `organik`, `logam_kaca`, `waktu`) VALUES (%f, %f, %f, %f, %s)",
    app($koneksi, $_POST['tanaman'], "float"),
    app($koneksi, $_POST['plastik'], "float"),
    app($koneksi, $_POST['organik'], "float"),
    app($koneksi, $_POST['logam_kaca'], "float"),
    app($koneksi, $_POST['waktu'], "text")
);
    

    $Result1 = mysqli_query($koneksi, $insertSQL) or die(mysqli_error($koneksi));
    $Result1 = mysqli_query($koneksi, $insertTotalSampahSQL) or die(mysqli_error($koneksi));


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

//VIEW DATA SEMUA
if ((isset($_GET['MM_view'])) && ($_GET['MM_view'] == "oiaya")) {
    $query = "SELECT * FROM sampah"; // Retrieve all data from the table
    
    $data = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    
    $totalSum = 0; // Initialize a variable to store the sum of a specific column
    $totalTanaman = 0; // Initialize a variable to store the sum of a specific column
    $totalPlastik = 0; // Initialize a variable to store the sum of a specific column
    $totalLogam_kaca = 0; // Initialize a variable to store the sum of a specific column
    $totalOrganik = 0; // Initialize a variable to store the sum of a specific column
    
    $response['kode'] = 1;
    $response['pesan'] = "Data Tersedia";
    $response['data'] = array();
    
    while ($user = mysqli_fetch_assoc($data)) {
        $arr['tanaman'] = $user['tanaman'];
        $arr['plastik'] = $user['plastik'];
        $arr['organik'] = $user['organik'];
        $arr['logam_kaca'] = $user['logam_kaca'];
        $arr['total'] = $user['total'];
        
        // Add the value of the 'total' column to the totalSum
        $totalSum += (int)$user['total'];
        $totalTanaman += (int)$user['tanaman'];
        $totalPlastik += (int)$user['plastik'];
        $totalOrganik += (int)$user['organik'];
        $totalLogam_kaca += (int)$user['logam_kaca'];
        
        array_push($response['data'], $arr);
    }
    
    // Add the total sum to the response
    $response['total_sum'] = $totalSum;
    $response['total_tanaman'] = $totalTanaman;
    $response['total_plastik'] = $totalPlastik;
    $response['total_organik'] = $totalOrganik;
    $response['total_logam_kaca'] = $totalLogam_kaca;
    
    echo json_encode($response);
    mysqli_close($koneksi);
}

//RIWAYAT AMBIL SAMPAH
if ((isset($_GET['MM_riwayat'])) && ($_GET['MM_riwayat'] == "oiaya")) {
    $query = "SELECT * FROM sampah ORDER BY id_ambil DESC"; // Retrieve the 5 most recent rows

    
    $data = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    $rs_data = mysqli_fetch_assoc($data);
    $ResultData = mysqli_num_rows($data);
    
    
    $response['kode'] = 1;
    $response['pesan'] = "Data Tersedia";
    $response['row'] = $ResultData;
    $response['data'] = array();
    
    foreach ($data as $user) {
        $arr['id_ambil'] = $user['id_ambil'];
        $arr['nik'] = $user['nik'];
        $arr['total'] = $user['total'];
        $arr['waktu'] = $user['waktu'];
        
        array_push($response['data'], $arr);
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

//VIEW DATA
if ((isset($_GET['MM_search'])) && ($_GET['MM_search'] == "oiaya")) {

    $query = sprintf(
        "SELECT * FROM  user",
    );
    $data = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    $rs_data = mysqli_fetch_assoc($data);
    $ResultData = mysqli_num_rows($data);

    if ($ResultData > 0) {
        $response['kode'] = 1;
        $response['row'] = mysqli_num_rows($data);
        $response['data'] = array();
        foreach ($data as $user) {
            $arr['nama_kepala'] = $user['nama_kepala'];
            $arr['nik'] = $user['nik'];
            $arr['alamat'] = $user['alamat'];
            array_push($response['data'], $arr);
        }
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "Data tidak ditemukan!";
    }

    echo json_encode($response);
    mysqli_close($koneksi);
}
