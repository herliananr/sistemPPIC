<?php
include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");
    $pesan_diterima="";
    $subcont="";
    $keluar=""; //untuk menandai bahwa ini suatu fungsi untuk mengupdate
    $warna="";

    if (isset($_GET["pesandikirim"])) {
        $pesan_diterima = $_GET["pesandikirim"];
    }
    //ini fungsi untuk mengupdate stok produk
    if (isset($_GET["subcont"]) and isset($_GET["keluar"])) {
        $subcont = $_GET["subcont"];
        $keluar = $_GET["keluar"];
    
        $ambil_id_bahan_baku= $_GET["id_bahan_baku"];
        $ambil_nama_bahan_baku=$_GET["nama_bahan_baku"];
        $ambil_id_po=$_GET["id_po"];
        $ambil_id_mps=$_GET["id_mps"];
        $ambil_tanggal_penerimaan=$_GET["tanggal_penerimaan"];
        $ambil_qty=(float)$_GET["qty"];
        $ambil_uom=$_GET["uom"];
        $ambil_warna=$_GET["warna"];
        $ambil_bentuk=$_GET["bentuk"];
        $ambil_spesifikasi=$_GET["spesifikasi"];
        
        $tanggal_keluar=date("Y-m-d"); //untuk mengambil hari ini
    
        //untuk mengisi keterangan
        if ($subcont=="bahanbaku") {
            $keterangan=$ambil_id_po.", ".$ambil_id_mps.", ".$ambil_tanggal_penerimaan.", ".$ambil_nama_bahan_baku.", ".$ambil_warna.", ".$ambil_bentuk.", ".$ambil_spesifikasi.", subcontbahanbaku";
        }
        else if ($subcont=="produk") {
            $keterangan=$ambil_id_po.", ".$ambil_id_mps.", ".$ambil_tanggal_penerimaan.", ".$ambil_nama_bahan_baku.", ".$ambil_warna.", ".$ambil_bentuk.", ".$ambil_spesifikasi.", subcontproduk";
        }
        
    
        $quericari=mysqli_query($link, "select*from bahan_baku_keluar where keterangan='$keterangan'");
        $hasilcari = mysqli_num_rows($quericari);
    
        $querycekstok = mysqli_query($link, "SELECT * FROM stok_bahan_baku WHERE id_bahan_baku='$ambil_id_bahan_baku'");
        $hasilcekstok = mysqli_fetch_assoc($querycekstok);
        $stok = $hasilcekstok['stok']-(float)$ambil_qty;

        if ($stok<0) {
            $pesandikirim .="Qty keluar tidak boleh mengurangi stok sampai dibawah 0.";
            $pesan_dikirim =urlencode($pesandikirim);
            if ($subcont=="bahanbaku") {
                header("Location: kurangi_stok_bahan_baku.php?pesandikirim=$pesan_dikirim&subcont=bahanbaku&warna=merah");
            }
            else if ($subcont=="produk") {
                header("Location: kurangi_stok_bahan_baku.php?pesandikirim=$pesan_dikirim&subcont=produk&warna=merah");
            }
            die();
        }

        else if ($hasilcari>0) {
            $pesandikirim .="Gagal mengurangi stok karena stok yang diminta sudah dikeluarkan.";
            $pesan_dikirim =urlencode($pesandikirim);
            if ($subcont=="bahanbaku") {
                header("Location: kurangi_stok_bahan_baku.php?pesandikirim=$pesan_dikirim&subcont=bahanbaku&warna=merah");
            }
            else if ($subcont=="produk") {
                header("Location: kurangi_stok_bahan_baku.php?pesandikirim=$pesan_dikirim&subcont=produk&warna=merah");
            }
            die();
        }
    
        else {
            //deklarasi untuk id bahan_baku_keluar
            $query="select max(id_bb_keluar) as id_bb_keluar_terbesar from bahan_baku_keluar where id_bb_keluar like 'BBOUT%'";
            $result = mysqli_query($link, $query);
            $hasil = mysqli_fetch_array($result);
            $idbbkeluar = $hasil['id_bb_keluar_terbesar'];
            $potonganurutan = (int) substr($idbbkeluar,5,4);
            $potonganurutan++;
    
            $hurufdepan = "BBOUT";
            $idbbkeluar = $hurufdepan. sprintf("%04s", $potonganurutan);
    
            $query2 = "update stok_bahan_baku set stok=stok-$ambil_qty where id_bahan_baku='$ambil_id_bahan_baku'";
            $hasil2 = mysqli_query($link, $query2);
    
            $query3 = "insert into bahan_baku_keluar values ('$idbbkeluar','$tanggal_keluar','$ambil_id_bahan_baku',
            $ambil_qty,'$ambil_uom','$keterangan')";
            $hasil3 = mysqli_query($link, $query3);

            if ($hasil2) {
                $pesandikirim .="Berhasil mengeluarkan sejumlah stok yang dibutuhkan. ";
                $pesan_dikirim =urlencode($pesandikirim);
                if ($subcont=="bahanbaku") {
                    header("Location: kurangi_stok_bahan_baku.php?pesandikirim=$pesan_dikirim&subcont=bahanbaku&warna=hijau");
                }
                else if ($subcont=="produk") {
                    header("Location: kurangi_stok_bahan_baku.php?pesandikirim=$pesan_dikirim&subcont=produk&warna=hijau");
                }
                die();
            }
        }
    }
    else {
        $subcont = $_GET["subcont"];
        $warna = $_GET["warna"];
    }



