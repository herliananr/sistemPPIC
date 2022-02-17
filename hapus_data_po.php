<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    include("layout/koneksi.php");

    if (isset($_GET["id_po"])) {
        $pesan_diterima = $_GET["id_po"];
    }

    $query="delete from po where id_po='$pesan_diterima'";
    $hasil= mysqli_query($link, $query);

    $query2="delete from po_pk where id_po='$pesan_diterima'";
    $hasil2= mysqli_query($link, $query2);

    if ($hasil) {
        $pesandikirim .="Data dengan ID = $pesan_diterima berhasil dihapus";
        $pesan_dikirim=urlencode($pesandikirim);
        header("Location: data_po.php?pesandikirim=$pesan_dikirim");
        die();
        
    }
    else {
        die ("Query gagal dijalankan: ".mysqli_errno($link).
        " - ".mysqli_error($link));
    }
?>
