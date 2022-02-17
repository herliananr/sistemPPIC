<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterimadua="";
    $pesan_diterimatiga="";
    $namaproduk="";

    include("layout/koneksi.php");

    if (isset($_GET["id_produk_bom"])) {
        $pesan_diterima = $_GET["id_produk_bom"];
        $pesan_diterimadua = $_GET["id_bahan_baku"];
    }
    if (isset($_GET["edit"])) {
        $pesan_diterimatiga=$_GET["edit"];
    }

    
    $query="select * from bom where id_produk_bom='$pesan_diterima' and id_bahan_baku='$pesan_diterimadua'";
    $is= mysqli_query($link, $query);
    $result=mysqli_fetch_array($is);
    $namaproduk=$result['nama_produk'];


    $query="delete from bom where id_produk_bom='$pesan_diterima' and id_bahan_baku='$pesan_diterimadua'";
    $hasil= mysqli_query($link, $query);

    if ($hasil) {
        $pesandikirim .="Data dengan ID Produk = $pesan_diterima dan ID Bahan Baku = $pesan_diterimadua berhasil dihapus";
        $pesan_dikirim = urlencode($pesandikirim);
        if ($pesan_diterimatiga=="y") {
            header("Location: edit_data_bom.php?pesandikirim=$pesan_dikirim&id_produk_bom=$pesan_diterima&nama_produk=$namaproduk");
            die();
        }
        else {
            header("Location: tambah_data_bom.php?pesandikirim=$pesan_dikirim&id_produk_bom=$pesan_diterima&nama_produk=$namaproduk");
            die();
        }
        
    }
    else {
        die ("Query gagal dijalankan: ".mysqli_errno($link).
        " - ".mysqli_error($link));
    }
?>