?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Kurangi Stok Bahan Baku</title>
    <?php
    include ("layout/title_import.php");
    include ("layout/hak_akses.php");
    ?>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

    <?php
    include ("layout/sidebar.php");
    ?>

    

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

            <?php
            include ("layout/topbar.php");
            ?>
            <!-- Begin Page Content -->
                <div class="container-fluid">

                    <?php
                         // tampilkan error jika ada
                        if ($pesan_diterima !== "") {
                            if ($warna=="merah") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-danger fade show notifikasiperingatan\">$pesan_diterima<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>"; 
                            }
                            else if ($warna=="hijau") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan_diterima<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>"; 
                            }
                                 
                        }
                    ?>

                    <!-- DataTales Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <?php
                            if ($subcont=="bahanbaku") {
                                echo "<h6 class=\"m-0 font-weight-bold text-primary\">Kurangi Stok Bahan Baku (Subcont Bahan Baku)</h6>";
                            }
                            else if ($subcont=="produk") {
                                echo "<h6 class=\"m-0 font-weight-bold text-primary\">Kurangi Stok Bahan Baku (Subcont Produk)</h6>";
                            }
                            ?>
                        
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <?php

                                if ($subcont=="bahanbaku") {
                                    echo "<table class=\"table table-bordered\" id=\"dataTable\" width=\"100%\" cellspacing=\"0\">";
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>No</th>";
                                                echo "<th>ID Bahan Baku</th>";
                                                echo "<th>Nama Bahan Baku</th>";
                                                echo "<th>ID PO</th>";
                                                echo "<th>ID MPS</th>";
                                                echo "<th>Tanggal Maksimal Penerimaan</th>";
                                                echo "<th>Qty</th>";
                                                echo "<th>UOM</th>";
                                                echo "<th>Keterangan</th>";
                                                echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                        include("layout/koneksi.php");
                                                $query = "select distinct a.id_bahan_baku as id_bahan_baku, b.nama_bahan_baku as nama_bahan_baku, f.id_po as id_po,
                                                e.id_mps as id_mps, a.tanggal_penerimaan as tanggal_penerimaan, a.qty as qty, b.uom_pemakaian_bb as uom, c.warna as warna,
                                                c.bentuk as bentuk, c.spesifikasi as spesifikasi from purchase_order a, bom b, bahan_baku c, mrp d, mps e, po f, po_pk g 
                                                where a.id_bahan_baku=b.id_bahan_baku and a.id_bahan_baku=c.id_bahan_baku and a.id_mrp=d.id_mrp and d.id_mps=e.id_mps 
                                                and e.id_po=f.id_po and f.id_po=g.id_po and b.level=2 and subcont='tidak'";
                                                $nomor=1;
                                                $result = mysqli_query($link,$query);
                                                while($hasil = mysqli_fetch_assoc($result)){
                                                    echo "<tr>";
                                                    echo "<td>$nomor</td>";
                                                    echo "<td>$hasil[id_bahan_baku]</td>";
                                                    echo "<td>$hasil[nama_bahan_baku]</td>";
                                                    echo "<td>$hasil[id_po]</td>";
                                                    echo "<td>$hasil[id_mps]</td>";
                                                    echo "<td>$hasil[tanggal_penerimaan]</td>";
                                                    echo "<td>$hasil[qty]</td>";
                                                    echo "<td>$hasil[uom]</td>";
                                                    
                                                    $pengantar=urlencode($hasil['id_bahan_baku']);
                                                    $pengantar2=urlencode($hasil['nama_bahan_baku']);
                                                    $pengantar3=urlencode($hasil['id_po']);
                                                    $pengantar4=urlencode($hasil['id_mps']);
                                                    $pengantar5=urlencode($hasil['tanggal_penerimaan']);
                                                    $pengantar6=urlencode($hasil['qty']);
                                                    $pengantar7=urlencode($hasil['uom']);
                                                    $pengantar8=urlencode($hasil['warna']);
                                                    $pengantar9=urlencode($hasil['bentuk']);
                                                    $pengantar10=urlencode($hasil['spesifikasi']);

                                                    $cekketerangan = $hasil['id_po'].", ".$hasil['id_mps'].", ".$hasil['tanggal_penerimaan'].", ".$hasil['nama_bahan_baku'].", ".$hasil['warna'].", ".$hasil['bentuk'].", ".$hasil['spesifikasi'].", subcontbahanbaku";

                                                    $querycariket=mysqli_query($link, "select*from bahan_baku_keluar where id_bahan_baku='$hasil[id_bahan_baku]' and keterangan='$cekketerangan'");
                                                    $hasilcariket = mysqli_num_rows($querycariket);

                                                    if ($hasilcariket<1) {
                                                        echo "<td><a href=\"kurangi_stok_bahan_baku.php?subcont=bahanbaku&keluar=iya&id_bahan_baku=$pengantar&
                                                    nama_bahan_baku=$pengantar2&id_po=$pengantar3&id_mps=$pengantar4&tanggal_penerimaan=$pengantar5&
                                                    qty=$pengantar6&uom=$pengantar7&warna=$pengantar8&bentuk=$pengantar9&spesifikasi=$pengantar10\" 
                                                    class=\"btn btn-danger\">Keluarkan</a></td>";
                                                    echo "</tr>";
                                                    }
                                                    else {
                                                        echo "<td><a href=\"kurangi_stok_bahan_baku.php?subcont=bahanbaku&keluar=iya&id_bahan_baku=$pengantar&
                                                    nama_bahan_baku=$pengantar2&id_po=$pengantar3&id_mps=$pengantar4&tanggal_penerimaan=$pengantar5&
                                                    qty=$pengantar6&uom=$pengantar7&warna=$pengantar8&bentuk=$pengantar9&spesifikasi=$pengantar10\" 
                                                    class=\"btn btn-warning disabled \">Selesai</a></td>";
                                                    echo "</tr>";
                                                    }
                                                    
                                                    $nomor++;

                                                };
                                            
                                        echo "</tbody>";
                                    echo "</table>";
                                }

                                else if ($subcont=="produk") {
                                    echo "<table class=\"table table-bordered\" id=\"dataTable\" width=\"100%\" cellspacing=\"0\">";
                                        echo "<thead>";
                                            echo "<tr>";
                                                echo "<th>No</th>";
                                                echo "<th>ID Bahan Baku</th>";
                                                echo "<th>Nama Bahan Baku</th>";
                                                echo "<th>ID PO</th>";
                                                echo "<th>ID MPS</th>";
                                                echo "<th>Tanggal Maksimal Penerimaan</th>";
                                                echo "<th>Qty</th>";
                                                echo "<th>UOM</th>";
                                                echo "<th>Keterangan</th>";
                                                echo "</tr>";
                                        echo "</thead>";
                                        echo "<tbody>";
                                        include("layout/koneksi.php");
                                                $query = "select distinct d.id_bahan_baku as id_bahan_baku, b.nama_bahan_baku as nama_bahan_baku, f.id_po as id_po,
                                                e.id_mps as id_mps, d.tanggal_penerimaan as tanggal_penerimaan, d.planned_order_release as qty, b.uom_pemakaian_bb as uom, c.warna as warna,
                                                c.bentuk as bentuk, c.spesifikasi as spesifikasi from bom b, bahan_baku c, mrp d, mps e, po f, po_pk g 
                                                where d.id_bahan_baku=b.id_bahan_baku and d.id_bahan_baku=c.id_bahan_baku and d.id_mps=e.id_mps 
                                                and e.id_po=f.id_po and f.id_po=g.id_po and b.level<>0 and subcont='iya' and b.keterangan<>'tidak dibeli'";
                                                $nomor=1;
                                                $result = mysqli_query($link,$query);
                                                while($hasil = mysqli_fetch_assoc($result)){
                                                    echo "<tr>";
                                                    echo "<td>$nomor</td>";
                                                    echo "<td>$hasil[id_bahan_baku]</td>";
                                                    echo "<td>$hasil[nama_bahan_baku]</td>";
                                                    echo "<td>$hasil[id_po]</td>";
                                                    echo "<td>$hasil[id_mps]</td>";
                                                    echo "<td>$hasil[tanggal_penerimaan]</td>";
                                                    echo "<td>$hasil[qty]</td>";
                                                    echo "<td>$hasil[uom]</td>";
                                                    
                                                    $pengantar=urlencode($hasil['id_bahan_baku']);
                                                    $pengantar2=urlencode($hasil['nama_bahan_baku']);
                                                    $pengantar3=urlencode($hasil['id_po']);
                                                    $pengantar4=urlencode($hasil['id_mps']);
                                                    $pengantar5=urlencode($hasil['tanggal_penerimaan']);
                                                    $pengantar6=urlencode($hasil['qty']);
                                                    $pengantar7=urlencode($hasil['uom']);
                                                    $pengantar8=urlencode($hasil['warna']);
                                                    $pengantar9=urlencode($hasil['bentuk']);
                                                    $pengantar10=urlencode($hasil['spesifikasi']);

                                                    $cekketerangan = $hasil['id_po'].", ".$hasil['id_mps'].", ".$hasil['tanggal_penerimaan'].", ".$hasil['nama_bahan_baku'].", ".$hasil['warna'].", ".$hasil['bentuk'].", ".$hasil['spesifikasi'].", subcontproduk";

                                                    $querycariket=mysqli_query($link, "select*from bahan_baku_keluar where id_bahan_baku='$hasil[id_bahan_baku]' and keterangan='$cekketerangan'");
                                                    $hasilcariket = mysqli_num_rows($querycariket);

                                                    if ($hasilcariket<1) {
                                                    echo "<td><a href=\"kurangi_stok_bahan_baku.php?subcont=produk&keluar=iya&id_bahan_baku=$pengantar&
                                                    nama_bahan_baku=$pengantar2&id_po=$pengantar3&id_mps=$pengantar4&tanggal_penerimaan=$pengantar5&
                                                    qty=$pengantar6&uom=$pengantar7&warna=$pengantar8&bentuk=$pengantar9&spesifikasi=$pengantar10\" 
                                                    class=\"btn btn-danger\">Keluarkan</a></td>";
                                                    echo "</tr>";
                                                    }
                                                    
                                                    else {
                                                        echo "<td><a href=\"kurangi_stok_bahan_baku.php?subcont=produk&keluar=iya&id_bahan_baku=$pengantar&
                                                    nama_bahan_baku=$pengantar2&id_po=$pengantar3&id_mps=$pengantar4&tanggal_penerimaan=$pengantar5&
                                                    qty=$pengantar6&uom=$pengantar7&warna=$pengantar8&bentuk=$pengantar9&spesifikasi=$pengantar10\" 
                                                    class=\"btn btn-warning disabled\">Selesai</a></td>";
                                                    echo "</tr>";
                                                    }

                                                    $nomor++;

                                                };
                                            
                                        echo "</tbody>";
                                    echo "</table>";
                                }
                                ?>
                                    

                            </div>

                            
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

    
        <?php
        include ("layout/footer.php");
         ?>
        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <?php
    include ("layout/plugin_and_logout_modal.php")
    ?>

    <!--modal untuk hapus-->


</body>

</html>