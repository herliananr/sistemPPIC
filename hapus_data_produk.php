<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    include("layout/koneksi.php");

    if (isset($_GET["id_produk"])) {
        $pesan_diterima = $_GET["id_produk"];
    }

    $query="delete from produk where id_produk='$pesan_diterima'";
    $hasil= mysqli_query($link, $query);

    $query2="delete from stok_produk where id_produk='$pesan_diterima'";
    $hasil2= mysqli_query($link, $query2);

    $query3="delete from lead_time where id_barang='$pesan_diterima'";
    $hasil3= mysqli_query($link, $query3);

    if ($hasil) {
        $pesandikirim .="Data dengan ID = $pesan_diterima berhasil dihapus";
        $pesan_dikirim=urlencode($pesandikirim);
        header("Location: data_produk.php?pesandikirim=$pesan_dikirim");
        die();
        
    }
    else {
        die ("Query gagal dijalankan: ".mysqli_errno($link).
        " - ".mysqli_error($link));
    }
?>
