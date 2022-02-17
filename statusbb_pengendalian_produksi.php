<?php
    include("layout/session.php");
?>

<?php
    include("layout/koneksi.php");

    // cek apakah form telah di submit
    if (isset($_POST["submit"])) {
        // form telah disubmit, proses data
        $inputID = htmlentities(strip_tags(trim($_POST["inputID"])));
        $inputIDProduk = htmlentities(strip_tags(trim($_POST["inputIDProduk"])));
        $inputTanggalProduksi = htmlentities(strip_tags(trim($_POST["inputTanggalProduksi"])));
        $inputJumlahProduksi = htmlentities(strip_tags(trim($_POST["inputJumlahProduksi"])));
        $inputUOM = htmlentities(strip_tags(trim($_POST["inputUOM"])));

        //menyiapkan variabel untuk pesan error
        $pesan="";
        $warna="";

        $query = "insert into tempbbproduksi (level, id_bahan_baku, nama_bahan_baku, jml_pemakaian_bb, uom_pemakaian_bb, total_pemakaian_bb) 
                    select level, id_bahan_baku, nama_bahan_baku, jml_pemakaian_bb, uom_pemakaian_bb, 
                  jml_pemakaian_bb*$inputJumlahProduksi as total_pemakaian_bb from bom where id_produk_bom='$inputIDProduk' 
                  and level='1'";
        $result = mysqli_query($link, $query);

        $idbahanbaku = array();
        $totalpemakaianbahanbaku = array();
        $stokbahanbaku= array();
        $uompemakaianbahanbaku = array();

        //untuk mengambil data dari tabel sementara, selanjutnya data tsb akan digunakan untuk mengurangi stok bahan baku
        //untuk melihat stok bahan baku terbaru
        $query3= "select a.id_bahan_baku as id_bahan_baku, a.total_pemakaian_bb as total_pemakaian_bb, 
        b.stok as stok, a.uom_pemakaian_bb as uom_pemakaian_bb from tempbbproduksi a, stok_bahan_baku b 
        where a.id_bahan_baku=b.id_bahan_baku";
        $result3 = mysqli_query($link, $query3);
        $jumlah_data = mysqli_num_rows($result3);
        $k=0;
        while ($hasil3 = mysqli_fetch_assoc($result3)) {
            $idbahanbaku[$k]= $hasil3['id_bahan_baku'];
            $totalpemakaianbahanbaku[$k] = $hasil3['total_pemakaian_bb'];
            $stokbahanbaku[$k]= $hasil3['stok'];
            $uompemakaianbahanbaku[$k] = $hasil3['uom_pemakaian_bb'];
            $k++;
        }

        //untuk mengetahui apakah stok bahan baku mencukupi/tidak untuk melakukan proses produksi
        $peringatan =0;
        for ($l=0; $l < count($stokbahanbaku); $l++) { 
            if ($stokbahanbaku[$l] < $totalpemakaianbahanbaku[$l]) {
                $peringatan = $peringatan + 1;
            }
        }

        if ($jumlah_data < 1 ) {
            $pesan .= "Anda belum menyusun Bill of Material untuk produk ini. ";  
        }

        if ($peringatan>0) {
            $pesan .="Stok bahan baku saat ini belum tersedia untuk melakukan proses produksi";
            $querygagal = "truncate tempbbproduksi";
            $resultgagal = mysqli_query($link, $querygagal);
        }


        if ($pesan==="") {
            //jalankan query insert
            $tanggalterkini= date("Y-m-d");
 
            for ($j=0; $j < count($idbahanbaku); $j++) { 
                $idbbkeluar='PRIN'.$inputID.".".$j;
                $query4 = "update stok_bahan_baku set stok=stok-$totalpemakaianbahanbaku[$j] where id_bahan_baku='$idbahanbaku[$j]'";
                $result4 = mysqli_query($link, $query4);

                $queryhistoribb = "insert into bahan_baku_keluar values('$idbbkeluar','$tanggalterkini',
                '$idbahanbaku[$j]',$totalpemakaianbahanbaku[$j],'$uompemakaianbahanbaku[$j]','$inputID')";
                $resulthistoribb = mysqli_query($link, $queryhistoribb);
            }

            $query5 = "truncate tempbbproduksi";
            $result5 = mysqli_query($link, $query5);

            $query6 = "update pengendalian_produksi set status_bahan_baku='Sudah divalidasi' where id_produksi='$inputID'";
            $result6= mysqli_query($link, $query6);

            if ($result4) {
                $pesandikirim .="Bahan Baku berhasil digunakan.";
                $pesan_dikirim = urlencode($pesandikirim);
                header("Location: pengendalian_produksi.php?pesandikirim=$pesan_dikirim");
                die();
            }
            else {
                die ("Query gagal dijalankan: ".mysqli_errno($link).
                " - ".mysqli_error($link));
            }
        }
    }

    else {  
        $diterima_id=$_GET['id_produksi'];

        $query = "SELECT a.id_produksi as id_produksi, b.id_produk as id_produk, b.tanggal_produksi as tanggal_produksi, 
        b.jumlah_produksi as jumlah_produksi, b.uom as uom from pengendalian_produksi a, schedule_produksi b 
        where a.id_produksi=b.id_produksi and a.id_produksi='$diterima_id'";
        $result = mysqli_query($link, $query);
        $hasil = mysqli_fetch_assoc($result);

        $inputID=$hasil['id_produksi'];
        $inputIDProduk=$hasil['id_produk'];
        $inputTanggalProduksi=$hasil['tanggal_produksi'];
        $inputJumlahProduksi=$hasil['jumlah_produksi'];
        $inputUOM=$hasil['uom'];
        $pesan="";
        $isi_select="";
        $warna="";
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
    <title>Pengendalian Produksi</title>
    <?php
    include ("layout/title_import.php");
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
                        if ($pesan !== "") {
                            if ($warna=="hijau") {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-success alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                            }
                            else {
                                echo "<div class=\"col-md-12 col-sm-12 alert alert-danger alert-dismissible fade show notifikasiperingatan\">$pesan<button type=\"button\" class=\"close\" data-dismiss=\"alert\">
                                <span>&times;</span></button></div>";  
                            }

                        }
                    ?>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Tambah Bahan Baku</h6>
                        </div>
                        <div class="card-body">
                            <form action="statusbb_pengendalian_produksi.php" class="col-10 offset-1" method="post">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputID">ID Produksi</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputID" id="inputID" readonly value="<?php echo $inputID ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputIDProduk">ID Produk</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputIDProduk" id="inputIDProduk" readonly value="<?php echo $inputIDProduk ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputTanggalProduksi">Tanggal Produksi</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputTanggalProduksi" id="inputTanggalProduksi" readonly value="<?php echo $inputTanggalProduksi ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputJumlahProduksi">Jumlah Produksi</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputJumlahProduksi" id="inputJumlahProduksi" readonly value="<?php echo $inputJumlahProduksi ?>">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label" for="inputUOM">UOM</label>
                                    <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inputUOM" id="inputUOM" readonly value="<?php echo $inputUOM ?>">
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Level</th>
                                                <th>ID Bahan Baku</th>
                                                <th>Nama Bahan Baku</th>
                                                <th>Jumlah Pemakaian</th>
                                                <th>UOM Pemakaian</th>
                                                <th>Total Pemakaian</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if ($inputID=="") {
                                                    
                                                }
                                                else {
                                                    include("layout/koneksi.php");
                                                
                                                    $query = "select level, id_bahan_baku, nama_bahan_baku, jml_pemakaian_bb, uom_pemakaian_bb, 
                                                    jml_pemakaian_bb*$inputJumlahProduksi as total_pemakaian_bb from bom where id_produk_bom='$inputIDProduk' 
                                                    and level='1'";
                                                    $nomor=1;
                                                    $result = mysqli_query($link,$query);
                                                    while($hasil = mysqli_fetch_assoc($result)){
                                                        echo "<tr>";
                                                        echo "<td>$nomor</td>";
                                                        echo "<td>$hasil[level]</td>";
                                                        echo "<td>$hasil[id_bahan_baku]</td>";
                                                        echo "<td>$hasil[nama_bahan_baku]</td>";
                                                        echo "<td>$hasil[jml_pemakaian_bb]</td>";
                                                        echo "<td>$hasil[uom_pemakaian_bb]</td>";
                                                        echo "<td>$hasil[total_pemakaian_bb]</td>";
                                                        echo "</tr>";
                                                        $nomor++;
                                                    };
                                                }

                                            ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="form-group row">
                                    <div class="col">                                   
                                        <button class="btn btn-primary mt-3 mb-3" type="submit" name="submit">Gunakan Bahan Baku</button>
                                    </div>
                                </div>
                                
                            </form>
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

    <!--Untuk tanggal-->
        <script type="text/javascript">
            $function () {
                $('.datepicker').datepicker({
                    startDate: '-3d';
                });

            };
        </script>
</body>

</html>