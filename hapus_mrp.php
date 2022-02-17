<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    include("layout/koneksi.php");

    if (isset($_GET["id_mrp"])) {
        $pesan_diterima = $_GET["id_mrp"];
    }

    $query="delete from mrp where id_mrp='$pesan_diterima'";
    $hasil= mysqli_query($link, $query);

    if ($hasil) {
        $pesandikirim .="Data dengan ID MRP= $pesan_diterima berhasil dihapus";
        $pesan_dikirim=urlencode($pesandikirim);
        header("Location: mrp.php?pesandikirim=$pesan_dikirim");
        die();
        
    }
    else {
        die ("Query gagal dijalankan: ".mysqli_errno($link).
        " - ".mysqli_error($link));
    }
?>
