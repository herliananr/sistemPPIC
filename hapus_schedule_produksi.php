<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterimadua="";
    $pesan_diterimatiga="";
    include("layout/koneksi.php");

    if (isset($_GET["id_produksi"])) {
        $pesan_diterima = $_GET["id_produksi"];
        $pesan_diterimadua = $_GET["hapus"];
        $pesan_diterimatiga = $_GET["id_mps"];
    }

    $query="delete from schedule_produksi where id_produksi='$pesan_diterima'";
    $hasil= mysqli_query($link, $query);

    $query2="delete from pengendalian_produksi where id_produksi='$pesan_diterima'";
    $hasil2= mysqli_query($link, $query2);

    if ($hasil) {
        $pesandikirim .="Data dengan ID Schedule Produksi= $pesan_diterima berhasil dihapus";
        $pesan_dikirim=urlencode($pesandikirim);

        if ($pesan_diterimadua=="tambah_sp") {
            header("Location: tambah_schedule_produksi.php?pesandikirim=$pesan_dikirim&id_mps=$pesan_diterimatiga");
            die();
        }
        else {
            header("Location: schedule_produksi.php?pesandikirim=$pesan_dikirim");
            die();
        }

        
    }
    else {
        die ("Query gagal dijalankan: ".mysqli_errno($link).
        " - ".mysqli_error($link));
    }
?>
