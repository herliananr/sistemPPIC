<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    include("layout/koneksi.php");

    if (isset($_GET["id_partner"])) {
        $pesan_diterima = $_GET["id_partner"];
    }

    $query="delete from partner where id_partner='$pesan_diterima'";
    $hasil= mysqli_query($link, $query);

    if ($hasil) {
        $pesandikirim .="Data dengan ID = $pesan_diterima berhasil dihapus";
        $pesan_dikirim=urlencode($pesandikirim);
        header("Location: data_partner.php?pesandikirim=$pesan_dikirim");
        die();
        
    }
    else {
        die ("Query gagal dijalankan: ".mysqli_errno($link).
        " - ".mysqli_error($link));
    }
?>
