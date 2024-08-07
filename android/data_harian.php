<?php
require_once('koneksi.php');

//VIEW DATA
if ((isset($_GET['MM_view'])) && ($_GET['MM_view'] == "oiaya")) {
    $id_user = $_GET['nik'];

    $query = sprintf(
        "SELECT * FROM `sampah` WHERE `nik`= %s",
        app($koneksi, $id_user, "text")
    );
    $data = mysqli_query($koneksi, $query) or die(mysqli_error($koneksi));
    $rs_data = mysqli_fetch_assoc($data);
    $ResultData = mysqli_num_rows($data);

    if ($ResultData > 0) {
        $response['kode'] = 1;
        $response['pesan'] = "Data Tersedia";
        $response['row'] = $ResultData;
        $response['data'] = array();
        foreach ($data as $user) {
            $arr['id_ambil'] = $user['id_ambil'];
            $arr['nik'] = $user['nik'];
            $arr['tanaman'] = $user['tanaman'];
            $arr['plastik'] = $user['plastik'];
            $arr['organik'] = $user['organik'];
            $arr['logam_kaca'] = $user['logam_kaca'];
            $arr['total'] = $user['total'];
            $arr['kategori'] = $user['kategori'];
            $arr['catatan'] = $user['catatan'];
            $arr['hari'] = $user['hari'];
            $arr['tanggal'] = $user['tanggal'];
            $arr['bulan'] = $user['bulan'];
            $arr['tahun'] = $user['tahun'];
            $arr['waktu'] = $user['waktu'];
            array_push($response['data'], $arr);
        }
    } else {
        $response['kode'] = 0;
        $response['pesan'] = "Data tidak ditemukan!";
    }

    echo json_encode($response);
    mysqli_close($koneksi);
}