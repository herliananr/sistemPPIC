<?php
include("layout/session.php");
?>

<?php
    $pesan_diterima="";
    $pesan_diterimadua="";
    $pesan_edit="";
    $idpartner="";
    $namapartner="";
    $tanggalterbit="";
    include("layout/koneksi.php");

    if (isset($_GET["id_po"])) {
        $pesan_diterima = $_GET["id_po"];
        $pesan_diterimadua = $_GET["id_produk"];
    }

    if (isset($_GET["edit"])) {
        $pesan_edit=$_GET["edit"];
    }
    //untuk repopulate form dengan tampilan adanya id_partner, nama_partner, dan tanggal_terbit
    $query="select * from po_pk where id_po='$pesan_diterima'";
    $is= mysqli_query($link, $query);
    $result=mysqli_fetch_array($is);
    $idpartner=$result['id_partner'];
    $namapartner=$result['nama_partner'];
    $tanggalterbit=$result['tanggal_terbit'];


    $query="delete from po where id_po='$pesan_diterima' and id_produk='$pesan_diterimadua'";
    $hasil= mysqli_query($link, $query);

    $query2 = "SELECT * FROM po WHERE id_po='$pesan_diterima'";
    $result2 = mysqli_query($link, $query2);
    $jumlah_data2 = mysqli_num_rows($result2);
    //untuk menghapus data pada suatu po di tabel po_pk
    if ($jumlah_data2 < 1 ) {
        $query3="delete from po_pk where id_po='$pesan_diterima'";
        $hasil3= mysqli_query($link, $query3);
    }

    if ($hasil) {
        $pesandikirim .="Data dengan ID PO = $pesan_diterima dan ID Produk = $pesan_diterimadua berhasil dihapus";
        $pesan_dikirim = urlencode($pesandikirim);

        if ($pesan_edit=="y") {
            header("Location: edit_data_po.php?pesandikirim=$pesan_dikirim&id_po=$pesan_diterima&id_partner=$idpartner&nama_partner=$namapartner&tanggal_terbit=$tanggalterbit");
            die();
        }
        else {
            header("Location: tambah_data_po.php?pesandikirim=$pesan_dikirim&id_po=$pesan_diterima&id_partner=$idpartner&nama_partner=$namapartner&tanggal_terbit=$tanggalterbit");
            die();
        }
    }
    else {
        die ("Query gagal dijalankan: ".mysqli_errno($link).
        " - ".mysqli_error($link));
    }
?>
