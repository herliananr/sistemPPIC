<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterimadua="";
    $pesan_diterimatiga="";
    $pesan_diterimaempat="";
    $pesan_edit="";
    $namaproduk="";
    $uomproduk="";

    include("layout/koneksi.php");

    if (isset($_GET["id_po"])) {
        $pesan_diterima = $_GET["id_po"];
        $pesan_diterimadua = $_GET["id_delv"];
        $pesan_diterimatiga = $_GET["id_produk"];
        $pesan_diterimaempat = $_GET["qty_produk"];
    }

    if (isset($_GET["edit"])) {
        $pesan_edit = $_GET["edit"];
    }

    $query="select * from schedule_delivery where id_po='$pesan_diterima' and id_produk='$pesan_diterimatiga'";
    $is= mysqli_query($link, $query);
    $result=mysqli_fetch_array($is);
    $namaproduk=$result['nama_produk'];
    $uomproduk=$result['uom'];

    $query="delete from schedule_delivery where id_po='$pesan_diterima' and id_delv='$pesan_diterimadua'";
    $hasil= mysqli_query($link, $query);

    if ($hasil) {
        $pesandikirim .="Data dengan ID Delivery = $pesan_diterimadua berhasil dihapus";
        $pesan_dikirim = urlencode($pesandikirim);

        if ($pesan_edit=="y") {
            header("Location: edit_data_schedule_delivery.php?pesandikirim=$pesan_dikirim&id_po=$pesan_diterima&id_produk=$pesan_diterimatiga&nama_produk=$namaproduk&uom_produk=$uomproduk&qty_produk=$pesan_diterimaempat");
            die();
        }
        else {
            header("Location: tambah_data_schedule_delivery.php?pesandikirim=$pesan_dikirim&id_po=$pesan_diterima&id_produk=$pesan_diterimatiga&nama_produk=$namaproduk&uom_produk=$uomproduk&qty_produk=$pesan_diterimaempat");
            die();
        }
    }
    else {
        die ("Query gagal dijalankan: ".mysqli_errno($link).
        " - ".mysqli_error($link));
    }
?>
