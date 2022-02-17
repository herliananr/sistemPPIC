
<?php

    $id_mrp="";

    if (isset($_GET["id_mrp"])) {
        $id_mrp = $_GET['id_mrp'];

        include("layout/koneksi.php");
        $query="select max(id_purchase_order) as id_purchase_order_terbesar from purchase_order where id_purchase_order like 'PUROR%'";
        $result = mysqli_query($link, $query);
        $hasil = mysqli_fetch_array($result);
        $idpurchaseorder = $hasil['id_purchase_order_terbesar'];
        $potonganurutan = (int) substr($idpurchaseorder,5,5);
        $potonganurutan++;

        $hurufdepan = "PUROR";
        $idpurchaseorder = $hurufdepan. sprintf("%05s", $potonganurutan);

        //jalankan query insert
        $query = "insert into purchase_order (id_mrp, id_produk, id_bahan_baku, tanggal_penerimaan, qty) select id_mrp, id_bom, id_bahan_baku, 
        tanggal_penerimaan, planned_order_release from mrp where id_mrp='$id_mrp' and level<>0 order by id_bahan_baku, tanggal_penerimaan";
        $hasil = mysqli_query($link, $query);

        $query2= "update purchase_order set id_purchase_order='$idpurchaseorder' where id_mrp='$id_mrp'";
        $hasil2= mysqli_query($link, $query2);

        if ($hasil) {
            $pesan_diterima = "Purchase Order berhasil ditambahkan";
            $pesan_dikirim =urlencode($pesan_diterima);
            header("Location: purchase_order.php?pesandikirim=$pesan_dikirim");
            die();
        }
        else {
            die ("Query gagal dijalankan: ".mysqli_errno($link).
            " - ".mysqli_error($link));
        }
    }

?>
