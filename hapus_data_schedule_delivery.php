<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterimadua="";
    include("layout/koneksi.php");

    if (isset($_GET["id_po"])) {
        $pesan_diterima = $_GET["id_po"];
        $pesan_diterimadua = $_GET["id_produk"];
    }

    $query="delete from schedule_delivery where id_po='$pesan_diterima' and id_produk='$pesan_diterimadua'";
    $hasil= mysqli_query($link, $query);

    if ($hasil) {
        $pesandikirim .="Data dengan ID = $pesan_diterima berhasil dihapus";
        $pesan_dikirim=urlencode($pesandikirim);
        header("Location: data_schedule_delivery.php?pesandikirim=$pesan_dikirim");
        die();
        
    }
    else {
        die ("Query gagal dijalankan: ".mysqli_errno($link).
        " - ".mysqli_error($link));
    }
?>
